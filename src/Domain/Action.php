<?php

declare(strict_types=1);

namespace Szopen\SimpleAccessControl\Domain;

use Webmozart\Assert\Assert;

class Action
{
    public function __construct(private string $nameIdentifier)
    {
        Assert::notEmpty($nameIdentifier);
    }

    public function equal(Action $action): bool
    {
        return $this->nameIdentifier === $action->nameIdentifier;
    }
}
