<?php

namespace App\Infrastructure\Adapter;

use App\Domain\UserManagement\Auth\Service\SessionManagerInterface;
use App\Infrastructure\Persistence\SessionRepository;
use Symfony\Component\Uid\Uuid;

/**
 * SessionManagerAdapter
 *
 * Adapter for managing user sessions, interacting with SessionRepository.
 */
class SessionManagerAdapter implements SessionManagerInterface
{
    private SessionRepository $sessionRepository;

    /**
     * Constructs a new instance of SessionManagerAdapter.
     *
     * @param SessionRepository $sessionRepository The repository to manage session data.
     */
    public function __construct(SessionRepository $sessionRepository)
    {
        $this->sessionRepository = $sessionRepository;
    }

    /**
     * Creates a new session for a user and device.
     *
     * @param Uuid $userId The unique identifier of the user.
     * @param Uuid $deviceId The unique identifier of the device used for the login.
     * @return void
     */
    public function createSession(Uuid $userId, Uuid $deviceId): void
    {
        $this->sessionRepository->createSession($userId, $deviceId);
    }
}
