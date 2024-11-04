<?php

namespace App\Domain\UserManagement\Service;

use App\Domain\UserManagement\Entity\Session;
use App\Domain\UserManagement\Entity\User;
use App\Infrastructure\Persistence\SessionRepository;
use App\Infrastructure\Persistence\UserRepository;

/**
 * Service responsible for managing session-related operations, 
 * such as ending a session and updating active session counts.
 */
class SessionManagementService
{
    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;

    public function __construct(SessionRepository $sessionRepository, UserRepository $userRepository)
    {
        $this->sessionRepository = $sessionRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Ends a session and updates the user's active session count.
     *
     * @param Session $session The session to end.
     * @param User $user The user whose session is ending.
     * @return void
     */
    public function endSessionAndUpdateCount(Session $session, User $user): void
    {
        // Mark session as ended
        $session->end();
        $this->sessionRepository->save($session);

        // Update active session count for the user
        $activeSessionCount = $this->sessionRepository->countActiveSessions($user->getId());
        $user->updateActiveSessionsCount($activeSessionCount);
        $this->userRepository->save($user);
    }
}
