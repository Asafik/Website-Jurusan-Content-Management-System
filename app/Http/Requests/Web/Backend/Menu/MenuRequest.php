<?php

namespace App\Http\Requests\Web\Backend\Menu;

use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
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
            'name' => 'required',
            'link' => 'required',
            'order' => 'required',
            'is_parent' => 'required',
            'parent' => 'required',
            'level' => 'required',
            'link_target' => 'required',
            'is_external_link' => 'required',
        ];
    }
}
