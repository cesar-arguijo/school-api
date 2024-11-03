<?php

namespace App\SharedKernel\Audit;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use DateTime;

/**
 * Entity representing an audit trail for tracking changes in entities.
 *
 * Stores a record of changes made to other entities, including the property changed,
 * its previous value, and the new value.
 *
 * @ORM\Entity
 * @ORM\Table(name="audit_trail")
 */
#[ORM\Entity]
#[ORM\Table(name: "audit_trail")]
class AuditTrail
{
    /**
     * The unique identifier for the audit trail entry.
     *
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue]
    private int $id;

    /**
     * The ID of the audited entity.
     *
     * @var Uuid
     * @ORM\Column(type="uuid")
     */
    #[ORM\Column(type: "uuid")]
    private Uuid $entityId;

    /**
     * The ID of the user who performed the action.
     *
     * @var Uuid
     * @ORM\Column(type="uuid")
     */
    #[ORM\Column(type: "uuid")]
    private Uuid $performedBy;

    /**
     * The name of the property that was changed.
     *
     * @var string
     * @ORM\Column(type="string")
     */
    #[ORM\Column(type: "string")]
    private string $propertyName;

    /**
     * The action taken, such as "updated" or "deleted".
     *
     * @var string
     * @ORM\Column(type="string")
     */
    #[ORM\Column(type: "string")]
    private string $action;

    /**
     * The entity being audited.
     *
     * @var string
     * @ORM\Column(type="string")
     */
    #[ORM\Column(type: "string")]
    private string $entity;

    /**
     * The previous value of the property as JSON.
     *
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     */
    #[ORM\Column(type: "text", nullable: true)]
    private ?string $oldValue;

    /**
     * The new value of the property as JSON.
     *
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     */
    #[ORM\Column(type: "text", nullable: true)]
    private ?string $newValue;

    /**
     * The timestamp when the change occurred.
     *
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    #[ORM\Column(type: "datetime")]
    private DateTime $changedAt;

    /**
     * Constructor to initialize the audit entry.
     *
     * @param string $action The action performed on the entity.
     * @param string $entity The entity being audited.
     * @param Uuid $entityId The ID of the entity being audited.
     * @param Uuid $performedBy The ID of the user who performed the action.
     * @param string $propertyName The name of the property that was changed.
     * @param mixed $oldValue The previous value of the property.
     * @param mixed $newValue The new value of the property.
     */
    public function __construct(
        string $action,
        string $entity,
        Uuid $entityId,
        Uuid $performedBy,
        string $propertyName,
        $oldValue,
        $newValue
    ) {
        $this->entityId = $entityId;
        $this->performedBy = $performedBy;
        $this->entity = $entity;
        $this->propertyName = $propertyName;
        $this->oldValue = json_encode($oldValue);
        $this->newValue = json_encode($newValue);
        $this->changedAt = new DateTime();
        $this->action = $action;
    }

    /**
     * Gets the unique identifier of the audit trail entry.
     *
     * @return int The unique identifier.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Gets the ID of the audited entity.
     *
     * @return Uuid The ID of the audited entity.
     */
    public function getEntityId(): Uuid
    {
        return $this->entityId;
    }

    /**
     * Gets the ID of the user who performed the action.
     *
     * @return Uuid The ID of the user who performed the action.
     */
    public function getPerformedBy(): Uuid
    {
        return $this->performedBy;
    }

    /**
     * Gets the name of the property that was changed.
     *
     * @return string The name of the property.
     */
    public function getPropertyName(): string
    {
        return $this->propertyName;
    }

    /**
     * Gets the previous value of the property.
     *
     * @return string|null The previous value as JSON.
     */
    public function getOldValue(): ?string
    {
        return $this->oldValue;
    }

    /**
     * Gets the new value of the property.
     *
     * @return string|null The new value as JSON.
     */
    public function getNewValue(): ?string
    {
        return $this->newValue;
    }

    /**
     * Gets the timestamp when the change occurred.
     *
     * @return DateTime The timestamp of the change.
     */
    public function getChangedAt(): DateTime
    {
        return $this->changedAt;
    }

    /**
     * Gets the action performed.
     *
     * @return string The action name.
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * Gets the entity type.
     *
     * @return string The entity being audited.
     */
    public function getEntity(): string
    {
        return $this->entity;
    }
}
