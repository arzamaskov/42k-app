<?php

declare(strict_types=1);

namespace Feature\Runs;

use App\Runs\Application\Services\RunService;
use App\Runs\Domain\Entity\Run;
use App\Users\Infrastructure\Database\Eloquent\Models\UserModel;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RunsControllerTest extends TestCase
{
    use RefreshDatabase;

    private UserModel $user;
    private RunService $runService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserModel::factory()->create();
        $this->runService = app(RunService::class);
    }

    public function test_index_displays_runs_list(): void
    {
        // Создаем пробежку для пользователя
        $run = new Run(
            userId: $this->user->id,
            runAt: new DateTimeImmutable('2025-01-15 10:00:00'),
            distance: 5000,
            duration: 1800,
            avgHr: 150,
            id: 'run123'
        );

        $this->runService->createRun($this->user->id, [
            'run_at' => '2025-01-15 10:00:00',
            'distance' => '5000',
            'duration' => '1800',
            'avg_hr' => '150'
        ]);

        $response = $this->actingAs($this->user)
            ->get('/runs');

        $response->assertOk();
        $response->assertViewIs('runs.index');
        $response->assertViewHas('runs');
    }

    public function test_create_displays_form(): void
    {
        $response = $this->actingAs($this->user)
            ->get('/runs/create');

        $response->assertOk();
        $response->assertViewIs('runs.create');
    }

    public function test_store_creates_run_and_redirects(): void
    {
        $runData = [
            'run_at' => '2025-01-15 10:00:00',
            'distance' => '5000',
            'duration' => '1800',
            'avg_hr' => '150',
            'cadence' => '180',
            'rpe' => '7',
            'notes' => 'Тестовая пробежка'
        ];

        $response = $this->actingAs($this->user)
            ->post('/runs', $runData);
        
        $response->assertRedirectToRoute('runs.index');
        $response->assertSessionHas('success', 'Пробежка успешно добавлена');

        // Проверяем, что пробежка создалась в БД
        $this->assertDatabaseHas('runs', [
            'user_id' => $this->user->id,
            'distance' => 5000,
            'duration' => 1800,
            'avg_hr' => 150,
            'cadence' => 180,
            'rpe' => 7,
            'notes' => 'Тестовая пробежка'
        ]);
    }

    public function test_store_validates_required_fields(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/runs', []);

        $response->assertSessionHasErrors(['run_at', 'distance', 'duration']);
    }

    public function test_store_validates_distance_range(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/runs', [
                'run_at' => '2025-01-15 10:00:00',
                'distance' => '0', // Меньше минимума
                'duration' => '1800'
            ]);

        $response->assertSessionHasErrors(['distance']);
    }

    public function test_store_validates_duration_range(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/runs', [
                'run_at' => '2025-01-15 10:00:00',
                'distance' => '5000',
                'duration' => '0' // Меньше минимума
            ]);

        $response->assertSessionHasErrors(['duration']);
    }

    public function test_store_validates_future_date(): void
    {
        $futureDate = now()->addDay()->format('Y-m-d H:i:s');

        $response = $this->actingAs($this->user)
            ->post('/runs', [
                'run_at' => $futureDate,
                'distance' => '5000',
                'duration' => '1800'
            ]);

        $response->assertSessionHasErrors(['run_at']);
    }

    public function test_edit_displays_form_with_run_data(): void
    {
        // Создаем пробежку
        $run = $this->runService->createRun($this->user->id, [
            'run_at' => '2025-01-15 10:00:00',
            'distance' => '5000',
            'duration' => '1800',
            'avg_hr' => '150'
        ]);

        $response = $this->actingAs($this->user)
            ->get("/runs/{$run->id}/edit");

        $response->assertOk();
        $response->assertViewIs('runs.edit');
        $response->assertViewHas('run', $run);
    }

    public function test_update_modifies_run_and_redirects(): void
    {
        // Создаем пробежку
        $run = $this->runService->createRun($this->user->id, [
            'run_at' => '2025-01-15 10:00:00',
            'distance' => '5000',
            'duration' => '1800',
            'avg_hr' => '150'
        ]);

        $updateData = [
            'run_at' => '2025-01-15 11:00:00',
            'distance' => '10000',
            'duration' => '3600',
            'avg_hr' => '160',
            'cadence' => '185',
            'rpe' => '8',
            'notes' => 'Обновленная пробежка'
        ];

        $response = $this->actingAs($this->user)
            ->put("/runs/{$run->id}", $updateData);

        $response->assertRedirectToRoute('runs.index');
        $response->assertSessionHas('success', 'Пробежка успешно обновлена');

        // Проверяем, что пробежка обновилась в БД
        $this->assertDatabaseHas('runs', [
            'id' => $run->id,
            'distance' => 10000,
            'duration' => 3600,
            'avg_hr' => 160,
            'cadence' => 185,
            'rpe' => 8,
            'notes' => 'Обновленная пробежка'
        ]);
    }

    public function test_destroy_deletes_run_and_redirects(): void
    {
        // Создаем пробежку
        $run = $this->runService->createRun($this->user->id, [
            'run_at' => '2025-01-15 10:00:00',
            'distance' => '5000',
            'duration' => '1800'
        ]);

        $response = $this->actingAs($this->user)
            ->delete("/runs/{$run->id}");

        $response->assertRedirectToRoute('runs.index');
        $response->assertSessionHas('success', 'Пробежка удалена');

        // Проверяем, что пробежка удалилась из БД
        $this->assertDatabaseMissing('runs', [
            'id' => $run->id
        ]);
    }

    public function test_guest_cannot_access_runs(): void
    {
        $response = $this->get('/runs');
        $response->assertRedirect('/login');
    }
}
