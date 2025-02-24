<?php

namespace App\Actions\Fortify;

use Illuminate\Validation\Rules\Password;

trait PasswordValidationRules
{
    /**
     * Minimum password length.
     *
     * @var int
     */
    protected int $min = 8;

    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function passwordRules(): array
    {
        return [
            'required',
            'string',
            Password::min($this->min), // Menggunakan $min untuk panjang minimal
            'confirmed',
        ];
    }
}
