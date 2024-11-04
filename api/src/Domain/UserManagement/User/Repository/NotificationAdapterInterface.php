<?php

namespace App\Domain\UserManagement\Notification;

/**
 * NotificationAdapterInterface
 *
 * Defines the contract for a notification adapter that handles sending different types
 * of notifications such as alerts and emails. This interface allows for flexible integration
 * with various notification services, facilitating decoupling from specific implementations.
 *
 * **Methods**
 *
 * - `sendAlert`: Sends an alert notification to specified recipients.
 * - `sendEmail`: Sends an email notification with a subject, body, and recipients.
 *
 * **Usage**
 *
 * Implementations of this interface can be used to manage notifications across the domain,
 * particularly when alerts or emails need to be sent based on specific events or actions
 * within the application.
 */
interface NotificationAdapterInterface
{
    /**
     * Sends an alert notification.
     *
     * This method allows sending a general alert message to multiple recipients. Typically
     * used for urgent or real-time notifications that require immediate attention.
     *
     * @param string $message The alert message.
     * @param array $recipients List of recipients to receive the alert.
     * @return void
     */
    public function sendAlert(string $message, array $recipients): void;

    /**
     * Sends an email notification.
     *
     * This method sends an email with a specified subject and body content to one or more recipients.
     * Useful for sending updates, confirmations, or other informational messages.
     *
     * @param string $subject The subject of the email.
     * @param string $body The body content of the email.
     * @param array $recipients List of email recipients.
     * @return void
     */
    public function sendEmail(string $subject, string $body, array $recipients): void;
}
