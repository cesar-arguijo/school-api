<?php

namespace App\Domain\UserManagement\Service;

use App\Infrastructure\Mailer\MailerService;
use App\Infrastructure\Persistence\UserRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Uid\Uuid;

/**
 * Service for handling notifications within the application.
 * 
 * This service is responsible for notifying administrators and other relevant users
 * about significant events, such as role changes or security alerts.
 */
class NotificationService
{
    /**
     * Logger for recording notification events.
     * 
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    private MailerService $mailerService;

    private UserRepository $userRepository;

    /**
     * Constructs a NotificationService instance.
     * 
     * @param MailerService $mailerService Service used to send notification emails.
     * @param LoggerInterface $logger Logger for recording notifications.
     */
    public function __construct(
        MailerService $mailerService, 
        LoggerInterface $logger,
        UserRepository $userRepository
    ){
        $this->logger = $logger;
        $this->mailerService = $mailerService;
        $this->userRepository = $userRepository;
    }

    /**
     * Sends a notification to the administrators.
     * 
     * @param string $message The message to notify administrators.
     * @return void
     */
    public function notifyAdmins(string $message): void
    {
        // This could be expanded to send emails or messages to specific channels.
        // For now, we simply log the notification for auditing purposes.
        $this->logger->info("Admin Notification: " . $message);
    }

     /**
     * Notifies the user of a password change.
     *
     * @param string $userId The ID of the user who changed their password.
     * @return void
     */
    public function notifyPasswordChange(Uuid $userId): void
    {
        // Retrieve user email and other details, assume a repository or method to get user by ID
        /** @var \App\Domain\UserManagement\Entity\User $user */
        $user = $this->userRepository->findById($userId); // Placeholder method, requires implementation
        $email = $user->getEmail();
        $username = $user->getUsername();

        // Compose notification message
        $subject = 'Your password has been changed';
        $message = sprintf(
            "Hello %s,\n\nYour password was recently changed. If you did not request this change, please contact support immediately.",
            $username
        );

        // Send email notification
        $this->mailerService->sendEmail(
            $email,
            $subject,
            $message
        );
    }

    /**
     * Sends a notification to the user when a new device is detected.
     * 
     * @param Uuid $userId The unique identifier of the user.
     * @param string $deviceType The type of the device (e.g., mobile, desktop).
     * @param \DateTimeImmutable $timestamp The time of the login attempt.
     * @return void
     */
    public function notifyNewDeviceDetected(Uuid $userId, string $deviceType, \DateTimeImmutable $timestamp): void
    {
        // Fetch user email (could be extended to fetch from UserRepository or UserService)
        $userEmail = $this->getUserEmailById($userId);
        
        // Compose the email notification
        $subject = 'New Device Detected';
        $body = sprintf(
            "Hello,\n\nWe detected a new %s device accessing your account at %s.\n\nIf this was not you, please secure your account immediately.",
            ucfirst($deviceType),
            $timestamp->format('Y-m-d H:i:s')
        );

        // Send the email notification using the MailerService
        $this->mailerService->sendEmail(
            $userEmail,
            $subject,
            $body,
            'no-reply@example.com'
        );
    }

    /**
     * Retrieves the user email by their ID.
     * 
     * @param Uuid $userId The unique identifier of the user.
     * @return string|null The email of the user or null if not found.
     */
    private function getUserEmailById(Uuid $userId): ?string
    {
        /** @var \App\Domain\UserManagement\Entity\User */
        $user=$this->userRepository->findById($userId);
        return $user->getEmail(); // Replace with actual repository lookup
    }

    /**
     * Sends a notification to the user when their session limit is exceeded.
     * 
     * @param Uuid $userId The unique identifier of the user.
     * @param int $limit The maximum allowed number of sessions.
     * @param \DateTimeImmutable $timestamp The time of the session limit event.
     * @return void
     */
    public function notifySessionLimitExceeded(Uuid $userId, int $limit, \DateTimeImmutable $timestamp): void
    {
        // Fetch user email (could extend this to use UserRepository or UserService)
        $userEmail = $this->getUserEmailById($userId);

        // Compose the email notification
        $subject = 'Session Limit Exceeded';
        $body = sprintf(
            "Hello,\n\nYour account has exceeded the allowed session limit of %d sessions as of %s.\n\nPlease review your active sessions and log out of any devices that are not currently in use to secure your account.",
            $limit,
            $timestamp->format('Y-m-d H:i:s')
        );

        // Send the email notification using the MailerService
        $this->mailerService->sendEmail(
            $userEmail,
            $subject,
            $body,
            'no-reply@example.com'
        );
    }
}
