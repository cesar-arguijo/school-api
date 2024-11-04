<?php

namespace App\Domain\UserManagement\ValueObject;

use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

/**
 * Password
 *
 * Value Object that handles password creation, hashing, and validation.
 * Ensures passwords meet security standards before they are hashed and stored.
 * 
 * **Security Requirements**
 *
 * - Minimum 8 characters.
 * - At least one uppercase letter.
 * - At least one lowercase letter.
 * - At least one digit.
 * - At least one special character.
 *
 * **Usage Workflow**
 *
 * - `__construct()`: Validates the password strength and hashes it.
 * - `verify()`: Checks if a plain text password matches the stored hashed password.
 * - `getHashedPassword()`: Provides the hashed password for storage or comparison.
 * 
 * @ORM\Embeddable
 */
#[ORM\Embeddable]
class Password
{
    /**
     * The hashed password string.
     *
     * @var string
     * 
     * @ORM\Column(type="string")
     */
    #[ORM\Column(type: "string")]
    private string $hashedPassword;

    /**
     * Initializes the Password object by validating and hashing the password.
     * 
     * @param string $plainPassword The plain text password.
     * @throws InvalidArgumentException If the password does not meet security requirements.
     */
    public function __construct(string $plainPassword)
    {
        $this->assertPasswordStrength($plainPassword);
        $this->hashedPassword = $this->hashPassword($plainPassword);
    }

    /**
     * Ensures the password meets strength requirements.
     * 
     * @param string $plainPassword The plain text password.
     * @throws InvalidArgumentException If the password does not meet security requirements.
     */
    private function assertPasswordStrength(string $plainPassword): void
    {
        if (strlen($plainPassword) < 8) {
            throw new InvalidArgumentException('Password must be at least 8 characters long.');
        }

        if (!preg_match('/[A-Z]/', $plainPassword)) {
            throw new InvalidArgumentException('Password must contain at least one uppercase letter.');
        }

        if (!preg_match('/[a-z]/', $plainPassword)) {
            throw new InvalidArgumentException('Password must contain at least one lowercase letter.');
        }

        if (!preg_match('/\d/', $plainPassword)) {
            throw new InvalidArgumentException('Password must contain at least one digit.');
        }

        if (!preg_match('/[\W]/', $plainPassword)) {
            throw new InvalidArgumentException('Password must contain at least one special character.');
        }
    }

    /**
     * Hashes the provided plain text password.
     * 
     * @param string $plainPassword The plain text password.
     * @return string The hashed password string.
     */
    private function hashPassword(string $plainPassword): string
    {
        return password_hash($plainPassword, PASSWORD_ARGON2ID);
    }

    /**
     * Verifies if a plain text password matches the stored hashed password.
     * 
     * @param string $plainPassword The plain text password.
     * @return bool True if passwords match, false otherwise.
     */
    public function verify(string $plainPassword): bool
    {
        return password_verify($plainPassword, $this->hashedPassword);
    }

    /**
     * Returns the hashed password for storage or comparison.
     * 
     * @return string The hashed password string.
     */
    public function getHashedPassword(): string
    {
        return $this->hashedPassword;
    }
}
