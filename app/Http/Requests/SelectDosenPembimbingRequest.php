<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SelectDosenPembimbingRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        return $user?->isMahasiswa()
            && $user->mahasiswaProfile !== null
            && $user->mahasiswaProfile->dosen_pembimbing_id === null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'dosen_pembimbing_id' => [
                'required',
                'integer',
                Rule::exists(User::class, 'id'),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'dosen_pembimbing_id' => 'dosen pembimbing',
        ];
    }
}
