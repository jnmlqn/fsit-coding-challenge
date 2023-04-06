<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\CustomerController;
use App\Services\CustomerServiceInterface;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tests\TestCase;

class CustomerControllerTest extends TestCase
{
    use WithFaker;

    /**
     * @var  CustomerController
     */
    private CustomerController $customerController;

    /**
     * @var  CustomerServiceInterface
     */
    private CustomerServiceInterface $customerService;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->customerService = $this->mock(CustomerServiceInterface::class);
        $this->customerController = new CustomerController($this->customerService);
    }

    /**
     * @return void
     */
    public function testIndex(): void
    {
        $customers = [
            [
                'fullname' => $this->faker->name(),
                'email' => $this->faker->email(),
                'country' => 'Australia'
            ],
            [
                'fullname' => $this->faker->name(),
                'email' => $this->faker->email(),
                'country' => 'Australia'
            ],
            [
                'fullname' => $this->faker->name(),
                'email' => $this->faker->email(),
                'country' => 'Australia'
            ]
        ];

        $this->customerService
            ->shouldReceive('list')
            ->once()
            ->andReturn($customers);

        $responseMsg = 'Customers were successfully retrieved';
        $responseData = [
            'total' => count($customers),
            'customers' => $customers
        ];
        $responseCode = Response::HTTP_OK;
        $expected = response([
            'message' => $responseMsg,
            'data' => $responseData,
            'status' => $responseCode
        ], $responseCode);
        $actual = $this->customerController->index();
        $expectedContent = json_decode($expected->getContent());
        $actualContent = json_decode($actual->getContent());

        $this->assertInstanceOf(Response::class, $actual);
        $this->assertEquals(Response::HTTP_OK, $actual->getStatusCode());
        $this->assertEquals($expectedContent, $actualContent);
    }

    /**
     * @return void
     */
    public function testShow(): void
    {
        $customerId = $this->faker->uuid();
        $customer = [
            'id' => $customerId,
            'fullname' => $this->faker->name(),
            'email' => $this->faker->email(),
            'username' => $this->faker->username(),
            'gender' => 'Male',
            'country' => 'Australia',
            'city' => $this->faker->word(),
            'phone' => $this->faker->phoneNumber(),
        ];

        $this->customerService
            ->shouldReceive('show')
            ->once()
            ->with($customerId)
            ->andReturn($customer);

        $responseMsg = 'Customer was successfully retrieved';
        $responseData = $customer;
        $responseCode = Response::HTTP_OK;
        $expected = response([
            'message' => $responseMsg,
            'data' => $responseData,
            'status' => $responseCode
        ], $responseCode);
        $actual = $this->customerController->show($customerId);
        $expectedContent = json_decode($expected->getContent());
        $actualContent = json_decode($actual->getContent());

        $this->assertInstanceOf(Response::class, $actual);
        $this->assertEquals(Response::HTTP_OK, $actual->getStatusCode());
        $this->assertEquals($expectedContent, $actualContent);
    }

    /**
     * @return void
     */
    public function testShowNotFound(): void
    {
        $customerId = $this->faker->uuid();

        $this->customerService
            ->shouldReceive('show')
            ->once()
            ->with($customerId)
            ->andReturn(null);

        $responseMsg = 'Customer not found';
        $responseCode = Response::HTTP_NOT_FOUND;
        $expected = response([
            'message' => $responseMsg,
            'data' => null,
            'status' => $responseCode
        ], $responseCode);
        $actual = $this->customerController->show($customerId);
        $expectedContent = json_decode($expected->getContent());
        $actualContent = json_decode($actual->getContent());

        $this->assertInstanceOf(Response::class, $actual);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $actual->getStatusCode());
        $this->assertEquals($expectedContent, $actualContent);
    }
}
