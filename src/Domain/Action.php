<?php

declare(strict_types=1);

namespace Szopen\SimpleAccessControl\Domain;

use Szopen\SimpleAccessControl\Domain\Parser\ActionNameParserStrategy;
use Szopen\SimpleAccessControl\Domain\Parser\NullActionNameParserStrategy;
use Webmozart\Assert\Assert;

class Action
{
    public function __construct(
        private string $nameIdentifier,
        private ?ActionNameParserStrategy $nameParserStrategy = null
    ) {
        if (null === $this->nameParserStrategy) {
            $this->nameParserStrategy = new NullActionNameParserStrategy();
        }

        Assert::notEmpty($nameIdentifier, "Name identifier must not be empty");
    }

    public function equal(Action $action): bool
    {
        return $this->nameIdentifier === $action->nameIdentifier;
    }

    /** @psalm-suppress PossiblyNullReference */
    public function isParentOf(Action $action): bool
    {
        $parentStructure = $this->nameParserStrategy->parse($this->nameIdentifier);
        $childStructure = $this->nameParserStrategy->parse($action->nameIdentifier);

        $lengthOfParentStructure = count($parentStructure);
        $lengthOfChildStructure = count($childStructure);

        return count(
            array_intersect_assoc(
                $parentStructure,
                $childStructure
            )
        ) === $lengthOfParentStructure
            && $lengthOfParentStructure < $lengthOfChildStructure;
    }
}
