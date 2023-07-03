<?php

declare(strict_types=1);

namespace Szopen\SimpleAccessControl\Domain;

class Permission
{
    public function __construct(
        protected Action $action,
        protected bool $isAllowed
    ) {
    }

    public function isAppliedTo(Action $action): bool
    {
        return $this->action->equal($action);
    }

    public function isAllowed(): bool
    {
        return $this->isAllowed;
    }
}
