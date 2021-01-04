<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories\Doctrine;

use App\Domain\Entities\ApiToken;
use App\Domain\Exceptions\ApiToken\TokenNotFoundException;
use App\Domain\Repositories\ApiTokenRepository as ApiTokenRepositoryInterface;
use Doctrine\ORM\EntityRepository;

final class ApiTokenRepository extends EntityRepository implements ApiTokenRepositoryInterface
{
    public function hasByToken(string $token): bool
    {
        /** @var ApiToken|null $token */
        $token = $this->findOneBy(['token' => $token]);

        return (bool)$token;
    }

    public function getByToken(string $token): ApiToken
    {
        /** @var ApiToken|null $token */
        $token = $this->findOneBy(['token' => $token]);

        if (!$token) {
            throw new TokenNotFoundException();
        }

        return $token;
    }

    public function persist(ApiToken $apiToken): void
    {
        $this->_em->persist($apiToken);
    }

    public function flush(): void
    {
        $this->_em->flush();
    }
}
