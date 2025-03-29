<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Repositories\AuthenticateRepository;

class AuthenticateService extends CrudService
{
    public const NOT_FOUND_MESSAGE = "User not found";
     
    /*   
    📌 readonly:  
        readonly guarantees that the property can only be set once, which promotes immutability and ensures the property cannot be modified after being initialized.
        When the property is set: You can set the readonly property only once, and that happens during object construction. 
        The readonly keyword is helpful when you want to create immutable objects
    */
    public function __construct(
        private readonly AuthenticateRepository $authenticateRepository
    ) {
        parent::__construct(
            $authenticateRepository,
            fn() => __(self::NOT_FOUND_MESSAGE)
        );
    }

    public function isAuthenticated(): bool
    {
        return $this->authenticateRepository->isAuthenticated();
    }

    public function attempt(array $credentials): bool
    {
        return $this->authenticateRepository->attempt($credentials);
    }

    public function getCurrentUser(): User|null
    {
        return $this->authenticateRepository->getCurrentUser();
    }

    public function getCurrentUserCol(string $col)
    {
        return $this->authenticateRepository->getCurrentUserCol($col);
    }

    public function getAuthId(): int
    {
        return (int) $this->authenticateRepository->getCurrentUserCol('id');
    }

    public function getAuthOrganizationId(): int
    {
        return (int) $this->authenticateRepository->getCurrentUserCol('organization_id');
    }

    public function getAuthOrganization(): object
    {
        return $this->authenticateRepository->getCurrentUser()->organization;
    }

    public function logout(): void
    {
        $this->authenticateRepository->logout();
    }

    public function AuthRole()
    {
        return $this->authenticateRepository->getCurrentUser()->roles();
    }
}