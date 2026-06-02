<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Certificate of Completion | CyberDrill</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@700&family=Inter:wght@400;600&display=swap');

        body {
            background-color: #040914; /* Your cyber background */
            color: #fff;
            font-family: 'Inter', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .cert-container {
            background: #ffffff; /* White background for printing */
            color: #0f172a;
            width: 1000px;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 0 30px rgba(6, 182, 212, 0.4);
            position: relative;
            text-align: center;
        }

        /* Inner border */
        .cert-inner {
            border: 4px double #06b6d4;
            padding: 60px 40px;
            position: relative;
        }

        .cert-logo {
            font-size: 2rem;
            font-weight: 800;
            color: #06b6d4;
            letter-spacing: 2px;
            margin-bottom: 30px;
            text-transform: uppercase;
        }

        .cert-title {
            font-family: 'Cinzel', serif;
            font-size: 3.5rem;
            color: #0f172a;
            margin: 0 0 20px 0;
        }

        .cert-text {
            font-size: 1.2rem;
            color: #64748b;
            margin-bottom: 20px;
        }

        .cert-name {
            font-family: 'Cinzel', serif;
            font-size: 3rem;
            color: #06b6d4;
            border-bottom: 2px solid #e2e8f0;
            display: inline-block;
            padding: 0 40px 10px;
            margin-bottom: 30px;
        }

        .cert-desc {
            font-size: 1.1rem;
            line-height: 1.6;
            max-width: 700px;
            margin: 0 auto 50px;
            color: #334155;
        }

        .cert-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 50px;
            padding: 0 50px;
        }

        .signature-line {
            border-top: 1px solid #0f172a;
            width: 250px;
            padding-top: 10px;
            font-weight: 600;
        }

        /* Print Button - Hidden when printing */
        .print-btn {
            position: absolute;
            top: -60px;
            right: 0;
            background: #06b6d4;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.2s;
        }
        .print-btn:hover { background: #0891b2; }

        @media print {
            body { background: #fff; display: block; }
            .cert-container { box-shadow: none; width: 100%; margin: 0; padding: 20px; }
            .print-btn { display: none; }
        }
    </style>
</head>
<body>

    <div class="cert-container">
        <button class="print-btn" onclick="window.print()">🖨️ Print / Save as PDF</button>
        
        <div class="cert-inner">
            <div class="cert-logo">CyberDrill</div>
            <h1 class="cert-title">Certificate of Achievement</h1>
            <p class="cert-text">This is proudly presented to</p>
            
            <div class="cert-name">{{ $user->name }}</div>
            
            <p class="cert-desc">
                For successfully completing the comprehensive Cyber Security Simulation training program. The participant has demonstrated a high level of proficiency in identifying and mitigating modern cyber threats, including <strong>Phishing, Malware, and Spam</strong>.
            </p>

            <div class="cert-footer">
                <div>
                    <div class="signature-line">Date Awarded</div>
                    <p style="margin: 5px 0 0; color: #64748b;">{{ date('F j, Y') }}</p>
                </div>
                <div>
                    <div class="signature-line">CyberDrill Director</div>
                    <p style="margin: 5px 0 0; color: #64748b;">System Administrator</p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>