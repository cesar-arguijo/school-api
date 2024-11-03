<?php

namespace App\Tests\Infrastructure\Mailer;

use App\Infrastructure\Mailer\MailerService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

/**
 * Class MailerServiceTest
 * 
 * Test suite for the MailerService class, ensuring it correctly configures
 * and sends email messages.
 */
class MailerServiceTest extends TestCase
{
    /**
     * @var MailerService
     */
    private MailerService $mailerService;

    /**
     * @var MailerInterface&\PHPUnit\Framework\MockObject\MockObject
     */
    private MailerInterface $mailerMock;

    /**
     * Sets up the MailerServiceTest environment.
     */
    protected function setUp(): void
    {
        /** @var MailerInterface&\PHPUnit\Framework\MockObject\MockObject $mailerMock */
        $this->mailerMock = $this->createMock(MailerInterface::class);
        $this->mailerService = new MailerService($this->mailerMock);
    }

    /**
     * Tests the sendEmail method in MailerService.
     */
    public function testSendEmail(): void
    {
        $to = 'recipient@example.com';
        $subject = 'Test Subject';
        $body = 'This is a test email body.';
        $from = 'sender@example.com';

        $this->mailerMock->expects($this->once())
            ->method('send')
            ->with($this->callback(function (Email $email) use ($to, $subject, $body, $from) {
                return $email->getTo()[0]->getAddress() === $to &&
                       $email->getSubject() === $subject &&
                       $email->getTextBody() === $body &&
                       $email->getFrom()[0]->getAddress() === $from;
            }));

        $this->mailerService->sendEmail($to, $subject, $body, $from);
    }

    /**
     * Tests that sendEmail defaults the sender to 'no-reply@example.com' when $from is not provided.
     * 
     * @return void
     */
    public function testSendEmailWithDefaultFromAddress(): void
    {
        $to = 'recipient@example.com';
        $subject = 'Test Subject';
        $body = 'This is the test email body.';

        $defaultFrom = 'no-reply@example.com';

        $this->mailerMock->expects($this->once())
            ->method('send')
            ->with($this->callback(function (Email $email) use ($to, $subject, $body, $defaultFrom) {
                return $email->getTo()[0]->getAddress() === $to &&
                    $email->getSubject() === $subject &&
                    $email->getTextBody() === $body &&
                    $email->getFrom()[0]->getAddress() === $defaultFrom;
            }));

        $this->mailerService->sendEmail($to, $subject, $body);
    }
}
