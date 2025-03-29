<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthenticateRepository extends CrudRepository
{
    protected string $model = User::class;

    public function isAuthenticated(): bool
    {
        return Auth::check();
    }

    public function attempt(array $credentials): bool
    {
        return Auth::attempt($credentials);
    }

    public function getCurrentUser(): ?User
    {
        return Auth::user();
    }

    public function getCurrentUserCol(string $col)
    {
        if(!Auth::check())
            return 1;

        return Auth::user()->$col;
    }

    public function logout(): void
    {
        Auth::logout();
    }
}
