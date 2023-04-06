<?php

namespace App\Services;

interface CustomerServiceInterface
{

    /**
     * @return bool
     */
    public function import(): bool;

    /**
     * @return array
     */
    public function list(): array;

    /**
     * @param  string  $id
     * 
     * @return array|null
     */
    public function show(string $id): ?array;
}
