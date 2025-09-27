<?php

declare(strict_types=1);

namespace App\Runs\Presentation\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Runs\Application\Services\RunService;
use App\Runs\Presentation\Http\Requests\StoreRunRequest;
use App\Runs\Presentation\Http\Requests\UpdateRunRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RunsController extends Controller
{
    public function __construct(
        private readonly RunService $runService,
    ) {
    }

    /**
     * Показать список пробежек пользователя
     */
    public function index(Request $request): View
    {
        $userId = $request->user()->id;
        $runs = $this->runService->getUserRuns($userId);

        return view('runs.index', compact('runs'));
    }

    /**
     * Показать форму создания пробежки
     */
    public function create(): View
    {
        return view('runs.create');
    }

    /**
     * Сохранить новую пробежку
     * @throws \Exception
     */
    public function store(StoreRunRequest $request): RedirectResponse
    {
        $userId = $request->user()->id;
        $this->runService->createRun($userId, $request->validated());

        return redirect()->route('runs.index')
            ->with('success', 'Пробежка успешно добавлена');
    }

    /**
     * Показать форму редактирования пробежки
     */
    public function edit(string $runId): View
    {
        $run = $this->runService->getRun($runId);

        return view('runs.edit', compact('run'));
    }

    /**
     * Обновить пробежку
     * @throws \Exception
     */
    public function update(UpdateRunRequest $request, string $runId): RedirectResponse
    {
        $this->runService->updateRun($runId, $request->validated());

        return redirect()->route('runs.index')
            ->with('success', 'Пробежка успешно обновлена');
    }

    /**
     * Удалить пробежку
     */
    public function destroy(string $runId): RedirectResponse
    {
        $this->runService->deleteRun($runId);

        return redirect()->route('runs.index')
            ->with('success', 'Пробежка удалена');
    }
}
