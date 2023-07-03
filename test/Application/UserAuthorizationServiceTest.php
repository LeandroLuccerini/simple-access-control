<?php

namespace Test\Szopen\SimpleAccessControl\Application;

use PHPUnit\Framework\TestCase;
use Szopen\SimpleAccessControl\Application\UserAuthorizationService;
use Szopen\SimpleAccessControl\Domain\Action;
use Szopen\SimpleAccessControl\Domain\Checker\AffirmativePermissionCheckerStrategy;
use Szopen\SimpleAccessControl\Domain\Permission;
use Szopen\SimpleAccessControl\Domain\PermissionsCollection;
use Szopen\SimpleAccessControl\Domain\User\UserWithPermissions;

class UserAuthorizationServiceTest extends TestCase
{
    public function testCanUserPerformActionReturnsTrueDueToAffirmativeStrategy()
    {
        $service = new UserAuthorizationService(new AffirmativePermissionCheckerStrategy());

        $permissions = new PermissionsCollection([
            new Permission(new Action('test.action.1'), true),
            new Permission(new Action('test.action.2'), true),
        ]);

        $user = $this->createStub(UserWithPermissions::class);
        $user->method('getPermissions')
            ->willReturn($permissions);

        self::assertTrue($service->canUserPerformAction($user, new Action('test.action.2')));
    }

    public function testCanUserPerformActionReturnsFalseDueToMissingPermission()
    {
        $service = new UserAuthorizationService(new AffirmativePermissionCheckerStrategy());

        $permissions = new PermissionsCollection([
            new Permission(new Action('test.action.1'), true),
        ]);

        $user = $this->createStub(UserWithPermissions::class);
        $user->method('getPermissions')
            ->willReturn($permissions);

        self::assertFalse($service->canUserPerformAction($user, new Action('test.action.2')));
    }
}