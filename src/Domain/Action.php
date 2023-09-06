<?php

declare(strict_types=1);

namespace Szopen\SimpleAccessControl\Domain;

use Webmozart\Assert\Assert;

class Action
{
    private const DEFAULT_SEPARATOR = ".";

    public function __construct(
        private string $nameIdentifier,
        private string $nameStructureSeparator = self::DEFAULT_SEPARATOR
    ) {
        Assert::notEmpty($nameIdentifier, "Name identifier must not be empty");
        Assert::notEmpty($nameStructureSeparator, "Structure separator must not be empty");
    }

    public function equal(Action $action): bool
    {
        return $this->nameIdentifier === $action->nameIdentifier;
    }

    public function isParentOf(Action $action): bool
    {
        $parentStructure = explode($this->nameStructureSeparator ?: self::DEFAULT_SEPARATOR, $this->nameIdentifier);
        $childStructure = explode($this->nameStructureSeparator ?: self::DEFAULT_SEPARATOR, $action->nameIdentifier);

        $lengthOfParentStructure = count($parentStructure);
        $lengthOfChildStructure = count($childStructure);

        return count(array_intersect_assoc($parentStructure, $childStructure)) === $lengthOfParentStructure
            && $lengthOfParentStructure < $lengthOfChildStructure;
    }
}
