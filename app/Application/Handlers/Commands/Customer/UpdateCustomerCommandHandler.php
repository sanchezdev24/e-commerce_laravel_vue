<?php
namespace App\Application\Handlers\Commands\Customer;

use App\Application\Commands\Customer\UpdateCustomerCommand;
use App\Domain\Customer\Repositories\CustomerRepositoryInterface;
use App\Domain\Customer\Entities\Customer;
use App\Domain\Customer\ValueObjects\ContactInfo;
use App\Domain\Customer\ValueObjects\CustomerType;

class UpdateCustomerCommandHandler
{
    public function __construct(private CustomerRepositoryInterface $customerRepository) {}

    public function handle(UpdateCustomerCommand $command): array
    {
        $existing = $this->customerRepository->findById($command->id);
        if (!$existing) throw new \Exception("Customer not found");

        $updated = new Customer(
            $command->id,
            $command->name,
            $command->lastName,
            new ContactInfo($command->email, $command->phone, $command->address),
            new CustomerType($command->type),
            new \DateTime($command->birthDate),
            $existing->isActive(),
        );

        $saved = $this->customerRepository->save($updated);

        return [
            'id'        => $saved->getId(),
            'name'      => $saved->getName(),
            'last_name' => $saved->getLastName(),
            'full_name' => $saved->getFullName(),
            'email'     => $saved->getContactInfo()->getEmail(),
            'phone'     => $saved->getContactInfo()->getPhone(),
            'address'   => $saved->getContactInfo()->getAddress(),
            'type'      => $saved->getType()->getValue(),
            'is_active' => $saved->isActive(),
        ];
    }
}
