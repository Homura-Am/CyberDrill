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
                'malicious_zone' => 'zone1, zone2, zone3', // Subject, Sender, and Body are all red flags
                'feedback' => 'This is a classic Business Email Compromise (BEC) attack.',
                'feedback_zone1' => "The subject uses urgent language ('URGENT:') designed to create panic and bypass critical thinking.",
                'feedback_zone2' => "The sender domain 'company-exec.net' is a spoofed variation. Our official domain is just 'company.com'.",
                'feedback_zone3' => "The request for an immediate wire transfer to a 'new vendor' without standard verification procedures is highly suspicious.",
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
                'feedback_zone1' => 'The subject is a standard, expected meeting invitation.',
                'feedback_zone2' => 'The email originates from the verified, official zoom.us domain.',
                'feedback_zone3' => 'The meeting link correctly points to the official Zoom web infrastructure.',
            ],
            [
                'key' => 'bank_sms', 
                'title' => 'Bank Fraud Alert', 
                'type' => 'sms',
                'sender_name' => 'BankAlert', 
                'sender_email' => null, 
                'subject' => null,      
                'body' => 'ALERT: A charge of $4,299.00 was attempted on your card. Cancel immediately at: http://secure-bank-auth.net',
                'is_phishing' => true,
                'malicious_zone' => 'zone2', // SMS only has Zone 1 (Sender) and Zone 2 (Body). The body has the bad link.
                'feedback' => 'This is a dangerous Smishing (SMS Phishing) attempt.',
                'feedback_zone1' => 'While the sender name says "BankAlert", SMS sender IDs can be easily spoofed by attackers.',
                'feedback_zone2' => 'The message creates false urgency regarding a large charge and includes an unsecure HTTP link, which real banks never use.',
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
                'malicious_zone' => 'zone3', // Only the link is bad. The sender looks internal.
                'feedback' => 'Attackers often spoof internal HR emails to harvest employee logins.',
                'feedback_zone1' => 'The subject line is plausible and matches normal corporate communications.',
                'feedback_zone2' => 'The sender address appears correct, meaning the attacker spoofed the display address.',
                'feedback_zone3' => 'Hovering over the "Acknowledge Policy" link reveals it points to an untrusted, external ".xyz" domain.',
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
                'malicious_zone' => 'zone1, zone3', // Urgent subject + bad link
                'feedback' => 'IT departments will never ask you to click a button to "keep" your current password.',
                'feedback_zone1' => 'The subject implies a strict 24-hour expiration, a common scare tactic to force immediate action.',
                'feedback_zone2' => 'The sender address is spoofed to look like internal IT.',
                'feedback_zone3' => 'The "Keep Password" button points to a fraudulent external domain designed to steal your credentials.',
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
                'malicious_zone' => 'zone2', // SMS body link
                'feedback' => 'This is a classic package delivery scam.',
                'feedback_zone1' => 'The generic "PostService" sender ID is a red flag, as legitimate couriers usually use their official brand name.',
                'feedback_zone2' => 'Legitimate postal services do not request tiny payments via unverified HTTP links sent through text messages.',
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
                'feedback' => 'This was a safe, informational email from internal IT.',
                'feedback_zone1' => 'The subject is informative and lacks artificial urgency.',
                'feedback_zone2' => 'The sender address matches the official internal infrastructure team.',
                'feedback_zone3' => 'The body simply informs the user of an event and does not contain any suspicious links or demands for action.',
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
                'malicious_zone' => 'zone1, zone2, zone3', // Everything is a trap
                'feedback' => 'This is a high-quality forgery designed to look like an official Microsoft alert.',
                'feedback_zone1' => 'The subject line leverages fear by claiming a foreign entity has breached your account.',
                'feedback_zone2' => 'The sender email uses a fake domain ("microsoft-security-team.com"). Official alerts come directly from "microsoft.com".',
                'feedback_zone3' => 'The "Report User" link directs you to a fake login portal controlled by the attacker.',
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
                'feedback' => 'This was a legitimate text message from a verified colleague.',
                'feedback_zone1' => 'The sender is a known, saved contact.',
                'feedback_zone2' => 'The message body is a standard workplace communication with no links or strange requests.',
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
                'malicious_zone' => 'zone1, zone3', // Urgent subject + Malware attachment
                'feedback' => 'This email attempts to use fear of legal action to trick you into downloading malware.',
                'feedback_zone1' => 'The subject uses capitalized "OVERDUE" to intimidate the recipient into opening the file quickly.',
                'feedback_zone2' => 'The sender is spoofing a known vendor to gain trust.',
                'feedback_zone3' => 'The attached file is an executable (.exe) disguised as a PDF. Opening it will install malware on your machine.',
            ]
        ];

        foreach ($scenarios as $scenario) {
            PhishingScenario::updateOrCreate(
                ['key' => $scenario['key']], 
                $scenario                    
            );
        }
    }
}