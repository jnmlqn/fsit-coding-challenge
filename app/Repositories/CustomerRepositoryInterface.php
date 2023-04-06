<?php

namespace App\Repositories;

use App\Entities\Customer;

interface CustomerRepositoryInterface
{
    /**
     * @return bool
     */
    public function importUsers(): bool;

    /**
     * @return array
     */
    public function list(): array;

    /**
     * @param  string  $id
     * @return Customer|null
     */
    public function show(string $id): ?Customer;
}
