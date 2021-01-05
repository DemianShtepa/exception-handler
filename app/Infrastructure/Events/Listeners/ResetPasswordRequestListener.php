<?php

declare(strict_types=1);

namespace App\Infrastructure\Events\Listeners;

use App\Domain\Events\ResetPasswordRequested;
use Illuminate\Contracts\Queue\ShouldQueue;

final class ResetPasswordRequestListener implements ShouldQueue
{
    public function handle(ResetPasswordRequested $passwordRequested): void
    {
    }
}
