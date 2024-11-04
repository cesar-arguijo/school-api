<?php

namespace App\Domain\UserManagement\Auth\Handler;

use App\Domain\UserManagement\Auth\Event\UserLoggedIn;
use App\Domain\UserManagement\Auth\Service\LoginService;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

/**
 * UserLoggedInHandler
 *
 * Handles actions to be taken when a UserLoggedIn event is dispatched.
 *
 * **Attributes**
 *
 * - *LoginService*: Manages login operations for users, including session creation and login timestamp update.
 *
 * **Event Emission**
 *
 * The `UserLoggedIn` event is emitted by the `User` entity upon successful login, 
 * handled by `LoginService` which coordinates the login process.
 *
 * **Usage Workflow**
 *
 * - Event Emission in Entity: The `User` entity emits `UserLoggedIn` upon successful login.
 * - Event Handling by UserLoggedInHandler: This handler listens for `UserLoggedIn` and delegates 
 *   the login processing to `LoginService`.
 *
 * @see LoginService Manages login-related operations.
 */
#[AsEventListener(event: UserLoggedIn::class)]
class UserLoggedInHandler
{
    private LoginService $loginService;

    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }

    public function __invoke(UserLoggedIn $event): void
    {
        $this->loginService->handleUserLogin($event->getUserId(), 
            $event->getDeviceId(), 
            $event->getTimestamp());
    }
}