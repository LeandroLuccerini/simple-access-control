<?php

declare(strict_types=1);

namespace Szopen\SimpleAccessControl\Domain\Checker;

use Szopen\SimpleAccessControl\Domain\Action;
use Szopen\SimpleAccessControl\Domain\PermissionsCollection;
use Szopen\SimpleAccessControl\Domain\Checker\PermissionCheckerStrategy;

class AffirmativePermissionCheckerStrategy implements PermissionCheckerStrategy
{
    public function canPerformAction(Action $action, PermissionsCollection $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($permission->isAppliedTo($action) && $permission->isAllowed()) {
                return true;
            }
        }

        return false;
    }
}
