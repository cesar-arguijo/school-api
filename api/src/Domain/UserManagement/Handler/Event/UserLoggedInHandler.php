<?php

namespace App\Domain\UserManagement\EventHandler;

use App\Domain\UserManagement\Entity\User;
use App\Domain\UserManagement\Event\NewDeviceDetected;
use App\Domain\UserManagement\Event\UserLoggedIn;
use App\Domain\UserManagement\Service\SessionService;
use App\Domain\UserManagement\Service\AlertService;
use App\Infrastructure\Persistence\UserRepository;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

/**
 * UserLoggedInHandler
 *
 * Handles actions to be taken when a UserLoggedIn event is dispatched.
 *
 * **Attributes**
 *
 * - *SessionService*: Manages user sessions, creating a new one when the user logs in.
 * - *AlertService*: Sends alerts, e.g., for new device logins.
 * - *UserRepository*: Provides access to user data, allowing for updates on the last login timestamp.
 *
 * **Event Emission**
 *
 * The `UserLoggedIn` event is emitted by the `User` entity, raising the event through the 
 * `EventRaiserTrait` when a user successfully logs in. The `UserService` then collects 
 * the event and dispatches it to `UserLoggedInHandler`.
 *
 * **Usage Workflow**
 *
 * - *Event Emission in Entity*: When the `User` entity registers a login, it emits `UserLoggedIn`.
 * - *Event Dispatching in Application Service*: `UserService` handles login, saves the user, and dispatches `UserLoggedIn`.
 * - *Event Handling by UserLoggedInHandler*: This handler listens for `UserLoggedIn` and:
 *   - Updates the `lastLogin` timestamp of the user.
 *   - Creates a new session for the user using `SessionService`.
 *   - Triggers a security alert if a new device is detected via `AlertService`.
 *
 * @see UserRepository Provides user-related data management.
 * @see SessionService Manages session data.
 */
#[AsEventListener(event: UserLoggedIn::class)]
class UserLoggedInHandler
{
    /**
     * @var SessionService Manages sessions for logged-in users.
     */
    private SessionService $sessionService;

    /**
     * @var AlertService Sends security alerts.
     */
    private AlertService $alertService;

    /**
     * @var UserRepository Manages user data.
     */
    private UserRepository $userRepository;

    /**
     * Constructs a new instance of UserLoggedInHandler.
     *
     * @param SessionService $sessionService The session service to manage user sessions.
     * @param AlertService $alertService The alert service to send security notifications.
     * @param UserRepository $userRepository The repository for user data.
     */
    public function __construct(
        SessionService $sessionService,
        AlertService $alertService,
        UserRepository $userRepository
    ) {
        $this->sessionService = $sessionService;
        $this->alertService = $alertService;
        $this->userRepository = $userRepository;
    }

    /**
     * Handles the UserLoggedIn event by managing session and alert activities.
     *
     * - Updates the last login timestamp in `UserRepository`.
     * - Creates a session through `SessionService`.
     * - Sends a new device alert via `AlertService`.
     *
     * @param UserLoggedIn $event The UserLoggedIn event instance.
     * @return void
     */
    public function __invoke(UserLoggedIn $event): void
    {
        /** @var User */
        $user = $event->getUser();

        if ($user) {
            // Update last login time
            $user->setLastLogin($event->getTimestamp());
            $this->userRepository->save($user);

            // Create a new session
            $this->sessionService->createSession($user, $event->getDevice());
        }
    }
}
