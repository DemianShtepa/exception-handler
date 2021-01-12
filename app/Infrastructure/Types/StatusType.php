<?php

declare(strict_types=1);

namespace App\Infrastructure\Types;

use App\Domain\ValueObjects\Status\HandledStatus;
use App\Domain\ValueObjects\Status\SolvedStatus;
use App\Domain\ValueObjects\Status\Status;
use App\Domain\ValueObjects\Status\UnhandledStatus;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Exception;
use ReflectionObject;

class StatusType extends Type
{
    private const STATUS_UNHANDLED = 'unhandled';
    private const STATUS_HANDLED = 'handled';
    private const STATUS_SOLVED = 'solved';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        return "VARCHAR(2048) COMMENT '(DC2Type:exception_status)'";
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (!in_array($value, $this->getStatuses())) {
            return new Exception('Invalid state.');
        }

        return $this->createStatusByName($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!($value instanceof Status)) {
            throw new Exception('Invalid state.');
        }

        return $value->getName();
    }

    public function getName()
    {
        return 'exception_status';
    }

    private function getStatuses(): array
    {
        $reflectedObject = new ReflectionObject($this);

        return $reflectedObject->getConstants();
    }

    private function createStatusByName(string $name): Status
    {
        if ($name === self::STATUS_HANDLED) {
            return new HandledStatus();
        }
        if ($name === self::STATUS_SOLVED) {
            return new SolvedStatus();
        }
        return new UnhandledStatus();
    }
}
