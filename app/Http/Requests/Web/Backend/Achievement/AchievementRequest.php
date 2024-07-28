<?php

namespace App\Http\Requests\Web\Backend\Achievement;

use Illuminate\Foundation\Http\FormRequest;

class AchievementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'location' => 'required',
            'achievement_type_id' => 'required',
            'achievement_level_id' => 'required',
            'achievement_program_studi_id' => 'required',
            'file' => 'mimes:png,jpg,jpeg',
            'date' => 'required',
            'description' => 'required',
        ];
    }
}
