<?php

namespace App\Domain\UserManagement\User\Entity;

use App\Domain\Common\Entity\EntityBase;
use App\Domain\UserManagement\ValueObject\Password;
use App\Domain\UserManagement\Auth\ValueObject\Role;
use App\Domain\UserManagement\Entity\Session;
use App\Domain\UserManagement\Event\PasswordChanged;
use App\Domain\UserManagement\User\Event\UserRegistered;
use App\Domain\UserManagement\Auth\Event\UserRoleChanged;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * User
 *
 * Represents a system user, managing core attributes, role assignments, session handling, and event emission for changes in user state.
 *
 * **Attributes**
 *
 * - **Identity**: Uniquely identified by username and email.
 * - **Role & Permissions**: Users are assigned roles, such as `ROLE_USER` or `ROLE_ADMIN`, which determine their access scope.
 * - **Status & Activity**: Tracks active status, last login time, and active session count.
 * - **Sessions**: Manages multiple sessions, each tied to a device.
 *
 * **Event Emission**
 *
 * The `User` class emits events for significant lifecycle changes:
 * 
 * - **UserRegistered**: Triggered upon user registration.
 * - **UserRoleChanged**: Triggered when the user's role is updated.
 * - **PasswordChanged**: Triggered when the user changes their password.
 * 
 * Events are queued via the `EventRaiserTrait` and dispatched by services.
 *
 * **Usage Workflow**
 * 
 * - **Registration**: The `register` method registers a new user and emits `UserRegistered`.
 * - **Login Tracking**: On login, updates the `lastLogin` timestamp and session management.
 * - **Role Management**: Allows role updates with `updatePermissionsBasedOnRole`, emitting `UserRoleChanged`.
 *
 * **See Also**
 * 
 * @see UserService for user lifecycle management.
 * @see UserRegistered event class for registration handling.
 * @see UserRoleChanged event class for role change actions.
 *
 * @ORM\Entity()
 * @ORM\Table(name="users")
 */
#[ORM\Entity()]
#[ORM\Table(name:'users')]
class User extends EntityBase
{
    /**
     * @var string The username of the user.
     * 
     * @ORM\Column(type="string", unique=true, length=50)
     */
    #[ORM\Column(type:'string', unique:true, length:50)]
    private string $username;

    /**
     * @var int Number of active sessions the user currently has.
     *
     * @ORM\Column(type="integer")
     */
    #[ORM\Column(type: "integer")]
    private int $activeSessionsCount = 0;

    /**
     * @var string The email address of the user.
     * 
     * @ORM\Column(type="string", unique=true)
     */
    #[ORM\Column(type:'string', unique:true)]
    private string $email;

    /**
     * @var Password Hashed password for the user.
     * 
     * @ORM\Embedded(class="App\Domain\UserManagement\ValueObject\Password")
     */
    #[ORM\Embedded(class:'App\Domain\UserManagement\ValueObject\Password')]
    private Password $passwordHash;

    /**
     * @var Role The user's role in the system.
     * 
     * @ORM\Embedded(class="App\Domain\UserManagement\ValueObject\Role")
     */
    #[ORM\Embedded(class:'App\Domain\UserManagement\ValueObject\Role')]
    private Role $role;

    /**
     * @var bool Indicates if the user is active.
     * 
     * @ORM\Column(type="boolean")
     */
    #[ORM\Column(type:'boolean')]
    private bool $isActive;

    /**
     * @var array User permissions, derived from role.
     * 
     * @ORM\Column(type="json", nullable=true)
     */
    #[ORM\Column(type:'json', nullable: true)]
    private array $permissions;

    /**
     * @var ?\DateTimeInterface|null Last login time.
     * 
     * @ORM\Column(type="datetime", nullable=true)
     */
    #[ORM\Column(type:'datetime', nullable: true)]
    private ?\DateTimeInterface $lastLogin = null;

    /**
     * @var Collection|Session[] Collection of sessions for this user.
     * 
     * @ORM\OneToMany(targetEntity="App\Domain\UserManagement\Entity\Session", mappedBy="user", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    #[ORM\OneToMany(targetEntity: 'App\Domain\UserManagement\Entity\Session', mappedBy: 'user', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $sessions;

    public function __construct(string $username, string $email, Password $passwordHash, Role $role)
    {
        parent::__construct();
        $this->username = $username;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->role = $role;
        $this->isActive = true;
        $this->sessions = new ArrayCollection();
    }

    public function addSession(Session $session): void
    {
        if (!$this->sessions->contains($session)) {
            $this->sessions->add($session);
        }
    }

    public function removeSession(Session $session): void
    {
        $this->sessions->removeElement($session);
    }

    public function closeAllSessions(): void
    {
        $this->sessions->clear();
    }

    public function getSessions(): Collection
    {
        return $this->sessions;
    }

    /**
     * Gets the username.
     * 
     * @return string The username.
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Sets the username.
     * 
     * @param string $username The new username.
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * Gets the email address.
     * 
     * @return string The email address.
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Sets the email address.
     * 
     * @param string $email The new email address.
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * Gets the hashed password.
     * 
     * @return Password The password object.
     */
    public function getPasswordHash(): Password
    {
        return $this->passwordHash;
    }

