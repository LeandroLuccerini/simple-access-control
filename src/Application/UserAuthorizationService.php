<?php

declare(strict_types=1);

namespace Szopen\SimpleAccessControl\Application;

use Szopen\SimpleAccessControl\Domain\Action;
use Szopen\SimpleAccessControl\Domain\Checker\PermissionCheckerStrategy;
use Szopen\SimpleAccessControl\Domain\User\UserWithPermissions;

final class UserAuthorizationService
{
    public function __construct(private PermissionCheckerStrategy $permissionChecker)
    {
    }

    public function canUserPerformAction(UserWithPermissions $user, Action $action): bool
    {
        return $this->permissionChecker
            ->canPerformAction(
                $action,
                $user->getPermissions()
            );
    }
}
