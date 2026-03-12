<?php
namespace App\Application\Handlers\Commands\Customer;

use App\Application\Commands\Customer\DeleteCustomerCommand;
use App\Domain\Customer\Repositories\CustomerRepositoryInterface;

class DeleteCustomerCommandHandler
{
    public function __construct(private CustomerRepositoryInterface $customerRepository) {}

    public function handle(DeleteCustomerCommand $command): bool
    {
        $customer = $this->customerRepository->findById($command->id);
        if (!$customer) {
            throw new \Exception("Customer not found");
        }
        return $this->customerRepository->delete($command->id);
    }
}
