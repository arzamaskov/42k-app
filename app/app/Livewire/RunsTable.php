<?php

namespace App\Livewire;

use App\Runs\Application\Services\RunService;
use Illuminate\Foundation\Application;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class RunsTable extends Component
{
    use WithPagination;

    public function render(): Application|View
    {
        $userId = auth()->id();
        $runs = app(RunService::class)->getUserRuns($userId);

        return view('livewire.runs-table', [
            'runs' => $runs,
        ]);
    }

    public function deleteRun(string $runId): void
    {
        app(RunService::class)->deleteRun($runId);

        session()->flash('success', 'Пробежка удалена');
        $this->dispatch('run-deleted');
    }
}
