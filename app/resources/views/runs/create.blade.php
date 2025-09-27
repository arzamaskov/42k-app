<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Создать пробежку') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('runs.store') }}">
    @csrf

                        <!-- Дата пробежки -->
                        <div class="mb-4">
                            <x-input-label for="run_at" :value="__('Дата пробежки')" />
                            <x-text-input id="run_at" class="block mt-1 w-full" type="datetime-local"
                                name="run_at" :value="old('run_at', now()->format('Y-m-d\TH:i'))" required />
                            <x-input-error :messages="$errors->get('run_at')" class="mt-2" />
                        </div>

                        <!-- Дистанция -->
                        <div class="mb-4">
                            <x-input-label for="distance" :value="__('Дистанция (метры)')" />
                            <x-text-input id="distance" class="block mt-1 w-full" type="number"
                                name="distance" :value="old('distance')" required />
                            <x-input-error :messages="$errors->get('distance')" class="mt-2" />
                        </div>
                        <!-- Время -->
                        <div class="mb-4">
                            <x-input-label for="duration" :value="__('Время (секунды)')" />
                            <x-text-input id="duration" class="block mt-1 w-full" type="number"
                                          name="duration" :value="old('duration')" required />
                            <x-input-error :messages="$errors->get('duration')" class="mt-2" />
                        </div>

                        <!-- Средняя ЧСС -->
                        <div class="mb-4">
                            <x-input-label for="avg_hr" :value="__('Средняя ЧСС (уд/мин)')" />
                            <x-text-input id="avg_hr" class="block mt-1 w-full" type="number"
                                          name="avg_hr" :value="old('avg_hr')" />
                            <x-input-error :messages="$errors->get('avg_hr')" class="mt-2" />
                        </div>

                        <!-- Каденс -->
                        <div class="mb-4">
                            <x-input-label for="cadence" :value="__('Каденс (шаг/мин)')" />
                            <x-text-input id="cadence" class="block mt-1 w-full" type="number"
                                          name="cadence" :value="old('cadence')" />
                            <x-input-error :messages="$errors->get('cadence')" class="mt-2" />
                        </div>

                        <!-- RPE -->
                        <div class="mb-4">
                            <x-input-label for="rpe" :value="__('RPE (1-10)')" />
                            <x-text-input id="rpe" class="block mt-1 w-full" type="number"
                                          name="rpe" :value="old('rpe')" min="1" max="10" />
                            <x-input-error :messages="$errors->get('rpe')" class="mt-2" />
                        </div>

                        <!-- Заметки -->
                        <div class="mb-4">
                            <x-input-label for="notes" :value="__('Заметки')" />
                            <textarea id="notes" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                      name="notes" rows="3">{{ old('notes') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('runs.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                                {{ __('Отмена') }}
                            </a>
                            <x-primary-button>
                                {{ __('Создать пробежку') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
