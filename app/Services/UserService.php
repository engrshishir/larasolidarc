<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\RoleRepository;
use App\Repositories\UserAccountRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Exception;

class UserService extends CrudService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly RoleRepository $roleRepository
    ) {
        parent::__construct(
            $userRepository,
            fn() => __('User not found.')
        );
    }

    public function create($data): object
    {
        try {
            DB::beginTransaction();
            $dataForUser = $this->extractUserData($data);
            $role = $this->findUserRole($data['role']);
            $user = $this->createUser($dataForUser, $role);
            DB::commit();
            return $user;
        } catch (\Throwable $th) {
            DB::rollBack();
            \Log::error($th->getMessage());
            throw new Exception('User creation failed: ' . $th->getMessage());
        }
    }

    private function extractUserData($data): array
    {
        return is_array($data) ? Arr::only($data, ['name', 'phone', 'email', 'password']) :
            request()->only(['name', 'phone', 'email', 'password']);
    }

    private function findUserRole($roleName): object
    {
        $role = $this->roleRepository->findByColumn('name', $roleName)->first();
        if (!$role) {
            throw new Exception('User role not found.');
        }
        return $role;
    }

    private function syncUserRoles(object $user, string $roleName): void
    {
        $user->syncRoles($roleName);
    }

    private function assignRoles(object $user, string $roleName): void
    {
        $user->assignRole($roleName);
    }

    private function createUser(array $data, object $role): object
    {
        $user = $this->userRepository->create($data);
        if (!$user) {
            throw new Exception('Error: User creation failed.');
        }
        if ($role) {
            $this->assignRoles($user, $role->name);
        }
        return $user;
    }


    public function update(int $id, array $data): object
    {
        try {
            $dataForUser = $this->extractUserData($data);
            $user = parent::update($id, $dataForUser);

            if ($data['role'] ?? null) {
                $this->syncUserRoles($user, $data['role']);
            }

            return $user;
        } catch (\Throwable $th) {
            \Log::error($th->getMessage());
            throw new Exception('User update synchronization error: ' . $th->getMessage());
        }
    }
}
