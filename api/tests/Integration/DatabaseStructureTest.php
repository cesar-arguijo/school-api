<?php

namespace App\Tests\Integration;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DatabaseStructureTest extends KernelTestCase
{
    /** @var Connection */
    private Connection $connection;

    protected function setUp(): void
    {
        // Arranca el kernel de Symfony para obtener el contenedor de servicios
        self::bootKernel();
        $container = static::getContainer();
        $this->connection = $container->get(EntityManagerInterface::class)->getConnection();
    }

    public function testDatabaseTablesExist(): void
    {
        // Define las tablas esperadas
        $expectedTables = [
            'audit_trail',
            'users',
            'sessions',
            'devices'
            // Añade aquí el resto de tus tablas necesarias
        ];

        foreach ($expectedTables as $table) {
            $this->assertTrue($this->tableExists($table), "La tabla {$table} no está presente en la base de datos.");
        }
    }

    private function tableExists(string $tableName): bool
    {
        // Verifica si la tabla existe en la base de datos
        $schemaManager = $this->connection->createSchemaManager();
        return in_array($tableName, $schemaManager->listTableNames(), true);
    }
}
