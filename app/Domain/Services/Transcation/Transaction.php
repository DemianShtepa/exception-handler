<?php

declare(strict_types=1);

namespace App\Domain\Services\Transcation;

interface Transaction
{
    /**
     * @return mixed
     */
    public function execute(callable $function);
}
