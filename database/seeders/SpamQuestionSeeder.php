<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Question;

class SpamQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $scenarios = [
            // 1. Advance-Fee Scam (Spam)
            ['module' => 'spam', 'type' => 'spam', 'key' => 'inheritance_scam', 'title' => 'Urgent: Inheritance Notification', 'sender_name' => 'Barrister Williams', 'sender_email' => 'legal-dept-office@claims-center.net', 'subject' => 'Unclaimed Funds Notification', 
            'body' => "Dear Beneficiary,\n\nWe have identified a sum of $10.5M USD left by your distant relative. Please provide your bank details to proceed with the transfer.\n\nRegards,\nBarrister Williams", 
            'options' => json_encode([['text' => 'Flag as Junk', 'result' => 'correct', 'feedback' => 'Correct! This is a classic 419 advance-fee scam.'], ['text' => 'Allow to Inbox', 'result' => 'incorrect', 'feedback' => 'Warning! This is a scam. Legitimate legal firms do not contact beneficiaries via random Gmail-style domains.']])],
            
            // 2. The Unsubscribe Trap (Spam)
            ['module' => 'spam', 'type' => 'spam', 'key' => 'unsubscribe_trap', 'title' => 'Daily Newsletter #402', 'sender_name' => 'FlashSales Daily', 'sender_email' => 'no-reply@discount-hunterz.biz', 'subject' => 'Your 90% Discount is Expiring!', 
            'body' => "Check out today’s hot deals!\n\nBuy 1 get 1 free on all electronics.\n\nTo stop receiving these emails, click here to Unsubscribe.", 
            'options' => json_encode([['text' => 'Flag as Junk', 'result' => 'correct', 'feedback' => 'Good eye. "Unsubscribe" links in unknown spam often verify your email is active.'], ['text' => 'Allow to Inbox', 'result' => 'incorrect', 'feedback' => 'This is low-quality marketing spam. Allowing this clutters the inbox.']])],

            // 3. Legitimate Internal Memo (Ham)
            ['module' => 'spam', 'type' => 'spam', 'key' => 'legit_it_memo', 'title' => 'IT Maintenance Notice', 'sender_name' => 'University IT Services', 'sender_email' => 'it-helpdesk@university.edu', 'subject' => 'Scheduled Blackboard Maintenance', 
            'body' => "Dear Students and Staff,\n\nPlease be advised that the Blackboard portal will undergo scheduled maintenance this Sunday from 2:00 AM to 4:00 AM. \n\nNo action is required on your part.", 
            'options' => json_encode([['text' => 'Flag as Junk', 'result' => 'incorrect', 'feedback' => 'Error! You just blocked an important internal university memo. Always check the sender domain.'], ['text' => 'Allow to Inbox', 'result' => 'correct', 'feedback' => 'Correct. The sender domain matches the organization perfectly.']])],

            // 4. Storage Quota Phish (Phishing)
            ['module' => 'spam', 'type' => 'spam', 'key' => 'storage_quota_phish', 'title' => 'Mailbox Quota Warning', 'sender_name' => 'Admin System', 'sender_email' => 'admin@server-mail-quota-alerts.com', 'subject' => 'ACTION REQUIRED: Mailbox 98% Full', 
            'body' => "Your mailbox has reached 98% of its 50GB limit.\n\nYou will be locked out of sending or receiving new messages in 12 hours.\n\nClick the link below to verify your account:\nhttp://verify-storage-update.net/login", 
            'options' => json_encode([['text' => 'Flag as Junk', 'result' => 'correct', 'feedback' => 'Excellent. This is a common tactic to steal login credentials.'], ['text' => 'Allow to Inbox', 'result' => 'incorrect', 'feedback' => 'Breach detected! This is a credential-harvesting phishing attack disguised as a system alert.']])],

            // 5. Legitimate Peer Communication (Ham)
            ['module' => 'spam', 'type' => 'spam', 'key' => 'legit_peer_email', 'title' => 'Lecture Notes Request', 'sender_name' => 'Sarah Jenkins', 'sender_email' => 's.jenkins@university.edu', 'subject' => 'Notes from Tuesday Seminar?', 
            'body' => "Hey!\n\nI was out sick on Tuesday and missed Dr. Ahmad's lecture on Network Topologies. \n\nDo you mind sending over your notes when you have a chance? \n\nThanks,\nSarah", 
            'options' => json_encode([['text' => 'Flag as Junk', 'result' => 'incorrect', 'feedback' => 'Error! You blocked a legitimate request from a classmate.'], ['text' => 'Allow to Inbox', 'result' => 'correct', 'feedback' => 'Correct. This is a safe, peer-to-peer communication.']])],

            // 6. Fake Invoice / Malware Dropper (Malware/Spam)
            ['module' => 'spam', 'type' => 'spam', 'key' => 'fake_invoice_malware', 'title' => 'Overdue Invoice #99812', 'sender_name' => 'QuickBooks Billing', 'sender_email' => 'accounting@qb-secure-invoicing-portal.biz', 'subject' => 'OVERDUE: Invoice #99812 attached', 
            'body' => "Dear Customer,\n\nYour account is severely past due. You currently owe $450.00 for services rendered last month.\n\nPlease download and review the attached PDF statement [Invoice_99812.pdf.exe] immediately.", 
            'options' => json_encode([['text' => 'Flag as Junk', 'result' => 'correct', 'feedback' => 'Great catch. The "PDF" is actually a hidden executable (.exe) file.'], ['text' => 'Allow to Inbox', 'result' => 'incorrect', 'feedback' => 'Critical Failure! The attached file was a disguised executable file (.exe) which drops malware.']])],

            // 7. CEO Fraud / BEC (Phishing)
            ['module' => 'spam', 'type' => 'spam', 'key' => 'ceo_fraud', 'title' => 'Urgent Task', 'sender_name' => 'Dean of Faculty', 'sender_email' => 'dean.faculty.edu@gmail.com', 'subject' => 'Are you at your desk?', 
            'body' => "I am in a meeting and cannot take calls. I need you to purchase 5 Apple Gift Cards for a faculty event right now.\n\nPlease reply immediately so I can give you the instructions. I will reimburse you.", 
            'options' => json_encode([['text' => 'Flag as Junk', 'result' => 'correct', 'feedback' => 'Correct. This is a Business Email Compromise (BEC). High-ranking staff do not use Gmail to ask for gift cards.'], ['text' => 'Allow to Inbox', 'result' => 'incorrect', 'feedback' => 'Warning! You fell for CEO Fraud. Always verify urgent financial requests out-of-band.']])],

            // 8. Legitimate Notification (Ham)
            ['module' => 'spam', 'type' => 'spam', 'key' => 'github_alert', 'title' => 'GitHub Security', 'sender_name' => 'GitHub', 'sender_email' => 'noreply@github.com', 'subject' => '[GitHub] A new public key was added', 
            'body' => "A new SSH key was added to your account.\n\nIf you did this, you can safely ignore this email.\nIf you did not do this, please secure your account immediately.\n\nThanks,\nThe GitHub Team", 
            'options' => json_encode([['text' => 'Flag as Junk', 'result' => 'incorrect', 'feedback' => 'Error! You blocked an important legitimate security alert from a known vendor.'], ['text' => 'Allow to Inbox', 'result' => 'correct', 'feedback' => 'Correct. This is a standard, automated security notification from a trusted domain.']])],

            // 9. Fake Social Media Phish (Phishing)
            ['module' => 'spam', 'type' => 'spam', 'key' => 'linkedin_phish', 'title' => 'Security Alert', 'sender_name' => 'Linkedln Security', 'sender_email' => 'alerts@linkediin-security.com', 'subject' => 'Suspicious Login Attempt', 
            'body' => "We detected a login to your account from Russia.\n\nClick the secure link below to reset your password and secure your profile:\n\nwww.linkedin-account-recovery-portal.net", 
            'options' => json_encode([['text' => 'Flag as Junk', 'result' => 'correct', 'feedback' => 'Good spot. The sender domain uses a typo ("linkediin") and the URL is fraudulent.'], ['text' => 'Allow to Inbox', 'result' => 'incorrect', 'feedback' => 'Breach detected! This is a credential harvesting attack disguised as a security alert.']])],

            // 10. The Classic Lottery Scam (Spam)
            ['module' => 'spam', 'type' => 'spam', 'key' => 'lottery_scam', 'title' => 'Congratulations!', 'sender_name' => 'Global Lottery Board', 'sender_email' => 'winner@globallottery.org', 'subject' => 'YOU WON $5,000,000!', 
            'body' => "Congratulations!\n\nYour email address was selected randomly to win $5,000,000 in the Global Web Lottery.\n\nTo claim your prize, please reply with your Full Name, Address, and a processing fee of $250.", 
            'options' => json_encode([['text' => 'Flag as Junk', 'result' => 'correct', 'feedback' => 'Correct. No legitimate lottery asks for an upfront fee to claim a prize.'], ['text' => 'Allow to Inbox', 'result' => 'incorrect', 'feedback' => 'Warning! This is an obvious scam. If you didn\'t enter a lottery, you didn\'t win one.']])],

            // 11. Fake IT Credential Harvest (Phishing)
            ['module' => 'spam', 'type' => 'spam', 'key' => 'pwd_expiry_phish', 'title' => 'Password Expiry Phish', 'sender_name' => 'IT Helpdesk', 'sender_email' => 'admin@it-support-portal-01.com', 'subject' => 'URGENT: Password Expiry Notification', 
            'body' => "Your corporate network password will expire in 2 hours.\n\nPlease click the link below to verify your current credentials and retain your access.\n\nhttp://it-support-portal-01.com/auth/login", 
            'options' => json_encode([['text' => 'Flag as Junk', 'result' => 'correct', 'feedback' => 'Excellent catch. The sender domain is spoofed, and IT will never ask you to verify credentials via an external unencrypted link.'], ['text' => 'Allow to Inbox', 'result' => 'incorrect', 'feedback' => 'You allowed a credential harvesting phishing email through. Look closely at the non-corporate sender email address!']])],

            // 12. Legitimate Internal HR Update (Ham)
            ['module' => 'spam', 'type' => 'spam', 'key' => 'legit_hr_update', 'title' => 'HR Holiday Schedule', 'sender_name' => 'HR Department', 'sender_email' => 'hr@university.edu', 'subject' => 'Updated Q3 Holiday Schedule', 
            'body' => "Hi team,\n\nAttached is the updated holiday schedule for Q3. Please note that the floating holiday has been moved to August 15th.\n\nLet me know if you have any questions.\n\nBest,\nHuman Resources", 
            'options' => json_encode([['text' => 'Flag as Junk', 'result' => 'incorrect', 'feedback' => 'You blocked a legitimate internal communication. The sender address is internal and the request is standard.'], ['text' => 'Allow to Inbox', 'result' => 'correct', 'feedback' => 'Good. This is a standard internal email from a trusted corporate domain with no suspicious links or requests.']])],

            // 13. Failed Delivery Malware Dropper (Malware)
            ['module' => 'spam', 'type' => 'spam', 'key' => 'failed_delivery_malware', 'title' => 'Failed Delivery Malware', 'sender_name' => 'Express Courier Delivery', 'sender_email' => 'tracking@express-courier-post.net', 'subject' => 'Failed Delivery Attempt - Action Required', 
            'body' => "Dear Customer,\n\nYour package could not be delivered today because no one was present to sign for it.\n\nPlease download and print the attached shipping label [Label_9918.pdf.exe] and bring it to your local depot to claim your package.", 
            'options' => json_encode([['text' => 'Flag as Junk', 'result' => 'correct', 'feedback' => 'Perfect. The attachment is a disguised executable file (.pdf.exe), a classic malware delivery method, paired with artificial urgency.'], ['text' => 'Allow to Inbox', 'result' => 'incorrect', 'feedback' => 'System compromised! The attachment was an executable file (.exe) disguised as a PDF. Always check file extensions.']])],

            // 14. Legitimate Internal Project Notes (Ham)
            ['module' => 'spam', 'type' => 'spam', 'key' => 'legit_project_notes', 'title' => 'Project Kickoff Notes', 'sender_name' => 'David Chen', 'sender_email' => 'd.chen@university.edu', 'subject' => 'Project Kickoff Meeting Notes', 
            'body' => "Hey everyone,\n\nThanks for attending the kickoff. I've uploaded the meeting minutes to our internal SharePoint drive here: \n\nhttps://university.sharepoint.com/project-alpha/notes\n\nCheers,\nDavid", 
            'options' => json_encode([['text' => 'Flag as Junk', 'result' => 'incorrect', 'feedback' => 'You blocked a safe internal email. The link points to a legitimate internal SharePoint domain.'], ['text' => 'Allow to Inbox', 'result' => 'correct', 'feedback' => 'Correct. Sender is internal and the URL points to a verified corporate SharePoint domain.']])],

            // 15. Fake Microsoft Security Alert (Phishing)
            ['module' => 'spam', 'type' => 'spam', 'key' => 'ms365_fake_alert', 'title' => 'M365 Fake Alert', 'sender_name' => 'Microsoft 365', 'sender_email' => 'no-reply@security-alerts-ms.com', 'subject' => 'Unusual sign-in activity', 
            'body' => "We detected something unusual about a recent sign-in to your account.\n\nLocation: Moscow, Russia\nIP Address: 192.168.1.1\n\nIf this wasn't you, please secure your account immediately:\n\nhttp://security-alerts-ms.com/secure-account", 
            'options' => json_encode([['text' => 'Flag as Junk', 'result' => 'correct', 'feedback' => 'Great job. This is a scare-tactic phishing email. The sender domain is not a legitimate Microsoft domain.'], ['text' => 'Allow to Inbox', 'result' => 'incorrect', 'feedback' => 'Breach detected. This was a fake security alert designed to steal your password. The sender domain was spoofed.']])]
        ];

        // This ensures if you run it twice, it updates existing questions instead of duplicating them
        foreach ($scenarios as $s) {
            Question::updateOrCreate(
                ['key' => $s['key']], 
                $s
            );
        }
    }
}