<?php
namespace App\Application\Handlers\Commands\Auth;

use App\Application\Commands\Auth\LoginCommand;
use App\Domain\Auth\Repositories\UserRepositoryInterface;
use App\Domain\Auth\ValueObjects\Email;
use App\Infrastructure\Persistence\Eloquent\Models\User as EloquentUser;
use Illuminate\Support\Facades\Hash;

class LoginCommandHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function handle(LoginCommand $command): array
    {
        $email = new Email($command->email);
        $domainUser = $this->userRepository->findByEmail($email);

        if (!$domainUser || !Hash::check($command->password, $domainUser->getPassword()->getHashedValue())) {
            throw new \Exception('Invalid credentials');
        }

        // Sanctum token requires the Eloquent model
        $eloquentUser = EloquentUser::find($domainUser->getId());
        $token = $eloquentUser->createToken('auth-token')->plainTextToken;

        return [
            'user' => [
                'id'    => $domainUser->getId(),
                'name'  => $domainUser->getName(),
                'email' => $domainUser->getEmail()->getValue(),
                'role'  => $domainUser->getRole(),
            ],
            'token' => $token,
        ];
    }
}
