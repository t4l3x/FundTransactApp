<?php
declare(strict_types=1);

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class RegistrationResource extends  JsonResource
{
    public function toArray($request)
    {
        return [
            'message' => 'User registered successfully',
            'user' => [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
                // Add any additional registration-specific data here
            ],
            'token' => $this->createToken('auth_token')->plainTextToken,
        ];
    }
}
