<?php

namespace Test\Szopen\SimpleAccessControl\Domain\Parser;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Szopen\SimpleAccessControl\Domain\Parser\DotSeparatedActionNameParserStrategy;
use Szopen\SimpleAccessControl\Domain\Parser\NullActionNameParserStrategy;

class DotSeparatedActionNameParserStrategyTest extends TestCase
{

    public static function dataProvider(): array
    {
        return [
            ['product', 1],
            ['product.detail', 2],
            ['product.detail.view', 3],
            ['product_detail_view', 1],
            ['product.detail_view', 2],
            ['.', 2],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testParseMustParseCorrectlyADotSeparatedName(string $name, int $elementCount): void
    {
        $parser = new DotSeparatedActionNameParserStrategy();
        $parsed = $parser->parse($name);

        self::assertCount($elementCount, $parsed);
        self::assertEquals($name, implode(".", $parsed));
    }
}