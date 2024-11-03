<?php

namespace App\Tests\Domain\UserManagement\ValueObject;

use App\Domain\UserManagement\ValueObject\Password;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the Password value object.
 *
 * This class contains tests for the password hashing, validation, and verification
 * mechanisms in the Password value object.
 */
class PasswordTest extends TestCase
{
    /**
     * Tests that a password meeting all complexity requirements can be created successfully.
     *
     * @return void
     */
    public function testPasswordCreationWithValidPassword(): void
    {
        $password = new Password('Valid@123');
        $this->assertInstanceOf(Password::class, $password);
        $this->assertNotEmpty($password->getHashedPassword());
    }

    /**
     * Tests that an exception is thrown when a password is shorter than 8 characters.
     *
     * @return void
     */
    public function testPasswordTooShortThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Password must be at least 8 characters long.');
        new Password('Shor1!');
    }

    /**
     * Tests that an exception is thrown when a password does not contain an uppercase letter.
     *
     * @return void
     */
    public function testPasswordWithoutUppercaseThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Password must contain at least one uppercase letter.');
        new Password('lowercase1@');
    }

    /**
     * Tests that an exception is thrown when a password does not contain a lowercase letter.
     *
     * @return void
     */
    public function testPasswordWithoutLowercaseThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Password must contain at least one lowercase letter.');
        new Password('UPPERCASE1@');
    }

    /**
     * Tests that an exception is thrown when a password does not contain a digit.
     *
     * @return void
     */
    public function testPasswordWithoutDigitThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Password must contain at least one digit.');
        new Password('NoDigits@!');
    }

    /**
     * Tests that an exception is thrown when a password does not contain a special character.
     *
     * @return void
     */
    public function testPasswordWithoutSpecialCharacterThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Password must contain at least one special character.');
        new Password('NoSpecial1');
    }

    /**
     * Tests that the hashed password is not equal to the plain password directly.
     *
     * @return void
     */
    public function testHashedPasswordDoesNotEqualPlainPassword(): void
    {
        $plainPassword = 'Valid@123';
        $password = new Password($plainPassword);
        $this->assertNotEquals($plainPassword, $password->getHashedPassword());
    }

    /**
     * Tests that the verify method returns true for the correct password.
     *
     * @return void
     */
    public function testVerifyReturnsTrueForCorrectPassword(): void
    {
        $plainPassword = 'Valid@123';
        $password = new Password($plainPassword);
        $this->assertTrue($password->verify($plainPassword));
    }

    /**
     * Tests that the verify method returns false for an incorrect password.
     *
     * @return void
     */
    public function testVerifyReturnsFalseForIncorrectPassword(): void
    {
        $password = new Password('Valid@123');
        $this->assertFalse($password->verify('InvalidPassword!'));
    }
}
