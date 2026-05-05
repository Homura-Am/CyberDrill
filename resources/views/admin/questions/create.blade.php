<!DOCTYPE html>
<html>
<head>
    <title>Create Scenario</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        input, select, textarea { width: 100%; padding: 10px; margin-bottom: 15px; background: var(--bg-body); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 8px; }
        .option-box { background: rgba(255,255,255,0.05); padding: 15px; border-radius: 8px; margin-bottom: 10px; }
    </style>
</head>
<body class="container" style="padding: 2rem;">
    @include('partials.navbar')

    <h1>Create New Scenario</h1>
    
    <form action="{{ route('admin.questions.store') }}" method="POST">
        @csrf
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label>Unique Key (e.g., netflix_scam)</label>
                <input type="text" name="key" required>
            </div>
            <div>
                <label>Title</label>
                <input type="text" name="title" required>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label>Type</label>
                <select name="type">
                    <option value="email">Email</option>
                    <option value="sms">SMS</option>
                </select>
            </div>
            <div>
                <label>Sender Name</label>
                <input type="text" name="sender_name" required>
            </div>
        </div>

        <label>Sender Email (Optional for SMS)</label>
        <input type="text" name="sender_email">

        <label>Subject (Optional for SMS)</label>
        <input type="text" name="subject">

        <label>Body (Supports HTML)</label>
        <textarea name="body" rows="5" required></textarea>

        <h3>Answer Options</h3>
        <div id="options-container">
            </div>
        <button type="button" onclick="addOption()" class="btn btn-outline" style="margin-bottom: 20px;">+ Add Option</button>

        <br>
        <button type="submit" class="btn btn-primary">Save Scenario</button>
    </form>

    <script>
        function addOption() {
            const index = document.querySelectorAll('.option-box').length;
            const html = `
                <div class="option-box">
                    <label>Button Text</label>
                    <input type="text" name="options[${index}][text]" required placeholder="e.g. Report Phishing">
                    
                    <label>Result</label>
                    <select name="options[${index}][result]">
                        <option value="correct">Correct ✅</option>
                        <option value="incorrect">Incorrect ❌</option>
                        <option value="neutral">Neutral 🤔</option>
                    </select>
                    
                    <label>Feedback Message</label>
                    <input type="text" name="options[${index}][feedback]" required placeholder="e.g. Great job! This was a scam.">
                </div>
            `;
            document.getElementById('options-container').insertAdjacentHTML('beforeend', html);
        }
        // Add 3 default options
        addOption(); addOption(); addOption();
    </script>
</body>
</html>