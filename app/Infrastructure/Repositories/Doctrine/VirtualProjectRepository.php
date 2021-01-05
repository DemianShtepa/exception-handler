<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories\Doctrine;

use App\Domain\Entities\VirtualProject;
use App\Domain\Repositories\VirtualProjectRepository as VirtualProjectRepositoryInterface;
use Doctrine\ORM\EntityRepository;

final class VirtualProjectRepository extends EntityRepository implements VirtualProjectRepositoryInterface
{
    public function persist(VirtualProject $virtualProject): void
    {
        $this->_em->persist($virtualProject);
    }

    public function flush(): void
    {
        $this->_em->flush();
    }
}
