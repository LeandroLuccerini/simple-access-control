<?php

namespace Szopen\SimpleAccessControl\Domain;

use Countable;
use Iterator;
use Webmozart\Assert\Assert;

/**
 * @template-implements Iterator<Permission>
 */
class PermissionsCollection implements Iterator, Countable
{
    private int $position = 0;

    /**
     * @psalm-param list<Permission> $permissions
     */
    public function __construct(private array $permissions)
    {
        if(!empty($permissions)) {
            Assert::allIsInstanceOf(
                $permissions,
                Permission::class,
                sprintf("All the elements must be of type %s", Permission::class)
            );
        }
    }


    public function current(): Permission
    {
        return $this->permissions[$this->position];
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return isset($this->permissions[$this->position]);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function count(): int
    {
        return count($this->permissions);
    }
}
