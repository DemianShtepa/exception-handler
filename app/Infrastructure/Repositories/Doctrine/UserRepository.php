<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories\Doctrine;

use App\Domain\Entities\User;
use App\Domain\Entities\VirtualProject;
use App\Domain\Exceptions\User\UserNotFoundException;
use App\Domain\Repositories\UserRepository as UserRepositoryInterface;
use App\Domain\ValueObjects\User\Email;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

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

    public function hasByEmail(Email $email): bool
    {
        /** @var User|null $user */
        $user = $this->findOneBy(['email.value' => $email->getValue()]);

        return (bool)$user;
    }

    public function save(User $user): void
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function isUserSubscribedTo(User $user, VirtualProject $virtualProject): bool
    {
        return (bool) $this->_em->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->join('u.subscriptions', 's')
            ->where('u.id = :user')
            ->andWhere('s.id = :virtualProject')
            ->setParameter('user', $user)
            ->setParameter('virtualProject', $virtualProject)
            ->getQuery()
            ->getResult();
    }
}
