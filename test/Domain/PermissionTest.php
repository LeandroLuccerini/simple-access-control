<?php

namespace Test\Szopen\SimpleAccessControl\Domain;

use PHPUnit\Framework\TestCase;
use Szopen\SimpleAccessControl\Domain\Action;
use Szopen\SimpleAccessControl\Domain\Permission;

class PermissionTest extends TestCase
{
    public function testIsAppliedToMustReturnFalseDueToDifferentActions()
    {
        $action = new Action('action.to.assign');
        $permission = new Permission($action, true);

        $actionToCheck = new Action('action.to.check');

        self::assertFalse($permission->isAppliedTo($actionToCheck));
    }

    public function testIsAppliedToMustReturnTrueDueToTheSameAction()
    {
        $action = new Action('action.test');
        $permission = new Permission($action, true);

        $actionToCheck = new Action('action.test');

        self::assertTrue($permission->isAppliedTo($actionToCheck));
    }

}