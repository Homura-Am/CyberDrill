<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PhishingScenario;

class PhishingScenarioSeeder extends Seeder
{
    public function run(): void
    {
        $scenarios = [
            [
                'key' => 'ceo_fraud', 
                'title' => 'Urgent Wire Transfer', 
                'type' => 'email',
                'sender_name' => 'John Smith - CEO', 
                'sender_email' => 'ceo@company-exec.net',
                'subject' => 'URGENT: Client Payment Required',
                'body' => '<p>Hi,</p><p>I am heading into a board meeting and need you to process an urgent wire transfer to our new vendor immediately.</p><p><i>Sent from my iPad</i></p>',
                'is_phishing' => true,
                'malicious_zone' => 'zone2', // The fake email address
                'feedback' => 'The sender email domain was company-exec.net, not our official domain.',
            ],
            [
                'key' => 'zoom_invite', 
                'title' => 'Project Kickoff', 
                'type' => 'email',
                'sender_name' => 'Zoom Meetings', 
                'sender_email' => 'no-reply@zoom.us',
                'subject' => 'Meeting Invitation: Q3 Planning',
                'body' => '<p>You have been invited to a scheduled Zoom meeting.</p><h3>Q3 Planning</h3><p>Join Zoom Meeting:<br><a href="#">https://zoom.us/j/98237498234</a></p>',
                'is_phishing' => false,
                'malicious_zone' => null,
                'feedback' => 'This was a legitimate automated email from Zoom.',
            ],
            [
                'key' => 'bank_sms', 
                'title' => 'Bank Fraud Alert', 
                'type' => 'sms',
                'sender_name' => 'BankAlert', 
                'sender_email' => null, // SMS has no email
                'subject' => null,      // SMS has no subject
                'body' => 'ALERT: A charge of $4,299.00 was attempted on your card. Cancel immediately at: http://secure-bank-auth.net',
                'is_phishing' => true,
                'malicious_zone' => 'zone2', // The text body containing the fake link
                'feedback' => 'Banks do not send unsecure HTTP links for fraud resolution.',
            ],
            [
                'key' => 'fake_hr', 
                'title' => 'HR Policy Update', 
                'type' => 'email',
                'sender_name' => 'Human Resources', 
                'sender_email' => 'hr@company.com', 
                'subject' => 'Action Required: New Vacation Policy',
                'body' => '<p>Dear Employee,</p><p>We have updated our internal PTO policy. Please login to the HR portal here: <br><br><a href="#">Acknowledge Policy</a></p><p><span style="color:#94a3b8;font-size:12px;">Link destination: http://hr-portal-secure.xyz/login</span></p>',
                'is_phishing' => true,
                'malicious_zone' => 'zone3', // The body containing the bad URL
                'feedback' => 'While the sender looked internal, the hidden URL led to a credential harvesting .xyz domain.',
            ],
            [
                'key' => 'it_password_reset', 
                'title' => 'Password Expiry Notice', 
                'type' => 'email',
                'sender_name' => 'IT Service Desk', 
                'sender_email' => 'helpdesk@company.com', 
                'subject' => 'Action Required: Password Expires in 24 Hours',
                'body' => '<p>Your network password will expire in exactly 24 hours.</p><p>Please retain your current password by verifying your active session here: <br><br><button style="padding:8px 12px;background:#3b82f6;color:#fff;border:none;border-radius:4px;cursor:pointer;">Keep Password</button></p><p><span style="color:#94a3b8;font-size:12px;">Button destination: http://login-portal-company.auth-verify.com</span></p>',
                'is_phishing' => true,
                'malicious_zone' => 'zone3', // Bad link in the body
                'feedback' => 'IT will never ask you to click a link to "keep" your password. The button points to a fraudulent domain.',
            ],
            [
                'key' => 'delivery_sms', 
                'title' => 'Failed Delivery Attempt', 
                'type' => 'sms',
                'sender_name' => 'PostService', 
                'sender_email' => null, 
                'subject' => null,      
                'body' => 'Your package could not be delivered due to an unpaid customs fee of $1.99. Pay now to schedule delivery: http://post-customs-fee.net/track',
                'is_phishing' => true,
                'malicious_zone' => 'zone2', // SMS body
                'feedback' => 'This is a classic "smishing" attempt. Postal services do not send text messages with unverified HTTP domains asking for tiny payments.',
            ],
            [
                'key' => 'sys_maintenance', 
                'title' => 'Scheduled System Downtime', 
                'type' => 'email',
                'sender_name' => 'IT Infrastructure', 
                'sender_email' => 'infrastructure@company.com', 
                'subject' => 'Scheduled Maintenance: Friday 10 PM',
                'body' => '<p>Team,</p><p>The main database and intranet services will be offline for routine maintenance this Friday at 10 PM EST. No action is required on your part.</p><p>Thank you for your patience.</p>',
                'is_phishing' => false,
                'malicious_zone' => null, 
                'feedback' => 'This was a safe, informational email from internal IT. There were no suspicious links or urgent calls to action.',
            ],
            [
                'key' => 'o365_alert', 
                'title' => 'Unusual Sign-in Activity', 
                'type' => 'email',
                'sender_name' => 'Microsoft Security', 
                'sender_email' => 'alerts@microsoft-security-team.com', 
                'subject' => 'Security Alert: New Sign-in from Russia',
                'body' => '<p>We detected an unusual sign-in from a new device in Moscow, RU.</p><p>If this wasn\'t you, please report the user and secure your account immediately.</p><p><a href="#">Report User</a></p><p><span style="color:#94a3b8;font-size:12px;">Link destination: https://microsoft-security-team.com/auth/login</span></p>',
                'is_phishing' => true,
                'malicious_zone' => 'zone2', // Sender email is spoofed
                'feedback' => 'The sender address "microsoft-security-team.com" is a fake domain. Official Microsoft security alerts come from microsoft.com.',
            ],
            [
                'key' => 'colleague_late', 
                'title' => 'Running Late', 
                'type' => 'sms',
                'sender_name' => 'Sarah (Marketing)', 
                'sender_email' => null, 
                'subject' => null,      
                'body' => 'Hey! I\'m stuck in terrible traffic. I might be 5-10 mins late to the 10 AM standup. Can you let Tom know for me?',
                'is_phishing' => false,
                'malicious_zone' => null, 
                'feedback' => 'This was a legitimate text message from a verified colleague with no malicious intent.',
            ],
            [
                'key' => 'fake_invoice', 
                'title' => 'Overdue Invoice', 
                'type' => 'email',
                'sender_name' => 'Billing Department', 
                'sender_email' => 'billing@trusted-vendor.com', 
                'subject' => 'OVERDUE: Invoice #89932',
                'body' => '<p>Please find attached the heavily overdue invoice for last month\'s consultation services.</p><p>Failure to pay within 24 hours will result in legal action.</p><p>Attachment: <a href="#">Invoice_89932.pdf.exe</a></p>',
                'is_phishing' => true,
                'malicious_zone' => 'zone3', // The body has a malware payload
                'feedback' => 'The attachment was disguised as a PDF but was actually a malicious executable file (.exe).',
            ]
        ];

        foreach ($scenarios as $scenario) {
            PhishingScenario::updateOrCreate(
                ['key' => $scenario['key']], // Check if it exists by key
                $scenario                    // Update or create with this data
            );
        }
    }
}