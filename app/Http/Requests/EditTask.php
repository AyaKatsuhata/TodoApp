<?php

namespace App\Http\Requests;

use App\Models\Task;
use Illuminate\Validation\Rule;

class EditTask extends CreateTask
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
     * @return array
     */
    public function rules()
    {
        $rule = Parent::rules();

        $status_rule = Rule::in(array_keys(Task::STATUS));
        return $rule + [
            'status' => 'required|'.$status_rule,
        ];
    }

    public function attributes()
    {
        $attributes = Parent::attributes();

        return $attributes + [
            'status' => '状態',
        ];
    }

    public function messages()
    {
        $messages = Parent::messages();

        $status_labels = array_map(function($item){
            return $item['label'];
        },Task::STATUS);
        $status_labels = implode('、', $status_labels);

        return $messages + [
            'status.in' => ':attribute には'.$status_labels.'のいずれかを指定してください。',
        ];
    }
}
