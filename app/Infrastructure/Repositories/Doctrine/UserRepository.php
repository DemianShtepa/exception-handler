<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories\Doctrine;

use App\Domain\Entities\User;
use App\Domain\Exceptions\User\UserNotFoundException;
use App\Domain\Repositories\UserRepository as UserRepositoryInterface;
use App\Domain\ValueObjects\User\Email;
use Doctrine\ORM\EntityRepository;

final class UserRepository extends EntityRepository implements UserRepositoryInterface
{
    public function getByEmail(Email $email): User
    {
        /** @var User|null $user */
        $user = $this->findOneBy(['email.value' => $email->getValue()]);
        if (!$user) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    public function persist(User $user): void
    {
        $this->_em->persist($user);
    }

    public function flush(): void
    {
        $this->_em->flush();
    }

    public function hasByEmail(Email $email): bool
    {
        /** @var User|null $user */
        $user = $this->findOneBy(['email.value' => $email->getValue()]);

        return (bool)$user;
    }
}
