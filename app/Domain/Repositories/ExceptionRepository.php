<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\Exception;
use App\Domain\Exceptions\Exception\ExceptionNotFoundException;

interface ExceptionRepository
{
    /** @throws ExceptionNotFoundException */
    public function getById(int $id): Exception;

    public function save(Exception $exception): void;
}
