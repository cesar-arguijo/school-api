<?php

namespace App\Tests\Unit\SharedKernel\Trait;

use PHPUnit\Framework\TestCase;
use App\SharedKernel\Trait\TimestampTrait;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolation;
use ReflectionClass;

/**
 * Unit test for the TimestampTrait with validation checks.
 */
class TimestampTraitTest extends TestCase
{
    private $timestampable;
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        // Use a dummy class to test TimestampTrait
        $this->timestampable = $this->getObjectForTrait(TimestampTrait::class);

        // Initialize the Symfony validator
        $this->validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();
    }

    /**
     * Tests validation for a createdAt date in the future.
     */
    public function testCreatedAtFutureDateValidation(): void
    {
        // Set createdAt to a future date
        $reflection = new ReflectionClass($this->timestampable);
        $property = $reflection->getProperty('createdAt');
        $property->setAccessible(true);
        $property->setValue($this->timestampable, new \DateTime('+1 day'));

        // Validate and filter violations related to LessThanOrEqual
        $violations = $this->validator->validate($this->timestampable);
        $filteredViolations = array_filter(
            iterator_to_array($violations),
            fn (ConstraintViolation $violation) => $violation->getMessage() === "The creation date cannot be in the future."
        );

        $this->assertCount(1, $filteredViolations);
    }

    /**
     * Tests validation for an updatedAt date in the future.
     */
    public function testUpdatedAtFutureDateValidation(): void
    {
        // Set updatedAt to a future date
        $reflection = new ReflectionClass($this->timestampable);
        $property = $reflection->getProperty('updatedAt');
        $property->setAccessible(true);
        $property->setValue($this->timestampable, new \DateTime('+1 day'));

        // Validate and filter violations related to LessThanOrEqual
        $violations = $this->validator->validate($this->timestampable);
        $filteredViolations = array_filter(
            iterator_to_array($violations),
            fn (ConstraintViolation $violation) => $violation->getMessage() === "The update date cannot be in the future."
        );

        $this->assertCount(1, $filteredViolations);
    }

    /**
     * Test that setCreatedAtValue() sets the createdAt and updatedAt fields.
     */
    public function testSetCreatedAtValue(): void
    {
        // Llamamos al método directamente para simular el efecto del PrePersist.
        $this->timestampable->setCreatedAtValue();

        // Obtenemos los valores de createdAt y updatedAt.
        $createdAt = $this->timestampable->getCreatedAt();
        $updatedAt = $this->timestampable->getUpdatedAt();

        // Comprobamos que ambos campos no sean nulos.
        $this->assertNotNull($createdAt, 'The createdAt value should not be null after setCreatedAtValue is called.');
        $this->assertNotNull($updatedAt, 'The updatedAt value should not be null after setCreatedAtValue is called.');

        // Comprobamos que ambos campos son instancias de DateTime.
        $this->assertInstanceOf(\DateTime::class, $createdAt, 'The createdAt value should be an instance of DateTime.');
        $this->assertInstanceOf(\DateTime::class, $updatedAt, 'The updatedAt value should be an instance of DateTime.');

        // Comprobamos que ambos campos tengan valores de fecha y hora similares.
        $this->assertEquals($createdAt->format('Y-m-d H:i:s'), $updatedAt->format('Y-m-d H:i:s'), 'The createdAt and updatedAt values should be the same initially.');
    }

    /**
     * Test that setUpdatedAtValue() sets the updatedAt field to the current DateTime.
     */
    public function testSetUpdatedAtValue(): void
    {
        // Llamamos al método directamente.
        $this->timestampable->setUpdatedAtValue();

        // Obtenemos el valor de updatedAt.
        $updatedAt = $this->timestampable->getUpdatedAt();

        // Comprobamos que updatedAt no sea nulo.
        $this->assertNotNull($updatedAt, 'The updatedAt value should not be null after setUpdatedAtValue is called.');

        // Comprobamos que updatedAt sea una instancia de DateTime.
        $this->assertInstanceOf(\DateTime::class, $updatedAt, 'The updatedAt value should be an instance of DateTime.');

        // Comprobamos que la fecha y hora de updatedAt sea reciente (dentro de los últimos 2 segundos).
        $now = new \DateTime();
        $interval = $now->getTimestamp() - $updatedAt->getTimestamp();
        $this->assertLessThanOrEqual(2, $interval, 'The updatedAt value should be the current DateTime.');
    }
}