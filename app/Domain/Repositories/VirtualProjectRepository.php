<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\VirtualProject;
use App\Domain\Exceptions\VirtualProject\VirtualProjectNotFoundException;

interface VirtualProjectRepository
{
    /** @throws VirtualProjectNotFoundException */
    public function getByInviteToken(string $token): VirtualProject;

    public function save(VirtualProject $virtualProject): void;
}
