<div class="max-w-5xl mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold">Пробежки</h1>
        <a href="{{ route('runs.create') }}"
           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Создать пробежку
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        @if(count($runs) > 0)
        <ul class="divide-y divide-gray-200">
            @foreach($runs as $run)
            <li class="px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $run->runAt->format('d.m.Y H:i') }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ number_format($run->distance / 1000, 2) }} км за
                                    {{ gmdate('H:i:s', $run->duration) }}
                                </p>
                                @if($run->formatPace())
                                <p class="text-sm text-gray-500">
                                    Темп: {{ $run->formatPace() }}/км
                                </p>
                                @endif
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('runs.edit', $run->id) }}"
                                   class="text-blue-600 hover:text-blue-900 text-sm">
                                    Редактировать
                                </a>
                                <button wire:click="deleteRun('{{ $run->id }}')"
                                        onclick="return confirm('Удалить пробежку?')"
                                        class="text-red-600 hover:text-red-900 text-sm">
                                    Удалить
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            @endforeach
        </ul>
        @else
        <div class="px-6 py-4 text-center text-gray-500">
            <p>У вас пока нет пробежек.</p>
            <a href="{{ route('runs.create') }}" class="text-blue-600 hover:text-blue-900">
                Создать первую пробежку
            </a>
        </div>
        @endif
    </div>
</div>
