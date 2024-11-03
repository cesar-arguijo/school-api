<?php

namespace App\Tests\Unit\SharedKernel\Trait;

use PHPUnit\Framework\TestCase;
use App\SharedKernel\Trait\VersionableTrait;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use ReflectionClass;

/**
 * Unit test for the VersionableTrait with validation checks.
 */
class VersionableTraitTest extends TestCase
{
    private $versionable;
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        // Use a dummy class to test VersionableTrait
        $this->versionable = $this->getObjectForTrait(VersionableTrait::class);
        
        // Initialize the Symfony validator
        $this->validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();
    }

    /**
     * Tests the default value of the version property.
     *
     * @return void
     */
    public function testDefaultVersion(): void
    {
        $this->assertEquals(1, $this->versionable->getVersion());
    }

    /**
     * Tests that the version property fails validation when set to zero (violating Positive constraint).
     *
     * @return void
     */
    public function testVersionPositiveConstraint(): void
    {
        // Use reflection to set version to 0, violating the Positive constraint
        $reflection = new ReflectionClass($this->versionable);
        $property = $reflection->getProperty('version');
        $property->setAccessible(true);
        $property->setValue($this->versionable, 0);

        // Validate and assert that there's a Positive violation
        $violations = $this->validator->validate($this->versionable);
        $this->assertCount(1, $violations);
        $this->assertEquals('This value should be positive.', $violations[0]->getMessage());
    }
}