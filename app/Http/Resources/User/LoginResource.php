<?php
declare(strict_types=1);

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'message' => 'User logged in successfully',
            'user' => [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
                // Add any additional login-specific data here
            ],
            'token' => $this->createToken('auth_token')->plainTextToken, // Include the authentication token if needed
        ];
    }
}

