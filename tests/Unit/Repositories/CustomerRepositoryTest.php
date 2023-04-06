<?php

namespace Tests\Unit\Repositories;


use App\Helpers\Database;
use App\Entities\Customer;
use App\Repositories\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\Response as ClienResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CustomerRepositoryTest extends TestCase
{
    use WithFaker;

    /**
     * @var  CustomerRepository
     */
    private CustomerRepository $customerRepository;

    /**
     * @var  Database
     */
    private Database $db;

    /**
     * @var  EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->db = $this->mock(Database::class);
        $this->entityManager = $this->mock(EntityManagerInterface::class);
        $this->customerRepository = new CustomerRepository(
            $this->db,
            $this->entityManager
        );
    }

    /**
     * @return void
     */
    public function testImport(): void
    {
        $users = [
            [
                'name' => [
                    'first' => $this->faker->word(),
                    'last' => $this->faker->word()
                ],
                'email' => $this->faker->email(),
                'login' => [
                    'username' => $this->faker->username(),
                    'md5' => md5('password')
                ],
                'gender' => 'Male',
                'location' => [
                    'country' => 'Australia',
                    'city' => $this->faker->word()
                ],
                'phone' => $this->faker->phoneNumber()
            ]
        ];

        Http::fake([
            '*' => Http::response(
                [
                    'results' => $users
                ],
                Response::HTTP_OK,
                ['Headers']
            ),
        ]);

        $customer = new Customer();

        $this->db
            ->shouldReceive('findBy')
            ->once()
            ->with(
                Customer::class,
                [
                    'email' => $users[0]['email']
                ]
            )
            ->andReturn($customer);

        $customer->setFullname($this->getFullName($users[0]['name']));
        $customer->setEmail($users[0]['email']);
        $customer->setUsername($users[0]['login']['username']);
        $customer->setPassword($users[0]['login']['md5']);
        $customer->setGender($users[0]['gender']);
        $customer->setCountry($users[0]['location']['country']);
        $customer->setCity($users[0]['location']['city']);
        $customer->setPhone($users[0]['phone']);

        $this->entityManager
            ->shouldReceive('persist')
            ->once()
            ->with($customer)
            ->andReturn(true);

        $this->entityManager
            ->shouldReceive('flush')
            ->once()
            ->andReturn(true);

        $actual = $this->customerRepository->import();
        $this->assertTrue($actual);
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

        $this->db
            ->shouldReceive('findAll')
            ->once()
            ->with(Customer::class)
            ->andReturn($customers);

        $actual = $this->customerRepository->list();
        $this->assertEquals($customers, $actual);
        $this->assertCount(count($customers), $actual);
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

        $this->db
            ->shouldReceive('findBy')
            ->once()
            ->with(
                Customer::class,
                [
                    'id' => $customer->getId()
                ]
            )
            ->andReturn($customer);

        $actual = $this->customerRepository->show($customer->getId());
        $this->assertEquals($customer, $actual);
    }

    /**
     * @param  array  $name
     * 
     * @return string
     */
    private function getFullName(array $name): string
    {
        return $name['first'] . ' ' . $name['last'];
    }
}
