<?php

namespace Szopen\SimpleAccessControl\Domain\Parser;

class DotSeparatedActionNameParserStrategy implements ActionNameParserStrategy
{
    public const DEFAULT_SEPARATOR = ".";

    public function parse(string $name): array
    {
        return explode(self::DEFAULT_SEPARATOR, $name);
    }

    public function isSeparator(string $separator): bool
    {
        return trim($separator) === self::DEFAULT_SEPARATOR;
    }
}
