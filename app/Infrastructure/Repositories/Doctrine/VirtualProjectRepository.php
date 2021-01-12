<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories\Doctrine;

use App\Domain\Entities\VirtualProject;
use App\Domain\Exceptions\VirtualProject\VirtualProjectNotFoundException;
use App\Domain\Repositories\VirtualProjectRepository as VirtualProjectRepositoryInterface;
use Doctrine\ORM\EntityRepository;

final class VirtualProjectRepository extends EntityRepository implements VirtualProjectRepositoryInterface
{
    public function getByInviteToken(string $token): VirtualProject
    {
        /** @var VirtualProject|null $project */
        $project = $this->findOneBy(['inviteToken' => $token]);

        if (!$project) {
            throw new VirtualProjectNotFoundException();
        }

        return $project;
    }

    public function save(VirtualProject $virtualProject): void
    {
        $this->_em->persist($virtualProject);
        $this->_em->flush();
    }

    public function getById(int $id): VirtualProject
    {
        /** @var VirtualProject|null $project */
        $project = $this->findOneBy(['id' => $id]);

        if (!$project) {
            throw new VirtualProjectNotFoundException();
        }

        return $project;
    }

    public function getByPushToken(string $token): VirtualProject
    {
        /** @var VirtualProject|null $project */
        $project = $this->findOneBy(['pushToken' => $token]);

        if (!$project) {
            throw new VirtualProjectNotFoundException();
        }

        return $project;
    }
}
