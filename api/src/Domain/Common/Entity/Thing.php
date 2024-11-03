<?php

namespace App\Domain\Common\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Domain\Common\Entity\EntityBase;

/**
 * Class Thing
 *
 * Represents a generic entity that can be extended by multiple domain-specific entities.
 *
 * @package App\Domain\Common\Entity
 */
#[ORM\MappedSuperclass]
abstract class Thing extends EntityBase
{
    /**
     * The name of the Thing entity.
     *
     * @var string
     */
    #[ORM\Column(type: "string", length: 255)]
    private string $name;

    public function __construct(string $name)
    {
        $this->setName($name);
    }

    /**
     * Sets the name of the entity.
     *
     * @param string $name The name of the entity.
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Gets the name of the entity.
     *
     * @return string The name of the entity.
     */
    public function getName(): string
    {
        return $this->name;
    }
}