<?php
namespace App\Application\Handlers\Queries\Customer;

use App\Application\Queries\Customer\GetCustomerByIdQuery;
use App\Domain\Customer\Repositories\CustomerRepositoryInterface;

class GetCustomerByIdQueryHandler
{
    public function __construct(private CustomerRepositoryInterface $customerRepository) {}

    public function handle(GetCustomerByIdQuery $query): ?array
    {
        $customer = $this->customerRepository->findById($query->id);
        if (!$customer) return null;

        return [
            'id'         => $customer->getId(),
            'name'       => $customer->getName(),
            'last_name'  => $customer->getLastName(),
            'full_name'  => $customer->getFullName(),
            'email'      => $customer->getContactInfo()->getEmail(),
            'phone'      => $customer->getContactInfo()->getPhone(),
            'address'    => $customer->getContactInfo()->getAddress(),
            'type'       => $customer->getType()->getValue(),
            'birth_date' => $customer->getBirthDate(),
            'is_active'  => $customer->isActive(),
        ];
    }
}
