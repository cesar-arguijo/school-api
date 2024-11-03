<?php

namespace App\Tests\Infrastructure\Mailer;

use App\Infrastructure\Mailer\MailerService;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Mailer\MailerInterface;

/**
 * Test case for MailerService using Mailhog and Guzzle to verify email sending functionality.
 * 
 * This test ensures that the MailerService correctly sends emails and that they are received by
 * Mailhog, allowing for validation of email contents.
 */
class MailerServiceIntegrationTest extends KernelTestCase
{
    /**
     * @var MailerService The service responsible for sending emails.
     */
    private MailerService $mailerService;

    /**
     * @var Client HTTP client used to interact with Mailhog API.
     */
    private Client $client;

    /**
     * Sets up the testing environment, including initializing the MailerService and Guzzle client.
     * 
     * @return void
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        // Get the MailerService
        $mailer = $container->get(MailerInterface::class);
        $this->mailerService = new MailerService($mailer);

        // Guzzle client for Mailhog API interaction
        $this->client = new Client(['base_uri' => 'http://mailhog:8025']);
    }

    /**
     * Tests that an email is successfully sent and received by Mailhog.
     * 
     * This test sends an email using MailerService and verifies that Mailhog receives it,
     * asserting the recipient, subject, and body content to ensure the email integrity.
     * 
     * @return void
     */
    public function testSendEmail(): void
    {
        // Define the test email parameters
        $recipientEmail = 'test@example.com';
        $subject = 'Test Subject';
        $body = 'This is a test email body';

        // Send the email
        $this->mailerService->sendEmail(
            $recipientEmail,
            $subject,
            $body,
            'sender@example.com'
        );

        // Verify the email in Mailhog
        $response = $this->client->get('/api/v2/messages');
        $messages = json_decode($response->getBody()->getContents(), true);

        // Assert that at least one email was sent
        $this->assertNotEmpty($messages['items']);

        // Retrieve the latest message
        $latestMessage = $messages['items'][0];

        // Verify recipient, subject, and body of the email
        $this->assertEquals($recipientEmail, $latestMessage['Content']['Headers']['To'][0]);
        $this->assertEquals($subject, $latestMessage['Content']['Headers']['Subject'][0]);
        $this->assertStringContainsString($body, $latestMessage['Content']['Body']);
    }

    /**
     * Cleans up after each test by deleting all messages from Mailhog.
     * 
     * This method helps ensure that each test runs independently by clearing any previously sent emails.
     * 
     * @return void
     */
    protected function tearDown(): void
    {
        // Clear all emails from Mailhog after each test
        $this->client->delete('/api/v1/messages');
    }
}
