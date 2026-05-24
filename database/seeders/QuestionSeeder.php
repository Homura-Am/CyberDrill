<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\MalwareScenario;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Nuke the old data to prevent duplicates
        MalwareScenario::truncate();

        // --- MALWARE SCENARIOS (8 TOTAL: 4 MALICIOUS, 4 SAFE) ---
        $malware = [
            // 1. MALICIOUS: Ransomware
            [
                'title' => 'Suspicious PDF Invoice',
                'filename' => 'Invoice_Final_Notice.pdf.exe',
                'filetype' => 'Application (.exe)',
                'publisher' => 'Unknown',
                'description' => 'Downloaded automatically after clicking a link in a "Unpaid Bill" email.',
                'options' => json_encode([
                    ['text' => 'Double-Click to Open', 'result' => 'incorrect', 'feedback' => 'INFECTION! This used a "double extension". Opening this .exe deployed Ransomware.'],
                    ['text' => 'Quarantine File', 'result' => 'correct', 'feedback' => 'Excellent! You spotted the hidden executable extension disguised as a PDF.']
                ])
            ],
            // 2. SAFE: Official Update
            [
                'title' => 'Windows Security Update',
                'filename' => 'KB5034765_Security_Patch.msu',
                'filetype' => 'Windows Update Patch',
                'publisher' => 'Microsoft Corporation',
                'description' => 'System-generated update found in the C:\Windows\SoftwareDistribution folder.',
                'options' => json_encode([
                    ['text' => 'Run Update', 'result' => 'correct', 'feedback' => 'Safe. This is a legitimate Microsoft security patch with a verified publisher.'],
                    ['text' => 'Delete File', 'result' => 'incorrect', 'feedback' => 'Oops! You deleted a critical security patch, leaving your system vulnerable.']
                ])
            ],
            // 3. MALICIOUS: Spyware
            [
                'title' => 'Flash Player Update',
                'filename' => 'Install_Flash_v32.msi',
                'filetype' => 'Windows Installer (.msi)',
                'publisher' => 'Not Verified',
                'description' => 'A pop-up on a movie streaming site prompted this download to "fix your video player".',
                'options' => json_encode([
                    ['text' => 'Run Installer', 'result' => 'incorrect', 'feedback' => 'INFECTION! Adobe Flash is dead. These "updates" are always fake and contain Spyware.'],
                    ['text' => 'Delete File', 'result' => 'correct', 'feedback' => 'Good catch! No modern site uses Flash; this was a classic malware dropper.']
                ])
            ],
            // 4. SAFE: Open Source Tool
            [
                'title' => 'VLC Media Player',
                'filename' => 'vlc-3.0.20-win64.exe',
                'filetype' => 'Application (.exe)',
                'publisher' => 'VideoLAN',
                'description' => 'Downloaded from the official videolan.org website to watch a project video.',
                'options' => json_encode([
                    ['text' => 'Install Tool', 'result' => 'correct', 'feedback' => 'Correct. This is a well-known, legitimate open-source tool from a verified source.'],
                    ['text' => 'Quarantine', 'result' => 'incorrect', 'feedback' => 'Unnecessary. This is a safe application. Always check the source URL.']
                ])
            ],
            // 5. MALICIOUS: Trojan/Miner
            [
                'title' => 'Cracked Software Keygen',
                'filename' => 'Photoshop_Crack_Keygen.zip',
                'filetype' => 'Compressed Archive (.zip)',
                'publisher' => 'Torrent Network',
                'description' => 'Downloaded from a peer-to-peer sharing site to bypass a software license.',
                'options' => json_encode([
                    ['text' => 'Extract & Run', 'result' => 'incorrect', 'feedback' => 'SYSTEM COMPROMISED. Pirated keygens almost always contain hidden trojans or miners.'],
                    ['text' => 'Scan with Antivirus', 'result' => 'correct', 'feedback' => 'Smart move. The scan flagged a Trojan inside the ZIP archive.']
                ])
            ],
            // 6. SAFE: Corporate Document
            [
                'title' => 'Employee Handbook',
                'filename' => 'IIUM_Staff_Handbook_2026.pdf',
                'filetype' => 'PDF Document',
                'publisher' => 'IIUM KICT',
                'description' => 'Downloaded from the internal University staff portal.',
                'options' => json_encode([
                    ['text' => 'Open Document', 'result' => 'correct', 'feedback' => 'Safe. This is a standard PDF document from your organization\'s verified portal.'],
                    ['text' => 'Report as Malware', 'result' => 'incorrect', 'feedback' => 'False Positive. This is a real document. Don\'t be over-paranoid with internal files!']
                ])
            ],
            // 7. MALICIOUS: Macro Virus
            [
                'title' => 'Urgent Financial Report',
                'filename' => 'Q3_Financial_Summary.docm',
                'filetype' => 'Macro-Enabled Word Document (.docm)',
                'publisher' => 'Unknown',
                'description' => 'Emailed by an unknown sender claiming to be from finance, asking you to "Enable Content" to view.',
                'options' => json_encode([
                    ['text' => 'Enable Macros & View', 'result' => 'incorrect', 'feedback' => 'SYSTEM COMPROMISED. Enabling macros allowed malicious VBA scripts to execute and download malware.'],
                    ['text' => 'Delete File', 'result' => 'correct', 'feedback' => 'Great job! Unsolicited macro-enabled documents are a primary delivery method for malware.']
                ])
            ],
            // 8. SAFE: Hardware Driver
            [
                'title' => 'Graphics Card Driver Update',
                'filename' => 'NVIDIA_GeForce_551_WHQL.exe',
                'filetype' => 'Application (.exe)',
                'publisher' => 'NVIDIA Corporation',
                'description' => 'Downloaded directly from the manufacturer\'s official website via their driver tool.',
                'options' => json_encode([
                    ['text' => 'Install Driver', 'result' => 'correct', 'feedback' => 'Safe. This is a digitally signed, WHQL-certified driver update from a legitimate vendor.'],
                    ['text' => 'Block Execution', 'result' => 'incorrect', 'feedback' => 'Unnecessary. This is a safe and digitally signed hardware update.']
                ])
            ]
        ];

        foreach ($malware as $m) {
            \App\Models\MalwareScenario::create($m);
        }
    }
}