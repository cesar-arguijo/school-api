<?php

namespace App\Domain\UserManagement\User\Handler;

use App\Api\DTO\UserRegisteredDTO;
use App\Domain\UserManagement\User\Adapter\AuditAdapter;
use App\Domain\UserManagement\User\Entity\User;
use App\Domain\UserManagement\User\Event\UserRegistered;
use App\Domain\UserManagement\User\Service\EmailService;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

/**
 * UserRegisteredHandler
 *
 * Handles the UserRegistered event by logging registration details to the audit trail and 
 * sending a welcome email to the newly registered user.
 *
 * **Attributes**
 *
 * - *AuditAdapter*: Manages logging actions to the audit trail.
 * - *EmailService*: Handles sending emails, specifically for user registration.
 *
 * **Event Emission**
 *
 * - This handler listens for the `UserRegistered` event, which is dispatched when a user registers successfully.
 *
 * **Usage Workflow**
 *
 * - *Audit Logging*: Upon user registration, the handler logs an audit entry recording the registration action.
 * - *Welcome Email*: Sends a welcome email to the new user as part of the registration confirmation.
 *
 * **See Also**
 *
 * @see UserRegistered: The event that triggers this handler.
 * @see AuditAdapter: Adapter for managing audit logging.
 * @see EmailService: Service used for sending welcome emails to new users.
 */
#[AsEventListener(event: UserRegistered::class)]
class UserRegisteredHandler
{
    private AuditAdapter $auditAdapter;
    private EmailService $emailService;

    /**
     * Constructs a new UserRegisteredHandler instance.
     *
     * @param AuditAdapter $auditAdapter The adapter responsible for logging audits.
     * @param EmailService $emailService The service used to send welcome emails to registered users.
     */
    public function __construct(AuditAdapter $auditAdapter, EmailService $emailService)
    {
        $this->auditAdapter = $auditAdapter;
        $this->emailService = $emailService;
    }

    /**
     * Handles the UserRegistered event by performing audit logging and sending a welcome email.
     *
     * - Logs the registration event to the audit trail via `AuditAdapter`.
     * - Sends a welcome email to the user's registered email address.
     *
     * @param UserRegistered $event The event containing the registered user's information.
     * @return void
     */
    public function __invoke(UserRegistered $event): void
    {
        // Crea el DTO con los datos del evento
        $dto = new UserRegisteredDTO(
            $event->getUserId(),
            $event->getUsername(),
            $event->getEmail()
        );
        
        // Usa el DTO para el log de auditoría y el envío de correo electrónico
         $this->auditAdapter->logAudit(
            action: 'UserRegistered',
            entity: User::class,
            entityId: $dto->getUserId(),
            performedBy: $dto->getUserId()
        );

        // Send a welcome email to the registered user
        $this->emailService->sendWelcomeEmail($dto);
    }
}
