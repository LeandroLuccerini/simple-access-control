<?php

namespace Szopen\SimpleAccessControl\Domain\Checker;

use Szopen\SimpleAccessControl\Domain\Action;
use Szopen\SimpleAccessControl\Domain\PermissionsCollection;

class UnanimousPermissionCheckerStrategy implements PermissionCheckerStrategy
{
    public function canPerformAction(Action $action, PermissionsCollection $permissions): bool
    {
        $isAllowed = false;
        foreach ($permissions as $permission) {
            if ($permission->isAppliedTo($action) && !$permission->isAllowed()) {
                return false;
            }

            if ($permission->isAppliedTo($action) && $permission->isAllowed()) {
                $isAllowed = true;
            }
        }

        return $isAllowed;
    }
}
