<?php

declare(strict_types=1);

namespace Szopen\SimpleAccessControl\Domain\Parser;

interface ActionNameParserStrategy
{
    public function parse(string $name): array;
}
