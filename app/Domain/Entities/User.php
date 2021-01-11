<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Domain\ValueObjects\User\Email;
use App\Domain\ValueObjects\User\Name;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class User
{
    private int $id;
    private Name $name;
    private Email $email;
    private string $password;
    private ApiToken $apiToken;
    private Collection $virtualProjects;
    private Collection $subscriptions;

    public function __construct(Name $name, Email $email, string $password, ApiToken $apiToken)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->apiToken = $apiToken;
        $this->apiToken->setUser($this);
        $this->virtualProjects = new ArrayCollection();
        $this->subscriptions = new ArrayCollection();
    }

    public function getApiToken(): ApiToken
    {
        return $this->apiToken;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getVirtualProjects(): Collection
    {
        return $this->virtualProjects;
    }

    public function getSubscriptions(): Collection
    {
        return $this->subscriptions;
    }

    public function subscribe(VirtualProject $virtualProject): void
    {
        $this->subscriptions->add($virtualProject);
    }

    public function unsubscribe(VirtualProject $virtualProject): void
    {
        //dd($this->subscriptions->toArray(), $this->subscriptions->remove($virtualProject->getId()));
        $this->subscriptions->removeElement($virtualProject);
        //$virtualProject->removeSubscriber($this);
    }
}
