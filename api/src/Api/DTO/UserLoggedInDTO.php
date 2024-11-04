<?php

namespace App\Api\DTO;

use Symfony\Component\Uid\Uuid;
use DateTimeImmutable;

/**
 * Data Transfer Object for user login events within the API context.
 * Encapsulates essential data related to a user's login session.
 */
class UserLoggedInDTO
{
    private Uuid $userId;
    private Uuid $deviceId;
    private DateTimeImmutable $timestamp;

    public function __construct(Uuid $userId, Uuid $deviceId, DateTimeImmutable $timestamp)
    {
        $this->userId = $userId;
        $this->deviceId = $deviceId;
        $this->timestamp = $timestamp;
    }

    public function getUserId(): Uuid
    {
        return $this->userId;
    }

    public function getDeviceId(): Uuid
    {
        return $this->deviceId;
    }

    public function getTimestamp(): DateTimeImmutable
    {
        return $this->timestamp;
    }
}
