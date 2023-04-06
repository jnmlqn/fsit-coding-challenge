<?php

namespace App\Helpers;

use Doctrine\ORM\EntityManagerInterface;
use App\Entities\Customer;

class Database
{
	/**
	 * @var
	 */
	private EntityManagerInterface $entityManager;

	/**
	 * @param  EntityManagerInterface  $entityManager
	 * 
	 * @return void
	 */
	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	/**
	 * @param  string  $class
	 * @param  array $conditions
	 * @param  mixed $value
	 * 
	 * @return Customer|null
	 */
	public function findBy(
		string $class,
		array $conditions
	): ?Customer {
		return $this->entityManager
			->getRepository($class)
			->findOneBy($conditions);
	}

	/**
	 * @param  string  $class
	 * 
	 * @return ?array
	 */
	public function findAll(string $class): ?array
	{
		return $this->entityManager
			->getRepository($class)
			->findAll();
	}
}
