<?php

namespace App\Domain\UserManagement\Service;

use App\Domain\UserManagement\Entity\Device;
use App\Domain\UserManagement\Entity\Session;
use App\Domain\UserManagement\Entity\User;
use App\Domain\UserManagement\Event\UserLoggedIn;
use App\Domain\UserManagement\ValueObject\Password;
use App\Infrastructure\Persistence\UserRepository;
use DateTimeImmutable;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * UserService
 *
 * This service is responsible for managing user-related operations, including registration.
 *
 * **Event Dispatching**
 *
 * - After a new user is registered, the UserService collects the events from the
 *   User entity and dispatches them using the EventDispatcherInterface.
 * - This workflow allows other services, such as notification or email services,
 *   to handle the events accordingly.
 * 
 * **Workflow**
 *
 * - *Event Emission*: The User entity emits `UserRegistered` upon a successful registration.
 * - *Event Dispatching*: The UserService saves the user and dispatches the `UserRegistered` event.
 * - *Event Handling*: The `UserRegisteredHandler` listens for the event and performs follow-up actions, such as:
 *     - Sending a welcome email
 *     - Initializing a user session
 *     - Sending an account activation link
 * 
 * @see UserRegistered For more information on the `UserRegistered` event class.
 */
class UserService
{
    private UserRepository $userRepository;
    private EventDispatcherInterface $eventDispatcher;
    private SessionService $sessionService;

    /**
     * UserService constructor.
     * 
     * @param UserRepository $userRepository
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        UserRepository $userRepository, 
        EventDispatcherInterface $eventDispatcher,
        SessionService $sessionService
        )
    {
        $this->userRepository = $userRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->sessionService = $sessionService;
    }

    public function loginUser(User $user, Device $device): Session
    {
        // Crear una nueva sesión a través del servicio de sesiones
        $session = $this->sessionService->createSession($user, $device);
    
        // Emitir el evento UserLoggedIn
        $timestamp = new DateTimeImmutable();
        $user->raiseEvent(new UserLoggedIn($user, $device, $timestamp));
    
        // Persistir cambios y despachar el evento
        $this->userRepository->save($user);
        $this->persistAndDispatch($user);

        return $session;
    }

     /**
     * Changes the user's password and dispatches the PasswordChanged event.
     *
     * @param User $user The user entity.
     * @param Password $newPassword The new password.
     * @return void
     */
    public function changeUserPassword(User $user, Password $newPassword): void
    {
        $user->changePassword($newPassword);
        $this->persistAndDispatch($user);
    }

    /**
     * Registers a new user and dispatches UserRegistered event.
     * 
     * @param User $user The user entity to register.
     * @return void
     */
    public function registerUser(User $user): void
    {
        $user->register();
        $this->persistAndDispatch($user);
    }

    /**
     * Persists user and dispatches collected domain events.
     *
     * @param User $user The user entity to persist.
     * @return void
     */
    private function persistAndDispatch(User $user): void
    {
        $this->userRepository->save($user);

        // Dispatch each event stored in the user
        foreach ($user->getEvents() as $event) {
            $this->eventDispatcher->dispatch($event);
        }

        // Clear events after dispatching to avoid duplicate handling
        $user->clearEvents();
    }
}
