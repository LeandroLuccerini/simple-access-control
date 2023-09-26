<?php

namespace Test\Szopen\SimpleAccessControl\Domain\Parser;

use Szopen\SimpleAccessControl\Domain\Parser\ActionNameParserStrategy;

class DotSeparatedActionNameParserStrategyStub implements ActionNameParserStrategy
{

    public function parse(string $name): array
    {
        return explode(".", $name);
    }
}