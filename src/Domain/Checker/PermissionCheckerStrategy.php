<?php

declare(strict_types=1);

namespace Szopen\SimpleAccessControl\Domain\Checker;

use Szopen\SimpleAccessControl\Domain\Action;
use Szopen\SimpleAccessControl\Domain\PermissionsCollection;

interface PermissionCheckerStrategy
{
    public function canPerformAction(Action $action, PermissionsCollection $permissions): bool;
}