    /**
     * Sets the password hash.
     * 
     * @param Password $passwordHash The password object with the new hash.
     */
    public function setPasswordHash(Password $passwordHash): void
    {
        $this->passwordHash = $passwordHash;
    }

    /**
     * Gets the role.
     * 
     * @return Role The role object.
     */
    public function getRole(): Role
    {
        return $this->role;
    }

    /**
     * Sets the role.
     * 
     * @param Role $role The new role.
     */
    public function setRole(Role $newRole): void
    {
        if (!$this->role->isEqual($newRole)) {
            $previousRole = $this->role;
            $this->role = $newRole;

            // Raise the UserRoleChanged event
            $this->raiseEvent(new UserRoleChanged($this->getId(), $newRole, $previousRole));
        }
    }

    /**
     * Checks if the user is active.
     * 
     * @return bool True if active, false otherwise.
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * Sets the active status of the user.
     * 
     * @param bool $isActive The new active status.
     */
    public function setActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    /**
     * Gets the last login time.
     * 
     * @return ?\DateTimeInterface|null The last login time or null if not set.
     */
    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->lastLogin;
    }

    /**
     * Sets the last login time.
     * 
     * @param ?\DateTimeInterface|null $lastLogin The new last login time.
     */
    public function setLastLogin(?\DateTimeInterface $lastLogin): void
    {
        $this->lastLogin = $lastLogin;
    }

    /**
     * Register a new user and raise the UserRegistered event.
     * 
     * @return void
     */
    public function register(): void
    {
        // Emit the UserRegistered event when a new user registers
        $this->raiseEvent(new UserRegistered($this->getId(), $this->getUsername(), $this->getEmail()));
    }

     
    /**
     * Updates the user's permissions based on their role.
     *
     * @param Role $newRole The new role assigned to the user.
     * @return void
     */
    public function updatePermissionsBasedOnRole(Role $newRole): void
    {
        if ($newRole->isAdmin()) {
            $this->permissions = [
                'manage_teacher_school_users', 
                'edit_school_content', 
                'view_school_reports', 
                'access_all_school_sections'
            ];
        } elseif ($newRole->isTeacher()) {
            $this->permissions = [
                'view_groups_reports', 
                'request_groups_changes'
            ];
        }  elseif ($newRole->isPrincipal()) {
            $this->permissions = [
                'view_school_reports', 
                'manage_school_users', 
                'access_principal_school_dashboard',
                'request_changes',
                'block_changes'
        ];
        } elseif ($newRole->isSuperAdmin()) {
            $this->permissions = [
                'view_all_reports', 
                'manage_all_users', 
                'access_principal_dashboard',
        ];
        }else {
            // Default permissions for users with other roles or no specific permissions
            $this->permissions = ['view_content'];
        }

        // Raise event if permissions were changed
        $this->raiseEvent(new UserRoleChanged($this->getId(), $newRole, $this->role));
        
        // Update the role to the new one
        $this->role = $newRole;
    }

     /**
     * Returns the user's current permissions.
     *
     * @return array The list of permissions for this user.
     */
    public function getPermissions(): array
    {
        return $this->permissions;
    }

    /**
     * Changes the user's password and raises the PasswordChanged event.
     *
     * @param Password $newPassword The new password value object.
     * @return void
     */
    public function changePassword(Password $newPassword): void
    {
        $this->passwordHash = $newPassword;
        $this->raiseEvent(new PasswordChanged($this->getId(), new DateTimeImmutable()));
    }

    /**
     * Updates the active sessions count by counting only non-expired sessions.
     *
     * @return void
     */
    public function updateActiveSessionsCount(): void
    {
        $this->activeSessionsCount = $this->sessions->filter(fn(Session $session) => !$session->isExpired())->count();
    }

    /**
     * Gets the active sessions count for the user.
     *
     * @return int The count of active sessions.
     */
    public function getActiveSessionsCount(): int
    {
        return $this->activeSessionsCount;
    }
}
