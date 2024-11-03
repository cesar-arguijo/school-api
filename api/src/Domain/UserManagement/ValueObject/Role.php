<?php

namespace App\Domain\UserManagement\ValueObject;

use Doctrine\ORM\Mapping as ORM;

/**
 * Value Object to define user roles.
 * @ORM\Embeddable
 */
#[ORM\Embeddable]
class Role
{
    /**
     * List of allowed roles in the system.
     * 
     * @var string[]
     */
    private const ALLOWED_ROLES = [
        'ROLE_USER', 
        'ROLE_TEACHER', 
        'ROLE_SCHOOL_ADMIN', 
        'ROLE_PRINCIPAL',
        'ROLE_SUPER_ADMIN'
    ];
    
    /**
     * The role of the user.
     * 
     * @var string
     * @ORM\Column(type="string")
     */
    #[ORM\Column(type: "string")]
    private string $role;

    /**
     * Initializes the Role object and validates it.
     * 
     * @param string $role The role to assign to the user.
     * @throws \InvalidArgumentException if the role is invalid.
     */
    public function __construct(string $role)
    {
        if (!in_array($role, self::ALLOWED_ROLES, true)) {
            throw new \InvalidArgumentException("Invalid role: $role");
        }
        $this->role = $role;
    }

    /**
     * Returns the role of the user.
     * 
     * @return string The user's role.
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * Checks if this role is equal to another role.
     * 
     * @param Role $role The role to compare with.
     * @return bool True if roles are equal, false otherwise.
     */
    public function isEqual(Role $role): bool
    {
        return $this->role === $role->getRole();
    }

    /**
     * Checks if the role is 'ROLE_ADMIN'.
     * 
     * @return bool True if the role is admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'ROLE_SCHOOL_ADMIN';
    }

    /**
     * Checks if the role is 'ROLE_TEACHER'.
     * 
     * @return bool True if the role is teacher.
     */
    public function isTeacher(): bool
    {
        return $this->role === 'ROLE_TEACHER';
    }

    /**
     * Checks if the role is 'ROLE_THIRD_LEVEL_TEACHER'.
     * 
     * @return bool True if the role is third-level teacher.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'ROLE_SUPER_ADMIN';
    }

    /**
     * Checks if the role is 'ROLE_PRINCIPAL'.
     * 
     * @return bool True if the role is principal.
     */
    public function isPrincipal(): bool
    {
        return $this->role === 'ROLE_PRINCIPAL';
    }
}
