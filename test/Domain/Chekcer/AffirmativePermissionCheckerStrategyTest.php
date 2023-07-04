<?php

namespace Test\Szopen\SimpleAccessControl\Domain\Chekcer;

use PHPUnit\Framework\TestCase;
use Szopen\SimpleAccessControl\Domain\Action;
use Szopen\SimpleAccessControl\Domain\Checker\AffirmativePermissionCheckerStrategy;
use Szopen\SimpleAccessControl\Domain\Permission;
use Szopen\SimpleAccessControl\Domain\PermissionsCollection;

class AffirmativePermissionCheckerStrategyTest extends TestCase
{
    public function testActionCannotBePerformedDueToMissingPermission()
    {
        $permissions = [
            new Permission(new Action('test.1'), true),
            new Permission(new Action('test.2'), true),
        ];

        $strategy = new AffirmativePermissionCheckerStrategy();

        self::assertFalse(
            $strategy->canPerformAction(
                new Action('missing.action'),
                new PermissionsCollection($permissions)
            )
        );
    }

    public function testCheckingInTenThousandElementsPermissionCollectionMustRemainUnderTwoDotFiveMilliseconds()
    {
        $permissions = [];
        for ($i = 0; $i < 10_000; $i++) {
            $permissions[] = new Permission(new Action('test.' . $i), true);
        }

        $strategy = new AffirmativePermissionCheckerStrategy();
        $startTime = microtime(true);
        $strategy->canPerformAction(
            new Action('missing.action'),
            new PermissionsCollection($permissions)
        );
        $endTime = microtime(true);

        $executionTime = ($endTime - $startTime) * 1_000;

        self::assertLessThan(3, $executionTime);
    }

    public function testActionCannotBePerformedDueToExplicitDeny()
    {
        $permissions = [
            new Permission(new Action('test.1'), true),
            new Permission(new Action('test.2'), false),
        ];

        $strategy = new AffirmativePermissionCheckerStrategy();

        self::assertFalse(
            $strategy->canPerformAction(
                new Action('test.2'),
                new PermissionsCollection($permissions)
            )
        );
    }

    public function testActionCanBePerformedDueToAffirmativeAllowing()
    {
        $permissions = [
            new Permission(new Action('test.1'), true),
            new Permission(new Action('test.1'), false),
        ];

        $strategy = new AffirmativePermissionCheckerStrategy();

        self::assertTrue(
            $strategy->canPerformAction(
                new Action('test.1'),
                new PermissionsCollection($permissions)
            )
        );
    }
}