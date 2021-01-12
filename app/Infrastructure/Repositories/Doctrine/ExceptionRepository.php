<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories\Doctrine;

use App\Domain\Entities\Exception;
use App\Domain\Exceptions\Exception\ExceptionNotFoundException;
use App\Domain\Repositories\ExceptionRepository as ExceptionRepositoryInterface;
use Doctrine\ORM\EntityRepository;

final class ExceptionRepository extends EntityRepository implements ExceptionRepositoryInterface
{
    public function save(Exception $exception): void
    {
        $this->_em->persist($exception);
        $this->_em->flush();
    }

    public function getById(int $id): Exception
    {
        /** @var Exception|null $exception */
        $exception = $this->findOneBy(['id' => $id]);

        if (!$exception) {
            throw new ExceptionNotFoundException();
        }

        return $exception;
    }
}
