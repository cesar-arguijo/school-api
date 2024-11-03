<?php

namespace App\Domain\UserManagement\ValueObject;

use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

/**
 * Value Object to handle password hashing and validation.
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
     * Initializes the Password object and hashes the password.
     * 
     * @param string $plainPassword The plain text password.
     * @throws InvalidArgumentException If the password doesn't meet security requirements.
     */
    public function __construct(string $plainPassword)
    {
        $this->assertPasswordStrength($plainPassword);
        $this->hashedPassword = $this->hashPassword($plainPassword);
    }

    /**
     * Validates the password strength.
     * 
     * @param string $plainPassword The plain text password.
     * @throws InvalidArgumentException If the password doesn't meet security requirements.
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
     * Hashes a plain text password.
     * 
     * @param string $plainPassword The plain text password.
     * @return string The hashed password.
     */
    private function hashPassword(string $plainPassword): string
    {
        return password_hash($plainPassword, PASSWORD_ARGON2ID);
    }

    /**
     * Verifies if a plain text password matches the hashed password.
     * 
     * @param string $plainPassword The plain text password.
     * @return bool True if passwords match, false otherwise.
     */
    public function verify(string $plainPassword): bool
    {
        return password_verify($plainPassword, $this->hashedPassword);
    }

    /**
     * Returns the hashed password.
     * 
     * @return string The hashed password.
     */
    public function getHashedPassword(): string
    {
        return $this->hashedPassword;
    }
}
