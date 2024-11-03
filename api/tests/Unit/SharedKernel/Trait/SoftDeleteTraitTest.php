<?php

namespace App\Tests\Unit\SharedKernel\Trait;

use PHPUnit\Framework\TestCase;
use App\SharedKernel\Trait\SoftDeleteTrait;

/**
 * Tests for the SoftDeleteTrait.
 *
 * Verifies the functionality of marking an entity as deleted or restored.
 */
class SoftDeleteTraitTest extends TestCase
{
    /**
     * @var object A class instance using the SoftDeleteTrait.
     */
    private object $softDeletable;

    /**
     * Sets up the test environment with an anonymous class that uses SoftDeleteTrait.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->softDeletable = new class {
            use SoftDeleteTrait;
        };
    }

    /**
     * Tests the delete and restore functionality of the SoftDeleteTrait.
     *
     * @return void
     */
    public function testSoftDelete(): void
    {
        $this->softDeletable->delete();
        $this->assertTrue($this->softDeletable->isDeleted());

        $this->softDeletable->restore();
        $this->assertFalse($this->softDeletable->isDeleted());
    }
}
