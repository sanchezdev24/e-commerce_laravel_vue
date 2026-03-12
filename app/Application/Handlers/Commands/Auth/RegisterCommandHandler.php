<?php
namespace App\Application\Handlers\Commands\Auth;

use App\Application\Commands\Auth\RegisterCommand;
use App\Domain\Auth\Repositories\UserRepositoryInterface;
use App\Domain\Auth\Entities\User;
use App\Domain\Auth\ValueObjects\Email;
use App\Domain\Auth\ValueObjects\Password;
use App\Infrastructure\Persistence\Eloquent\Models\User as EloquentUser;

class RegisterCommandHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function handle(RegisterCommand $command): array
    {
        $email = new Email($command->email);

        if ($this->userRepository->findByEmail($email)) {
            throw new \Exception('Email already exists');
        }

        $password = new Password($command->password);

        $user = new User(0, $command->name, $email, $password, $command->role);
        $savedUser = $this->userRepository->save($user);

        // Sanctum token requires Eloquent model
        $eloquentUser = EloquentUser::find($savedUser->getId());
        $token = $eloquentUser->createToken('auth-token')->plainTextToken;

        return [
            'user' => [
                'id'    => $savedUser->getId(),
                'name'  => $savedUser->getName(),
                'email' => $savedUser->getEmail()->getValue(),
                'role'  => $savedUser->getRole(),
            ],
            'token' => $token,
        ];
    }
}
