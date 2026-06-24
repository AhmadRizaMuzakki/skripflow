<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class DosenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $dosen = $this->route('dosen');

        return [
            'name' => ['required', 'string', 'max:255'],
            'nomor_induk' => [
                'required',
                'string',
                'max:50',
                Rule::unique(User::class, 'nomor_induk')->ignore($dosen?->id),
            ],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class, 'email')->ignore($dosen?->id),
            ],
            'password' => [
                $dosen ? 'nullable' : 'required',
                'confirmed',
                Password::defaults(),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nama',
            'nomor_induk' => 'NIDN',
            'email' => 'email',
            'password' => 'password',
        ];
    }
}
