<?php

namespace App\Domain\UserManagement\Entity;

use App\Domain\Common\Entity\Thing;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entity representing a user device.
 *
 * @ORM\Entity
 * @ORM\Table(name="devices")
 */
#[ORM\Entity]
#[ORM\Table(name: 'devices')]
class Device extends Thing
{
    /**
     * A unique identifier for the device, such as a UUID or persistent token.
     *
     * @var string
     * @ORM\Column(type="string", unique=true)
     */
    #[ORM\Column(type: "string", unique: true)]
    private string $deviceIdentifier;

    /**
     * Type of device (e.g., "mobile", "desktop", "tablet").
     *
     * @var string
     * @ORM\Column(type="string")
     */
    #[ORM\Column(type: "string")]
    private string $deviceType;

    /**
     * Optional: Browser fingerprint or other identifying data for additional verification.
     *
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    #[ORM\Column(type: "string", nullable: true)]
    private ?string $fingerprint;

    /**
     * Initializes a new Device instance.
     *
     * @param string $deviceIdentifier A unique identifier for the device.
     * @param string $deviceType The type of device (e.g., "mobile", "desktop").
     * @param string|null $fingerprint Optional additional fingerprint data.
     */
    public function __construct(string $deviceIdentifier, string $deviceType, ?string $fingerprint = null)
    {
        parent::__construct();
        $this->deviceIdentifier = $deviceIdentifier;
        $this->deviceType = $deviceType;
        $this->fingerprint = $fingerprint;
    }

    /**
     * Gets the device's unique identifier.
     *
     * @return string The device identifier.
     */
    public function getDeviceIdentifier(): string
    {
        return $this->deviceIdentifier;
    }

    /**
     * Gets the type of device.
     *
     * @return string The type of device (e.g., "mobile", "desktop").
     */
    public function getDeviceType(): string
    {
        return $this->deviceType;
    }

    /**
     * Gets the optional fingerprint data for the device.
     *
     * @return string|null The fingerprint data, or null if not set.
     */
    public function getFingerprint(): ?string
    {
        return $this->fingerprint;
    }

    /**
     * Checks if this device is equal to another device.
     *
     * Devices are considered equal if they share the same device identifier,
     * type, and optional fingerprint.
     *
     * @param Device $otherDevice The device to compare with.
     * @return bool True if the devices are equal, false otherwise.
     */
    public function equals(Device $otherDevice): bool
    {
        return $this->deviceIdentifier === $otherDevice->getDeviceIdentifier() &&
            $this->deviceType === $otherDevice->getDeviceType() &&
            $this->fingerprint === $otherDevice->getFingerprint();
}
}
