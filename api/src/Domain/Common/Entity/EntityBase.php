<?php

namespace App\Domain\Common\Entity;

use App\SharedKernel\Interface\IdentifiableInterface;
use App\SharedKernel\Interface\SoftDeletableInterface;
use App\SharedKernel\Interface\TimestampableInterface;
use App\SharedKernel\Interface\VersionableInterface;
use App\SharedKernel\Interface\EventRaiserInterface;
use App\SharedKernel\Audit\AuditableTrait;
use App\SharedKernel\Trait\SoftDeleteTrait;
use App\SharedKernel\Trait\TimestampTrait;
use App\SharedKernel\Trait\VersionableTrait;
use App\SharedKernel\Trait\EventRaiserTrait;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;

/**
 * Base entity class providing a unique identifier, versioning, timestamps, and soft delete functionality.
 *
 * Implements multiple interfaces to add flexibility and maintainability.
 */
#[ORM\MappedSuperclass]
abstract class EntityBase 
    implements IdentifiableInterface, SoftDeletableInterface, 
    TimestampableInterface, VersionableInterface, EventRaiserInterface
{
    use SoftDeleteTrait;
    use TimestampTrait;
    use VersionableTrait;
    use AuditableTrait;
    use EventRaiserTrait;

    /**
     * @var Uuid $id The unique identifier for the entity.
     */
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    private Uuid $id;

    /**
     * Initializes a new instance with a unique identifier.
     */
    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    /**
     * {@inheritDoc}
     */
    public function getId(): Uuid
    {
        return $this->id;
    }
}

