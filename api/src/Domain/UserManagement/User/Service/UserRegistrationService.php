<?php

namespace App\Domain\UserManagement\User\Service;

use App\Domain\UserManagement\User\Event\UserRegistered;
use App\Domain\UserManagement\User\Entity\User;
use App\Domain\UserManagement\User\Repository\UserRepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * UserRegistrationService
 *
 * Manages the process of user registration, including persisting new user data 
 * and triggering related domain events.
 *
 * **Attributes**
 * 
 * - *UserRepositoryInterface*: Used to persist user data.
 * - *EventDispatcherInterface*: Dispatches events to notify other parts of the system about the new user registration.
 *
 * **Responsibilities**
 * - Persists new users to the data repository.
 * - Emits a `UserRegistered` event to indicate successful registration, allowing other services 
 *   to act on this event (e.g., sending welcome emails, logging registration activities).
 *
 * **Usage Workflow**
 * 
 * - The `registerUser()` method is called with a `User` entity, saving it in the repository and dispatching
 *   a `UserRegistered` event for further handling by other services.
 *
 * **Example**
 *
 * ```php
 * $user = new User($username, $email, $password);
 * $userRegistrationService->registerUser($user);
 * ```
 *
 * @see UserRepositoryInterface Repository interface for accessing and saving user data.
 * @see UserRegistered Event triggered upon successful user registration.
 */
class UserRegistrationService
{
    private UserRepositoryInterface $userRepository;
    private EventDispatcherInterface $eventDispatcher;

    /**
     * Constructs a new instance of UserRegistrationService.
     *
     * @param UserRepositoryInterface $userRepository The repository responsible for saving user data.
     * @param EventDispatcherInterface $eventDispatcher The dispatcher used to emit domain events.
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->userRepository = $userRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Registers a new user and dispatches the UserRegistered event.
     *
     * This method persists the new user entity in the repository and emits a 
     * `UserRegistered` event, allowing other parts of the application to 
     * respond to the registration event.
     *
     * @param User $user The user entity to be registered.
     * @return void
     */
    public function registerUser(User $user): void
    {
        // Save the user in the repository
        $this->userRepository->save($user);

        // Dispatch the UserRegistered event
        $this->eventDispatcher->dispatch(new UserRegistered($user->getId(), $user->getUsername(), $user->getEmail()));
    }
}
