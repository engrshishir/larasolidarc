<?php

declare(strict_types=1);

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface ServiceInterface
{
    /**
     * Create a new record.
     *
     * @param array $data
     * @return object|null
     */
    public function create(array $data): ?object;

    /**
     * Update an existing record by ID.
     *
     * @param int $id
     * @param array $data
     * @return object|null
     */
    public function update(int $id, array $data): ?object;

    /**
     * Delete a record by ID and update its status.
     *
     * @param int $id
     * @param int $status
     * @return void
     */
    public function delete(int $id, int $status): ?object;

    /**
     * Get a record by ID.
     *
     * @param int $id
     * @return object|null
     */
    public function getById(int $id): ?object;

    /**
     * Get all records.
     *
     * @return Collection
     */
    public function all(): Collection;
}
