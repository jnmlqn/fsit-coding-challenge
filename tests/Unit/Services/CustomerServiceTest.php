<?php

namespace Tests\Unit\Services;

use App\Entities\Customer;
use App\Services\CustomerService;
use App\Repositories\CustomerRepositoryInterface;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerServiceTest extends TestCase
{
    use WithFaker;

    /**
     * @var  CustomerService
     */
    private CustomerService $customerService;

    /**
     * @var  CustomerRepositoryInterface
     */
    private CustomerRepositoryInterface $customerRepository;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->customerRepository = $this->mock(CustomerRepositoryInterface::class);
        $this->customerService = new CustomerService($this->customerRepository);
    }

    /**
     * @return void
     */
    public function testList(): void
    {
        $customer1 = (new Customer())
            ->setFullname($this->faker->name())
            ->setEmail($this->faker->email())
            ->setCountry('Australia');
        $customer2 = (new Customer())
            ->setFullname($this->faker->name())
            ->setEmail($this->faker->email())
            ->setCountry('Australia');
        $customer3 = (new Customer())
            ->setFullname($this->faker->name())
            ->setEmail($this->faker->email())
            ->setCountry('Australia');
        $customers = [
            $customer1,
            $customer2,
            $customer3
        ];

        $this->customerRepository
            ->shouldReceive('list')
            ->once()
            ->andReturn($customers);

        $expected = array_map(function ($customer) {
            return [
                'fullname' => $customer->getFullname(),
                'email' => $customer->getEmail(),
                'country' => $customer->getCountry(),
            ];
        }, $customers);

        $actual = $this->customerService->list();
        $this->assertEquals($expected, $actual);
        $this->assertCount(count($expected), $actual);
    }

    /**
     * @return void
     */
    public function testShow(): void
    {
        $customer = (new Customer())
            ->setFullname($this->faker->name())
            ->setEmail($this->faker->email())
            ->setUsername($this->faker->username())
            ->setGender('Email')
            ->setCountry('Australia')
            ->setCity($this->faker->word())
            ->setPhone($this->faker->phoneNumber());

        $this->customerRepository
            ->shouldReceive('show')
            ->once()
            ->with($customer->getId())
            ->andReturn($customer);

        $expected = [
            'fullname' => $customer->getFullname(),
            'email' => $customer->getEmail(),
            'username' => $customer->getUsername(),
            'gender' => $customer->getGender(),
            'country' => $customer->getCountry(),
            'city' => $customer->getCity(),
            'phone' => $customer->getPhone(),
        ];

        $actual = $this->customerService->show($customer->getId());
        $this->assertEquals($expected, $actual);
    }
}
