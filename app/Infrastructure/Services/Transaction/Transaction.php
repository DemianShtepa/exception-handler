<?php

declare(strict_types=1);

namespace App\Infrastructure\Services\Transaction;

use App\Domain\Services\Transcation\Transaction as TransactionInterface;
use Doctrine\ORM\EntityManagerInterface;

final class Transaction implements TransactionInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function execute(callable $function)
    {
        return $this->entityManager->transactional($function);
    }
}
