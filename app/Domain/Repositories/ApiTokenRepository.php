<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\ApiToken;
use App\Domain\Exceptions\ApiToken\TokenNotFoundException;

interface ApiTokenRepository
{
    public function hasByToken(string $token): bool;

    /** @throws TokenNotFoundException */
    public function getByToken(string $token): ApiToken;
}
