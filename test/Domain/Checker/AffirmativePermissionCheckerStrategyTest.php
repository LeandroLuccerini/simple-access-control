<?php

namespace Test\Szopen\SimpleAccessControl\Domain\Checker;

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use Szopen\SimpleAccessControl\Domain\Action;
use Szopen\SimpleAccessControl\Domain\Checker\AffirmativePermissionCheckerStrategy;
use Szopen\SimpleAccessControl\Domain\Permission;
use Szopen\SimpleAccessControl\Domain\PermissionsCollection;
use Test\Szopen\SimpleAccessControl\Domain\Parser\DotSeparatedActionNameParserStrategyStub;

class AffirmativePermissionCheckerStrategyTest extends TestCase
{
    public function testActionCannotBePerformedDueToMissingPermission(): void
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

    #[Group('MachineDependingTest')]
    public function testCheckingInTenThousandElementsPermissionCollectionMustRemainUnderSixMilliseconds(): void
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

        self::assertLessThan(6, $executionTime);
    }

    public function testActionCannotBePerformedDueToExplicitDeny(): void
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

    public function testActionCanBePerformedDueToAffirmativeAllowing(): void
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

    public function testActionCanBePerformedDuToAffirmativeAllowingOnParentAction(): void
    {
        $parser = new DotSeparatedActionNameParserStrategyStub();
        $permissions = [
            new Permission(new Action('root', $parser), true),
            new Permission(new Action('root.child', $parser), false),
        ];

        $strategy = new AffirmativePermissionCheckerStrategy();

        self::assertTrue(
            $strategy->canPerformAction(
                new Action('root.child', $parser),
                new PermissionsCollection($permissions)
            )
        );
    }
}