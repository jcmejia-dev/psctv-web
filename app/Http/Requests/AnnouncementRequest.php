<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class AnnouncementRequest extends FormRequest
{
    public function authorize()
    {
        $user = $this->user(); // this->user() always returns your App\Models\User if logged in

        if ($user instanceof User) {
            return $user->role === 'admin';
        }

        return false;
    }

    public function rules()
    {
        return [
            'title'    => 'required|string|max:255',
            'content'  => 'required|string',
            'audience' => 'required|in:customers,technicians,all',
            'priority' => 'required|in:normal,high',
            'start'    => 'nullable|date',
            'end'      => 'nullable|date|after_or_equal:start',
        ];
    }

    /**
     * Map incoming 'start'/'end' to start_at/end_at for DB
     */
    protected function prepareForValidation()
    {
        if ($this->filled('start')) {
            $this->merge(['start_at' => $this->input('start')]);
        }

        if ($this->filled('end')) {
            $this->merge(['end_at' => $this->input('end')]);
        }
    }
}
