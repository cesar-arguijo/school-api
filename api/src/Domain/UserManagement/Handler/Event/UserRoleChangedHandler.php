<?php

namespace App\Domain\UserManagement\Handler;

use App\Domain\UserManagement\Entity\User;
use App\Domain\UserManagement\Event\UserRoleChanged;
use App\Domain\UserManagement\Service\NotificationService;
use App\Infrastructure\Persistence\UserRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

/**
 * Event Handler for UserRoleChanged
 *
 * Handles the role change event by notifying administrators and updating related permissions.
 *
 * Attributes:
 * - notificationService: The service responsible for sending notifications.
 *
 * Event Emission:
 * - Triggers when UserRoleChanged is dispatched.
 *
 * Usage Workflow:
 * - Notify administrators of the role change.
 * - Update permissions in related services.
 *
 * See Also:
 * - UserRoleChanged Event.
 * - NotificationService.
 */
#[AsEventListener(event: UserRoleChanged::class)]
class UserRoleChangedHandler
{
    /**
     * @var NotificationService The service for sending notifications to administrators.
     */
    private NotificationService $notificationService;

    /**
     * @var UserRepository The repository for updating the user data.
     */
    private UserRepository $userRepository;

    /**
     * @var LoggerInterface A logger for recording the role change events.
     */
    private LoggerInterface $logger;

    /**
     * Constructs a new UserRoleChangedHandler.
     *
     * @param NotificationService $notificationService The service used for sending notifications.
     * @param UserRepository $userRepository The repository for updating user data.
     * @param LoggerInterface $logger A logger instance to record role change events.
     */
    public function __construct(
        NotificationService $notificationService,
        UserRepository $userRepository,
        LoggerInterface $logger
    ) {
        $this->notificationService = $notificationService;
        $this->userRepository = $userRepository;
        $this->logger = $logger;
    }

    /**
     * Handles the UserRoleChanged event.
     *
     * This method performs actions necessary when a user's role changes, such as notifying administrators,
     * logging the role change, and updating related permissions if required.
     *
     * @param UserRoleChanged $event The event instance containing user ID, new role, and old role.
     * @return void
     */
    public function handle(UserRoleChanged $event): void
    {
        $userId = $event->getUserId();
        $newRole = $event->getNewRole();
        $oldRole = $event->getPreviousRole();

        // Notify administrators about the role change
        $this->notificationService->notifyAdmins("User $userId role changed from $oldRole to $newRole");

        // Log the role change for auditing purposes
        $this->logger->info("User $userId role changed", [
            'userId' => $userId,
            'newRole' => $newRole->getRole(),
            'oldRole' => $oldRole ? $oldRole->getRole() : null,
        ]);

        /** @var \App\Domain\UserManagement\Entity\User $user */
        $user = $this->userRepository->findById($userId);
        if ($user) {
            $user->updatePermissionsBasedOnRole($newRole);
            $this->userRepository->save($user);
        }
    }
}
