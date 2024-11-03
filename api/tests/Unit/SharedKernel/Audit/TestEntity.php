<?php

namespace App\Tests\Unit\SharedKernel\Audit;

use App\Domain\Common\Entity\EntityBase;
use Doctrine\ORM\Mapping as ORM;
use App\SharedKernel\Audit\AuditableTrait;

/**
 * Test entity that uses AuditableTrait for testing purposes.
 *
 * @ORM\Entity
 * @ORM\Table(name="test_entity")
 */
class TestEntity extends EntityBase
{
    use AuditableTrait;

    #[ORM\Column(type: "string")]
    private string $name;

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}