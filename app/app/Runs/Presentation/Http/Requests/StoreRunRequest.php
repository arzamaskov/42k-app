<?php

declare(strict_types=1);

namespace App\Runs\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRunRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'run_at' => ['required', 'date', 'before_or_equal:now'],
            'distance' => ['required', 'integer', 'min:1', 'max:4988970'], // 1м - 4988,97км
            'duration' => ['required', 'integer', 'min:1', 'max:4406400'], // 1с - 51день
            'avg_hr' => ['nullable', 'integer', 'min:30', 'max:250'], // ЧСС
            'cadence' => ['nullable', 'integer', 'min:60', 'max:300'], // шаг/мин
            'rpe' => ['nullable', 'integer', 'min:1', 'max:10'], // RPE 1-10
//            'shoe_id' => ['nullable', 'integer', 'exists:shoes,id'], // если будет таблица обуви
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'run_at.required' => 'Дата пробежки обязательна',
            'run_at.date' => 'Некорректная дата пробежки',
            'run_at.before_or_equal' => 'Дата пробежки не может быть в будущем',
            'distance.required' => 'Дистанция обязательна',
            'distance.integer' => 'Дистанция должна быть числом',
            'distance.min' => 'Дистанция должна быть больше 0 метров',
            'distance.max' => 'Дистанция не может превышать 4988,97 км',
            'duration.required' => 'Время пробежки обязательно',
            'duration.integer' => 'Время должно быть числом',
            'duration.min' => 'Время должно быть больше 0 секунд',
            'duration.max' => 'Время не может превышать 51 дня',
            'avg_hr.integer' => 'ЧСС должна быть числом',
            'avg_hr.min' => 'ЧСС не может быть меньше 30',
            'avg_hr.max' => 'ЧСС не может быть больше 250',
            'cadence.integer' => 'Каденс должен быть числом',
            'cadence.min' => 'Каденс не может быть меньше 60',
            'cadence.max' => 'Каденс не может быть больше 300',
            'rpe.integer' => 'RPE должен быть числом',
            'rpe.min' => 'RPE не может быть меньше 1',
            'rpe.max' => 'RPE не может быть больше 10',
            'notes.max' => 'Заметки не могут превышать 1000 символов',
        ];
    }
}
