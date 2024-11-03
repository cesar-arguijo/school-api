<?php

namespace App\Domain\UserManagement\EventHandler;

use App\Domain\UserManagement\Event\UserRegistered;
use App\Domain\UserManagement\Service\EmailService;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

/**
 * Handles actions to be taken when a UserRegistered event is dispatched.
 * 
 * This handler listens for the UserRegistered event and performs necessary
 * actions such as sending a welcome email or initializing user resources.
 */
#[AsEventListener(event: UserRegistered::class)]
class UserRegisteredHandler
{
    /**
     * Service for sending emails.
     *
     * @var EmailService
     */
    private EmailService $emailService;

    /**
     * Constructor.
     *
     * @param EmailService $emailService The email service to send notifications.
     */
    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Handles the UserRegistered event by sending a welcome email.
     *
     * @param UserRegistered $event The UserRegistered event instance.
     * @return void
     */
    public function __invoke(UserRegistered $event): void
    {
        // Sending a welcome email
        $this->emailService->sendWelcomeEmail(
            $event->getEmail(),
            $event->getUsername()
        );

        // Additional actions (if any) can be added here
    }
}
