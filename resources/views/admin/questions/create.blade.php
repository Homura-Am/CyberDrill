<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Scenario</title>
    <style>
        :root { --bg-dark: #0f172a; --panel-bg: #1e293b; --text-main: #f8fafc; --primary: #3b82f6; --border: #334155; }
        body { background-color: var(--bg-dark); color: var(--text-main); font-family: sans-serif; padding: 20px; }
        .form-container { background-color: var(--panel-bg); padding: 30px; border-radius: 8px; max-width: 800px; margin: 0 auto; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #cbd5e1; font-size: 14px; }
        input, select, textarea { width: 100%; padding: 10px; background-color: #0f172a; color: white; border: 1px solid var(--border); border-radius: 4px; box-sizing: border-box; }
        .btn-submit { background-color: var(--primary); color: white; padding: 12px 15px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; width: 100%; margin-top: 20px; }
        .dynamic-section { display: none; padding: 15px; background: rgba(0,0,0,0.2); border-left: 4px solid var(--primary); margin-bottom: 20px; border-radius: 4px; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .options-box { border: 1px dashed var(--border); padding: 15px; margin-top: 10px; }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>Add New Scenario to Database</h2>
        
        <form action="{{ route('questions.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label>Select Module</label>
                <select name="module_type" id="module_type" onchange="toggleFields()" required>
                    <option value="" disabled selected>-- Choose --</option>
                    <option value="phishing">Phishing</option>
                    <option value="malware">Malware</option>
                    <option value="spam">Spam</option>
                </select>
            </div>

            <div id="phishing_fields" class="dynamic-section">
                <h3>Phishing Scenario Details</h3>
                <div class="grid-2">
                    <div class="form-group"><label>Key</label><input type="text" name="key" placeholder="e.g. ceo_fraud"></div>
                    <div class="form-group"><label>Title</label><input type="text" name="title" placeholder="e.g. Urgent Wire Transfer"></div>
                    <div class="form-group">
                        <label>Type</label>
                        <select name="type"><option value="email">Email</option><option value="sms">SMS</option></select>
                    </div>
                    <div class="form-group"><label>Sender Name</label><input type="text" name="sender_name"></div>
                    <div class="form-group"><label>Sender Email</label><input type="email" name="sender_email"></div>
                    <div class="form-group"><label>Subject</label><input type="text" name="subject"></div>
                </div>
                <div class="form-group"><label>Body (HTML Allowed)</label><textarea name="body" rows="4"></textarea></div>
                
                <div class="grid-2">
                    <div class="form-group">
                        <label>Is Phishing?</label>
                        <select name="is_phishing"><option value="1">True (1)</option><option value="0">False (0)</option></select>
                    </div>
                    <div class="form-group">
                        <label>Malicious Zone</label>
                        <select name="malicious_zone"><option value="">None (Safe)</option><option value="zone1">Zone 1</option><option value="zone2">Zone 2</option><option value="zone3">Zone 3</option></select>
                    </div>
                </div>
                <div class="form-group"><label>Feedback / Explanation</label><textarea name="feedback" rows="2"></textarea></div>
            </div>

            <div id="malware_fields" class="dynamic-section">
                <h3>Malware Scenario Details</h3>
                <div class="grid-2">
                    <div class="form-group"><label>Title</label><input type="text" name="title" placeholder="e.g. Suspicious PDF"></div>
                    <div class="form-group"><label>File Name</label><input type="text" name="filename" placeholder="e.g. invoice.pdf.exe"></div>
                    <div class="form-group"><label>File Type</label><input type="text" name="filetype" placeholder="e.g. Application (.exe)"></div>
                    <div class="form-group"><label>Publisher</label><input type="text" name="publisher" placeholder="e.g. Unknown"></div>
                </div>
                <div class="form-group"><label>Description</label><textarea name="description" rows="2"></textarea></div>
            </div>

            <div id="spam_fields" class="dynamic-section">
                <h3>Spam Scenario Details</h3>
                <div class="grid-2">
                    <div class="form-group"><label>Key</label><input type="text" name="key" placeholder="e.g. inheritance_scam"></div>
                    <div class="form-group"><label>Title</label><input type="text" name="title"></div>
                    <div class="form-group">
                        <label>Type</label>
                        <select name="type"><option value="spam">Spam (Malicious)</option><option value="ham">Ham (Safe)</option></select>
                    </div>
                    <div class="form-group"><label>Sender Name</label><input type="text" name="sender_name"></div>
                    <div class="form-group"><label>Sender Email</label><input type="email" name="sender_email"></div>
                    <div class="form-group"><label>Subject</label><input type="text" name="subject"></div>
                </div>
                <div class="form-group"><label>Body Text</label><textarea name="body" rows="4"></textarea></div>
            </div>

            <div id="json_options_fields" class="dynamic-section options-box">
                <h3 style="color:var(--primary);">Button Options Configuration</h3>
                <p style="font-size:12px; color:var(--text-muted);">Configure the two buttons the user will see.</p>
                
                <h4>Option 1</h4>
                <div class="grid-2">
                    <div class="form-group"><label>Button Text</label><input type="text" name="opt1_text" placeholder="e.g. Flag as Junk"></div>
                    <div class="form-group">
                        <label>Result</label>
                        <select name="opt1_result"><option value="correct">Correct</option><option value="incorrect">Incorrect</option></select>
                    </div>
                </div>
                <div class="form-group"><label>Feedback if clicked</label><input type="text" name="opt1_feedback"></div>

                <h4>Option 2</h4>
                <div class="grid-2">
                    <div class="form-group"><label>Button Text</label><input type="text" name="opt2_text" placeholder="e.g. Allow to Inbox"></div>
                    <div class="form-group">
                        <label>Result</label>
                        <select name="opt2_result"><option value="incorrect">Incorrect</option><option value="correct">Correct</option></select>
                    </div>
                </div>
                <div class="form-group"><label>Feedback if clicked</label><input type="text" name="opt2_feedback"></div>
            </div>

            <button type="submit" class="btn-submit">Save to Database</button>
        </form>
    </div>

    <script>
        function toggleFields() {
            // Reset all sections
            document.getElementById('phishing_fields').style.display = 'none';
            document.getElementById('malware_fields').style.display = 'none';
            document.getElementById('spam_fields').style.display = 'none';
            document.getElementById('json_options_fields').style.display = 'none';

            // Show relevant sections
            let type = document.getElementById('module_type').value;
            
            if (type === 'phishing') {
                document.getElementById('phishing_fields').style.display = 'block';
                // Note: Phishing doesn't use the JSON options array in your seeder
            } else if (type === 'malware') {
                document.getElementById('malware_fields').style.display = 'block';
                document.getElementById('json_options_fields').style.display = 'block'; // Needs JSON
            } else if (type === 'spam') {
                document.getElementById('spam_fields').style.display = 'block';
                document.getElementById('json_options_fields').style.display = 'block'; // Needs JSON
            }
        }
    </script>
</body>
</html>