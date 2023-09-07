<?php

namespace Test\Szopen\SimpleAccessControl\Domain;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Szopen\SimpleAccessControl\Domain\Action;
use Szopen\SimpleAccessControl\Domain\Permission;
use Test\Szopen\SimpleAccessControl\Domain\Parser\DotSeparatedActionNameParserStrategyStub;

class PermissionTest extends TestCase
{
    public function testIsAppliedToMustReturnFalseDueToDifferentActions(): void
    {
        $action = new Action('action.to.assign');
        $permission = new Permission($action, true);

        $actionToCheck = new Action('action.to.check');

        self::assertFalse($permission->isAppliedTo($actionToCheck));
    }

    public function testIsAppliedToMustReturnTrueDueToTheSameAction(): void
    {
        $action = new Action('action.test');
        $permission = new Permission($action, true);

        $actionToCheck = new Action('action.test');

        self::assertTrue($permission->isAppliedTo($actionToCheck));
    }

    public function testIsAppliedToMustReturnTrueDueToTheSameActionUsingHierarchy(): void
    {
        $parser = new DotSeparatedActionNameParserStrategyStub();

        $action = new Action('action.test', $parser);
        $permission = new Permission($action, true);

        $actionToCheck = new Action('action.test', $parser);

        self::assertTrue($permission->isAppliedTo($actionToCheck));
    }

    #[DataProvider('relatedActionsDataProvider')]
    public function testIsAppliedToMustReturnTrueDueToRelatedActionChecked(string $parent, string $child): void
    {
        $parser = new DotSeparatedActionNameParserStrategyStub();

        $action = new Action($parent, $parser);
        $permission = new Permission($action, true);

        $actionToCheck = new Action($child, $parser);

        self::assertTrue($permission->isAppliedTo($actionToCheck));
    }

    #[DataProvider('notRelatedActionsDataProvider')]
    public function testIsAppliedToMustReturnFalseDueToNotRelatedActions(string $action, string $actionToCheck): void
    {
        $parser = new DotSeparatedActionNameParserStrategyStub();

        $action = new Action($action, $parser);
        $permission = new Permission($action, true);

        $actionToCheck = new Action($actionToCheck, $parser);

        self::assertFalse($permission->isAppliedTo($actionToCheck));
    }

    public static function relatedActionsDataProvider(): array
    {
        return [
            ['action', 'action.test'],
            ['action', 'action.sub_action.test'],
            ['action.sub_action', 'action.sub_action.test'],
        ];
    }

    public static function notRelatedActionsDataProvider(): array
    {
        return [
            ['action', 'test'],
            ['action', 'sub_action.action'],
            ['action_sub_action', 'sub_action.action'],
            ['action_sub_action', 'action.sub_action'],
        ];
    }

}