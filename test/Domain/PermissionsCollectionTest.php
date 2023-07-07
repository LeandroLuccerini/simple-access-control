<?php

namespace Test\Szopen\SimpleAccessControl\Domain;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Szopen\SimpleAccessControl\Domain\Action;
use Szopen\SimpleAccessControl\Domain\Permission;
use Szopen\SimpleAccessControl\Domain\PermissionsCollection;

class PermissionsCollectionTest extends TestCase
{
    public function testInstantiationMustFailDueToNotAllowedClassInArray(): void
    {
        self::expectException(InvalidArgumentException::class);

        new PermissionsCollection([
            new Permission(new Action('test.1'), true),
            new Action('test.2')
        ]);
    }

    public function testInstantiationAcceptsEmptyArray(): void
    {
        $collection = new PermissionsCollection([]);

        self::assertInstanceOf(PermissionsCollection::class, $collection);
        self::assertCount(0, $collection);
    }

    public function testMemoryUsageForTenThousandPermissionsMustBeUnderThreeMb(): void
    {
        $mem = memory_get_usage();
        $permissions = [];
        for ($i = 0; $i < 10_000; $i++ ){
            $permissions[] = new Permission(new Action('test.'.$i), true);
        }

        new PermissionsCollection($permissions);
        $memoryUsed = (memory_get_usage() - $mem)/1_000_000;

        self::assertLessThan(3, $memoryUsed);
    }
}