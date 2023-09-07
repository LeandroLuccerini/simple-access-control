<?php

namespace Test\Szopen\SimpleAccessControl\Domain\Parser;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Szopen\SimpleAccessControl\Domain\Parser\NullActionNameParserStrategy;

class NullActionNameParserStrategyTest extends TestCase
{

    public static function dataProvider(): array
    {
        return [
            ['product.test'],
            ['product_view'],
            ['product/detail'],
            ['product.view.detail'],
            ['product'],
            ['viewProduct'],
            ['.test'],
            [''],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testParseMustReturnAlwaysAnArrayWithAnElement(string $name): void
    {
        $parser = new NullActionNameParserStrategy();
        $parsed = $parser->parse($name);
        self::assertCount(1, $parsed);
        self::assertEquals($name, current($parsed));
    }
}