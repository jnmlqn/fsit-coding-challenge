<?php

namespace App\Services;

use App\Repositories\CustomerRepositoryInterface;

class CustomerService implements CustomerServiceInterface
{
    /**
     * @var  CustomerRepositoryInterface
     */
    private CustomerRepositoryInterface $customerRepository;

    /**
     * @param  CustomerRepositoryInterface $customerRepository
     * 
     * @return void
     */
    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * @return bool
     */
    public function importUsers(): bool
    {
        return $this->customerRepository->importUsers();
    }

    /**
     * @return array
     */
    public function list(): array
    {
        $customers = $this->customerRepository->list();

        return array_map(function ($customer) {
            return [
                'fullname' => $customer->getFullname(),
                'email' => $customer->getEmail(),
                'country' => $customer->getCountry(),
            ];
        }, $customers);
    }

    /**
     * @param  string  $id
     * 
     * @return array|null
     */
    public function show(string $id): ?array
    {
        $customer = $this->customerRepository->show($id);

        if (is_null($customer)) {
            return null;
        }

        return [
            'fullname' => $customer->getFullname(),
            'email' => $customer->getEmail(),
            'username' => $customer->getUsername(),
            'gender' => $customer->getGender(),
            'country' => $customer->getCountry(),
            'city' => $customer->getCity(),
            'phone' => $customer->getPhone(),
        ];
    }
}
