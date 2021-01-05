<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\VirtualProject;

interface VirtualProjectRepository
{
    public function persist(VirtualProject $virtualProject): void;

    public function flush(): void;
}
