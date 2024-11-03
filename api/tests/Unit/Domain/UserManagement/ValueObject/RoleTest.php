<?php

namespace App\Tests\Domain\UserManagement\ValueObject;

use App\Domain\UserManagement\ValueObject\Role;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for the Role value object.
 */
class RoleTest extends TestCase
{
    /**
     * Tests the successful creation of a Role with valid roles.
     * 
     * @dataProvider validRolesProvider
     *
     * @param string $roleName The valid role name.
     */
    public function testValidRoleCreation(string $roleName): void
    {
        $role = new Role($roleName);
        $this->assertSame($roleName, $role->getRole());
    }

    /**
     * Data provider for valid roles.
     *
     * @return array<string[]>
     */
    public function validRolesProvider(): array
    {
        return [
            ['ROLE_USER'],
            ['ROLE_TEACHER'],
            ['ROLE_THIRD_LEVEL_TEACHER'],
            ['ROLE_SCHOOL_ADMIN'],
            ['ROLE_PRINCIPAL']
        ];
    }

    /**
     * Tests that an exception is thrown for an invalid role.
     */
    public function testInvalidRoleCreation(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid role: INVALID_ROLE");

        new Role('INVALID_ROLE');
    }

    /**
     * Tests that the isEqual method correctly compares two roles.
     */
    public function testIsEqual(): void
    {
        $role1 = new Role('ROLE_USER');
        $role2 = new Role('ROLE_USER');
        $role3 = new Role('ROLE_TEACHER');

        $this->assertTrue($role1->isEqual($role2));
        $this->assertFalse($role1->isEqual($role3));
    }

    /**
     * Tests that the isAdmin method correctly identifies admin roles.
     */
    public function testIsAdmin(): void
    {
        $adminRole = new Role('ROLE_SCHOOL_ADMIN');
        $nonAdminRole = new Role('ROLE_USER');

        $this->assertTrue($adminRole->isAdmin());
        $this->assertFalse($nonAdminRole->isAdmin());
    }

    /**
     * Tests that the isTeacher method correctly identifies teacher roles.
     */
    public function testIsTeacher(): void
    {
        $teacherRole = new Role('ROLE_TEACHER');
        $nonTeacherRole = new Role('ROLE_USER');

        $this->assertTrue($teacherRole->isTeacher());
        $this->assertFalse($nonTeacherRole->isTeacher());
    }

    /**
     * Tests that the isThirdLevelTeacher method correctly identifies third-level teacher roles.
     */
    public function testIsThirdLevelTeacher(): void
    {
        $thirdLevelTeacherRole = new Role('ROLE_THIRD_LEVEL_TEACHER');
        $nonThirdLevelTeacherRole = new Role('ROLE_USER');

        $this->assertTrue($thirdLevelTeacherRole->isThirdLevelTeacher());
        $this->assertFalse($nonThirdLevelTeacherRole->isThirdLevelTeacher());
    }

    /**
     * Tests that the isPrincipal method correctly identifies principal roles.
     */
    public function testIsPrincipal(): void
    {
        $principalRole = new Role('ROLE_PRINCIPAL');
        $nonPrincipalRole = new Role('ROLE_USER');

        $this->assertTrue($principalRole->isPrincipal());
        $this->assertFalse($nonPrincipalRole->isPrincipal());
    }
}
