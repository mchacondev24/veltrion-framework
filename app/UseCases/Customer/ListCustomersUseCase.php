<?php
namespace Veltrion\UseCases\Customer;

/* VELTRION_PROTECTION_GUARD v1.0 | Commercial Build Protection */
if(!defined('VELTRION_RUNTIME_GUARD') && file_exists(__DIR__ . '/../bootstrap/guard.php')){ @include_once __DIR__ . '/../bootstrap/guard.php'; }
  use Veltrion\Domain\Repositories\CustomerRepositoryInterface; class ListCustomersUseCase { private CustomerRepositoryInterface $customerRepository; public function __construct(CustomerRepositoryInterface $customerRepository) { $this->customerRepository = $customerRepository; } public function execute(): array { $customers = $this->customerRepository->findAll(); return [ 'success' => true, 'count' => count($customers), 'data' => array_map(fn($c) => $c->toArray(), $customers) ]; } } 