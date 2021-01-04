<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories\Doctrine;

use App\Domain\Entities\ResetPasswordRequest;
use App\Domain\Entities\User;
use App\Domain\Exceptions\ResetPasswordRequest\ResetPasswordRequestNotFound;
use App\Domain\Repositories\ResetPasswordRequestRepository as ResetPasswordRequestRepositoryInterface;
use App\Domain\ValueObjects\User\Email;
use Doctrine\ORM\EntityRepository;

final class ResetPasswordRequestRepository extends EntityRepository implements ResetPasswordRequestRepositoryInterface
{

    public function getByToken(string $token): ResetPasswordRequest
    {
        /** @var ResetPasswordRequest|null $request */
        $request = $this->findOneBy(['token' => $token]);
        if (!$request) {
            throw new ResetPasswordRequestNotFound();
        }

        return $request;
    }

    public function getByUser(User $user): ResetPasswordRequest
    {
        /** @var ResetPasswordRequest|null $request */
        $request = $this->findOneBy(['user' => $user->getId()]);
        if (!$request) {
            throw new ResetPasswordRequestNotFound();
        }

        return $request;
    }

    public function persist(ResetPasswordRequest $request): void
    {
        $this->_em->persist($request);
    }

    public function flush(): void
    {
        $this->_em->flush();
    }

    public function remove(ResetPasswordRequest $request): void
    {
        $this->_em->remove($request);
    }
}
