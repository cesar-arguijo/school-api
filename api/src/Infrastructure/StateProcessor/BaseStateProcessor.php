<?php

namespace App\Infrastructure\StateProcessor;

use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Domain\Common\Entity\EntityBase;
use Doctrine\ORM\EntityManagerInterface;

/**
 * BaseStateProcessor handles the persistence and soft deletion logic
 * for entities in API Platform operations.
 *
 * This processor checks the type of HTTP operation (e.g., POST, PUT, DELETE)
 * to determine whether to persist a new or updated entity, or to soft-delete it.
 * It allows a centralized approach to managing entity states across the API.
 */
class BaseStateProcessor implements ProcessorInterface
{

     /**
     * The EntityManager for managing database operations.
     *
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * Constructs the BaseStateProcessor with the provided EntityManager.
     *
     * @param EntityManagerInterface $entityManager The EntityManager to handle database operations.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

     /**
     * Processes the given entity based on the operation type, handling persistence
     * and soft-deletion logic.
     *
     * @param mixed $data The entity being processed, which should be an instance of EntityBase.
     * @param Operation $operation The operation being performed (e.g., POST, PUT, DELETE).
     * @param array $uriVariables Variables extracted from the URI (for use in identifying resources).
     * @param array $context Additional context information relevant to the operation.
     * @return mixed The processed entity, or null if the data is not an instance of EntityBase.
     *
     * @throws \InvalidArgumentException If $data is not an instance of EntityBase.
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        // Check if data is an EntityBase instance
        if (!$data instanceof EntityBase) {
            return null;
        }

        // Handle soft delete on DELETE operations
        if ($operation instanceof DeleteOperationInterface) {
            $data->delete(); // Soft delete the entity
        } else {
            // Handle other operations (e.g., persist for POST or PUT)
            $this->entityManager->persist($data);
        }

        $this->entityManager->flush();

        return $data; // Return the processed entity
    }
}