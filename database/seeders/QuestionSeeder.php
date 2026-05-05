<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        // NO CHECK HERE - WE WANT TO FORCE DATA IN

        $questions = [
            [
                'module' => 'phishing',
                'key' => 'ceo_fraud',
                'title' => 'Urgent Wire Transfer',
                'type' => 'email',
                'sender_name' => 'CEO - John Smith',
                'sender_email' => 'ceo-urgent@company-mail.net',
                'subject' => 'URGENT: Client Payment Required',
                'body' => 'Hi,<br><br>I need you to process an urgent wire transfer immediately.<br><br>Sent from my iPad',
                'options' => [
                    ['text' => 'Process Transfer', 'result' => 'incorrect', 'feedback' => 'Always verify urgent money requests via phone.'],
                    ['text' => 'Reply asking details', 'result' => 'neutral', 'feedback' => 'Replying confirms your email to spammers.'],
                    ['text' => 'Report Phishing', 'result' => 'correct', 'feedback' => 'Correct. The domain @company-mail.net is fake.']
                ]
            ],
            [
                'module' => 'phishing',
                'key' => 'fake_hr',
                'title' => 'HR Policy Update',
                'type' => 'email',
                'sender_name' => 'Human Resources',
                'sender_email' => 'hr-notifications@company-secure-portal.xyz',
                'subject' => 'Action Required: New Vacation Policy',
                'body' => 'Please login below.<br><br><a href="#" class="fake-link" title="http://hr-portal-secure.xyz/login">Click here to login</a>',
                'options' => [
                    ['text' => 'Login to Portal', 'result' => 'incorrect', 'feedback' => 'Credential harvesting site (.xyz domain).'],
                    ['text' => 'Report Phishing', 'result' => 'correct', 'feedback' => 'Good catch. External URL.'],
                    ['text' => 'Ignore Email', 'result' => 'neutral', 'feedback' => 'Reporting helps protect others.']
                ]
            ],
            [
                'module' => 'phishing',
                'key' => 'suspicious_login',
                'title' => 'New Login Alert',
                'type' => 'email',
                'sender_name' => 'Security Team',
                'sender_email' => 'alert@micr0soft-security.net',
                'subject' => 'Security Alert: Login from Russia',
                'body' => 'We detected a sign-in.<br><br><button class="fake-btn" title="http://micr0soft-security.net">Secure My Account</button>',
                'options' => [
                    ['text' => 'Click Button', 'result' => 'incorrect', 'feedback' => 'Fake domain (micr0soft). Login to official site manually.'],
                    ['text' => 'Check Settings', 'result' => 'correct', 'feedback' => 'Perfect. Navigate manually.'],
                    ['text' => 'Reply', 'result' => 'neutral', 'feedback' => 'Automated emails are no-reply.']
                ]
            ],
            [
                'module' => 'phishing',
                'key' => 'zoom_invite',
                'title' => 'Zoom Meeting Invite',
                'type' => 'email',
                'sender_name' => 'Zoom',
                'sender_email' => 'no-reply@zoom.us',
                'subject' => 'Meeting Invitation',
                'body' => 'You have been invited to a meeting.<br><br><a href="#" class="fake-link" title="https://zoom.us/j/998273645">Join Zoom Meeting</a>',
                'options' => [
                    ['text' => 'Join Meeting', 'result' => 'correct', 'feedback' => 'Correct! This is a legitimate email from zoom.us.'],
                    ['text' => 'Report Phishing', 'result' => 'neutral', 'feedback' => 'This email is safe.'],
                    ['text' => 'Delete Email', 'result' => 'incorrect', 'feedback' => 'You missed a real meeting!']
                ]
            ],
            [
                'module' => 'phishing',
                'key' => 'it_maintenance',
                'title' => 'System Maintenance',
                'type' => 'email',
                'sender_name' => 'IT Support',
                'sender_email' => 'support@company.com',
                'subject' => 'Scheduled Downtime',
                'body' => 'Systems offline Saturday.<br><br>Status: <a href="#" class="fake-link" title="https://status.company.com">status.company.com</a>',
                'options' => [
                    ['text' => 'Mark Read', 'result' => 'correct', 'feedback' => 'Correct. Internal announcement.'],
                    ['text' => 'Report Phishing', 'result' => 'neutral', 'feedback' => 'This is from your own IT team.'],
                    ['text' => 'Reply', 'result' => 'incorrect', 'feedback' => 'Don\'t reply to mass announcements.']
                ]
            ],
            [
                'module' => 'phishing',
                'key' => 'password_reset',
                'title' => 'Password Expiry SMS',
                'type' => 'sms',
                'sender_name' => 'IT-Support',
                'sender_email' => null,
                'subject' => null,
                'body' => 'Password expires in 2h. Reset: <br><span class="imessage-link" title="http://bit.ly/reset">http://bit.ly/reset</span>',
                'options' => [
                    ['text' => 'Click Link', 'result' => 'incorrect', 'feedback' => 'IT never uses bit.ly for passwords.'],
                    ['text' => 'Report Spam', 'result' => 'correct', 'feedback' => 'Correct.'],
                    ['text' => 'Reply STOP', 'result' => 'neutral', 'feedback' => 'Confirms your number is active.']
                ]
            ],
            [
                'module' => 'phishing',
                'key' => 'bank_fraud',
                'title' => 'Bank Fraud Alert',
                'type' => 'sms',
                'sender_name' => 'BankAlert',
                'sender_email' => null,
                'subject' => null,
                'body' => 'Charge of $4,299? <br>Cancel: <span class="imessage-link" title="http://secure-bank.net">http://secure-bank.net</span>',
                'options' => [
                    ['text' => 'Click Cancel', 'result' => 'incorrect', 'feedback' => 'Phishing site.'],
                    ['text' => 'Reply NO', 'result' => 'neutral', 'feedback' => 'Invites scam calls.'],
                    ['text' => 'Login App', 'result' => 'correct', 'feedback' => 'Smart move.']
                ]
            ],
            [
                'module' => 'phishing',
                'key' => 'subscription_fail',
                'title' => 'Subscription Suspended',
                'type' => 'sms',
                'sender_name' => 'NetfIix',
                'sender_email' => null,
                'subject' => null,
                'body' => 'Payment declined. <br><span class="imessage-link" title="http://netflix-payment.com">http://netflix-payment.com</span>',
                'options' => [
                    ['text' => 'Click Link', 'result' => 'incorrect', 'feedback' => 'Fake URL and Sender Name.'],
                    ['text' => 'Go to App', 'result' => 'correct', 'feedback' => 'Correct.'],
                    ['text' => 'Reply Help', 'result' => 'neutral', 'feedback' => 'Confirms number.']
                ]
            ]
        ];

        foreach ($questions as $q) {
            Question::updateOrCreate(['key' => $q['key']], $q);
        }
    }
}