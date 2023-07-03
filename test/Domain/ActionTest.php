<?php

namespace Test\Szopen\SimpleAccessControl\Domain;

use PHPUnit\Framework\TestCase;
use Szopen\SimpleAccessControl\Domain\Action;

class ActionTest extends TestCase
{
    public function testEqualMustReturnFalseDueToDifferentNameIdentifier()
    {
        $action1 = new Action('action.1');
        $action2 = new Action('action.2');

        self::assertFalse($action1->equal($action2));
    }

    public function testEqualMustReturnFalseTrueToSameNameIdentifier()
    {
        $action1 = new Action('action.1');
        $action2 = new Action('action.1');

        self::assertTrue($action1->equal($action2));
    }
}