<?php

declare(strict_types=1);

namespace Szopen\SimpleAccessControl\Domain\User;

use Szopen\SimpleAccessControl\Domain\PermissionsCollection;

interface UserWithPermissions
{
    public function getPermissions(): PermissionsCollection;
}
