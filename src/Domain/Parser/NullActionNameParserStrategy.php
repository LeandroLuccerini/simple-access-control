<?php

namespace Szopen\SimpleAccessControl\Domain\Parser;

class NullActionNameParserStrategy implements ActionNameParserStrategy
{
    public function parse(string $name): array
    {
        return [$name];
    }
}
