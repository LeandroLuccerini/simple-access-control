<?php

namespace Test\Szopen\SimpleAccessControl\Domain;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Szopen\SimpleAccessControl\Domain\Action;
use Szopen\SimpleAccessControl\Domain\Parser\ActionNameParserStrategy;
use Szopen\SimpleAccessControl\Domain\Parser\DotSeparatedActionNameParserStrategy;

class ActionTest extends TestCase
{

    public function testInstantiationMustFailDueToEmptyName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Name identifier must not be empty");

        new Action("");
    }

    public function testEqualMustReturnFalseDueToDifferentNameIdentifier(): void
    {
        $action1 = new Action('action.1');
        $action2 = new Action('action.2');

        self::assertFalse($action1->equal($action2));
    }

    public function testEqualMustReturnTrueDueToSameNameIdentifier(): void
    {
        $action1 = new Action('action.1');
        $action2 = new Action('action.1');

        self::assertTrue($action1->equal($action2));
    }

    public function testIsParentOfMustReturnFalseDueToDifferentRoots(): void
    {
        $parent = new Action('product');
        $child = new Action('different_product');

        self::assertFalse($parent->isParentOf($child));
    }

    #[DataProvider("actionFalseHierarchicalStructureDataProvider")]
    public function testIsParentOfMustReturnFalseDueToDifferentTreePath(string $parent, string $child): void
    {
        $parent = new Action($parent);
        $child = new Action($child);

        self::assertFalse($parent->isParentOf($child));
    }

    #[DataProvider("actionTrueHierarchicalStructureDataProvider")]
    public function testIsParentOfMustReturnTrue(string $parent, string $child): void
    {
        $parent = new Action($parent, new DotSeparatedActionNameParserStrategy());
        $child = new Action($child, new DotSeparatedActionNameParserStrategy());

        self::assertTrue($parent->isParentOf($child));
    }

    public static function actionFalseHierarchicalStructureDataProvider(): array
    {
        return [
            ['product.test', 'product.view'],
            ['product.detail', 'product.view.detail'],
            ['product', 'view.product'],
            ['product.test', 'product.view.test'],
            ['product.test', '.test'],
            ['product.detail', 'product.detail'],
        ];
    }

    public static function actionTrueHierarchicalStructureDataProvider(): array
    {
        return [
            ['product', 'product.view'],
            ['product', 'product.detail.view'],
            ['product.detail', 'product.detail.edit'],
        ];
    }
}