<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CreateCampaignRequest extends FormRequest
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
        return [
            'title'            => 'required|string|max:255',
            'body'             => 'required|string',
            'subscriber_ids'   => 'required|array|min:1',
            'subscriber_ids.*' => 'exists:subscribers,id',
            'scheduled_at'     => 'nullable|date|after_or_equal:now',
        ];
    }

    public function messages()
    {
        return [
            'scheduled_at.after_or_equal' => 'Thời gian lên lịch phải ở tương lai.',
            'subscriber_ids.required'      => 'Vui lòng chọn ít nhất một người nhận.',
        ];
    }
}
