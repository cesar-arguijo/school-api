<?php

namespace App\Domain\UserManagement\Entity;

use App\Domain\Common\Entity\EntityBase;
use App\Domain\UserManagement\Entity\Device;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * Entity to manage user session data, including device association and expiration.
 * 
 * @ORM\Entity
 * @ORM\Table(name="sessions")
 */
#[ORM\Entity]
#[ORM\Table(name: 'sessions')]
class Session extends EntityBase
{
    /**
     * The expiration timestamp of the session.
     *
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    #[ORM\Column(type: "datetime")]
    private DateTime $expiresAt;

    /**
     * The timestamp when the session ended.
     *
     * @var DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    #[ORM\Column(type: "datetime", nullable: true)]
    private ?DateTime $endedAt = null;

    /**
     * The associated device for this session.
     *
     * @var Device|null
     * @ORM\OneToOne(targetEntity="Device")
     * @ORM\JoinColumn(name="device_id", referencedColumnName="id", onDelete="SET NULL")
     */
    #[ORM\OneToOne(targetEntity: Device::class)]
    #[ORM\JoinColumn(name: "device_id", referencedColumnName: "id", onDelete: "SET NULL")]
    private ?Device $device;

    /**
     * Initializes a new Session object.
     *
     * @param DateTime $expiresAt The expiration timestamp of the session.
     * @param Device $device The device associated with the session.
     */
    public function __construct(DateTime $expiresAt, Device $device)
    {
        parent::__construct();
        $this->expiresAt = $expiresAt;
        $this->device = $device;
    }

    /**
     * Gets the expiration time of the session.
     *
     * @return DateTime The session expiration timestamp.
     */
    public function getExpiresAt(): DateTime
    {
        return $this->expiresAt;
    }

    /**
     * Checks if the session is expired or has been explicitly ended.
     *
     * @return bool True if the session is expired or ended, false otherwise.
     */
    public function isExpired(): bool
    {
        return (new DateTime()) > $this->expiresAt || $this->endedAt !== null;
    }

    /**
     * Gets the device associated with this session.
     *
     * @return Device|null The device, or null if none is associated.
     */
    public function getDevice(): ?Device
    {
        return $this->device;
    }

    public function equals(Session $session){
        return $this->getId() === $session->getId();
    }

    /**
     * Ends the session by setting the ended timestamp.
     *
     * @return void
     */
    public function end(): void
    {
        $this->endedAt = new DateTime();
    }

    /**
     * Gets the end time of the session, if it has ended.
     *
     * @return DateTime|null The session end timestamp, or null if not ended.
     */
    public function getEndedAt(): ?DateTime
    {
        return $this->endedAt;
    }
}
