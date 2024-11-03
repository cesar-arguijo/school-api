<?php

namespace App\Domain\UserManagement\Event;

use App\Domain\UserManagement\ValueObject\Role;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

/**
 * Event UserRoleChanged
 *
 * Triggered when a user's role changes in the system.
 *
 * Attributes:
 * - userId: Unique identifier of the user.
 * - newRole: The new role assigned to the user.
 * - previousRole: The user's previous role, useful for auditing.
 * - timestamp: Date and time the role change occurred.
 *
 * @package App\Domain\UserManagement\Event
 */
class UserRoleChanged
{
    private Uuid $userId;
    private Role $newRole;
    private Role $previousRole;
    private DateTimeImmutable $timestamp;

    /**
     * Constructs a UserRoleChanged event.
     *
     * @param Uuid $userId Unique identifier of the user whose role has changed.
     * @param Role $newRole The new role assigned to the user.
     * @param Role $previousRole The user's previous role.
     */
    public function __construct(Uuid $userId, Role $newRole, Role $previousRole)
    {
        $this->userId = $userId;
        $this->newRole = $newRole;
        $this->previousRole = $previousRole;
        $this->timestamp = new DateTimeImmutable();
    }

    /**
     * Gets the user ID.
     *
     * @return Uuid The user's unique identifier.
     */
    public function getUserId(): Uuid
    {
        return $this->userId;
    }

    /**
     * Gets the new role assigned to the user.
     *
     * @return Role The new role.
     */
    public function getNewRole(): Role
    {
        return $this->newRole;
    }

    /**
     * Gets the previous role of the user.
     *
     * @return Role The previous role.
     */
    public function getPreviousRole(): Role
    {
        return $this->previousRole;
    }

    /**
     * Gets the timestamp of the role change.
     *
     * @return DateTimeImmutable The timestamp when the role changed.
     */
    public function getTimestamp(): DateTimeImmutable
    {
        return $this->timestamp;
    }
}
