<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Scenario</title>
    <style>
        :root { --bg-dark: #0f172a; --panel-bg: #1e293b; --text-main: #f8fafc; --primary: #3b82f6; --border: #334155; }
        body { background-color: var(--bg-dark); color: var(--text-main); font-family: sans-serif; padding: 20px; }
        .form-container { background-color: var(--panel-bg); padding: 30px; border-radius: 8px; max-width: 800px; margin: 0 auto; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #cbd5e1; font-size: 14px; }
        input, select, textarea { width: 100%; padding: 10px; background-color: #0f172a; color: white; border: 1px solid var(--border); border-radius: 4px; box-sizing: border-box; }
        .btn-submit { background-color: var(--primary); color: white; padding: 12px 15px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; width: 100%; margin-top: 20px; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .options-box { border: 1px dashed var(--border); padding: 15px; margin-top: 10px; }
        .btn-back { color: var(--text-muted); text-decoration: none; margin-bottom: 15px; display: inline-block; }
    </style>
</head>
<body>

    <div class="form-container">
        <a href="{{ route('questions.index') }}" class="btn-back">← Back to Scenarios</a>
        <h2>Edit {{ ucfirst($module) }} Scenario (ID: {{ $scenario->id }})</h2>
        
        <form action="{{ route('questions.update', $scenario->id) }}" method="POST">
            @csrf
            @method('PUT') <input type="hidden" name="module_type" value="{{ $module }}">

            @if($module == 'phishing')
                <div class="grid-2">
                    <div class="form-group"><label>Key</label><input type="text" name="key" value="{{ $scenario->key }}"></div>
                    <div class="form-group"><label>Title</label><input type="text" name="title" value="{{ $scenario->title }}"></div>
                    <div class="form-group"><label>Type</label><select name="type"><option value="email" {{ $scenario->type == 'email' ? 'selected' : '' }}>Email</option><option value="sms" {{ $scenario->type == 'sms' ? 'selected' : '' }}>SMS</option></select></div>
                    <div class="form-group"><label>Sender Name</label><input type="text" name="sender_name" value="{{ $scenario->sender_name }}"></div>
                    <div class="form-group"><label>Sender Email</label><input type="email" name="sender_email" value="{{ $scenario->sender_email }}"></div>
                    <div class="form-group"><label>Subject</label><input type="text" name="subject" value="{{ $scenario->subject }}"></div>
                </div>
                <div class="form-group"><label>Body (HTML Allowed)</label><textarea name="body" rows="4">{{ $scenario->body }}</textarea></div>
                <div class="grid-2">
                    <div class="form-group"><label>Is Phishing?</label><select name="is_phishing"><option value="1" {{ $scenario->is_phishing ? 'selected' : '' }}>True</option><option value="0" {{ !$scenario->is_phishing ? 'selected' : '' }}>False</option></select></div>
                    <div class="form-group"><label>Malicious Zone</label><input type="text" name="malicious_zone" value="{{ $scenario->malicious_zone }}"></div>
                </div>
                <div class="form-group"><label>Feedback / Explanation</label><textarea name="feedback" rows="2">{{ $scenario->feedback }}</textarea></div>

            @elseif($module == 'malware')
                <div class="grid-2">
                    <div class="form-group"><label>Title</label><input type="text" name="title" value="{{ $scenario->title }}"></div>
                    <div class="form-group"><label>File Name</label><input type="text" name="filename" value="{{ $scenario->filename }}"></div>
                    <div class="form-group"><label>File Type</label><input type="text" name="filetype" value="{{ $scenario->filetype }}"></div>
                    <div class="form-group"><label>Publisher</label><input type="text" name="publisher" value="{{ $scenario->publisher }}"></div>
                </div>
                <div class="form-group"><label>Description</label><textarea name="description" rows="2">{{ $scenario->description }}</textarea></div>

            @elseif($module == 'spam')
                <div class="grid-2">
                    <div class="form-group"><label>Key</label><input type="text" name="key" value="{{ $scenario->key }}"></div>
                    <div class="form-group"><label>Title</label><input type="text" name="title" value="{{ $scenario->title }}"></div>
                    <div class="form-group"><label>Type</label><select name="type"><option value="spam" {{ $scenario->type == 'spam' ? 'selected' : '' }}>Spam</option><option value="ham" {{ $scenario->type == 'ham' ? 'selected' : '' }}>Ham</option></select></div>
                    <div class="form-group"><label>Sender Name</label><input type="text" name="sender_name" value="{{ $scenario->sender_name }}"></div>
                    <div class="form-group"><label>Sender Email</label><input type="email" name="sender_email" value="{{ $scenario->sender_email }}"></div>
                    <div class="form-group"><label>Subject</label><input type="text" name="subject" value="{{ $scenario->subject }}"></div>
                </div>
                <div class="form-group"><label>Body Text</label><textarea name="body" rows="4">{{ $scenario->body }}</textarea></div>
            @endif

            @if($module == 'malware' || $module == 'spam')
                <div class="options-box">
                    <h3 style="color:var(--primary); margin-top:0;">Edit Button Options</h3>
                    
                    <h4>Option 1</h4>
                    <div class="grid-2">
                        <div class="form-group"><label>Button Text</label><input type="text" name="opt1_text" value="{{ $scenario->options[0]['text'] ?? '' }}"></div>
                        <div class="form-group"><label>Result</label><select name="opt1_result"><option value="correct" {{ ($scenario->options[0]['result'] ?? '') == 'correct' ? 'selected' : '' }}>Correct</option><option value="incorrect" {{ ($scenario->options[0]['result'] ?? '') == 'incorrect' ? 'selected' : '' }}>Incorrect</option></select></div>
                    </div>
                    <div class="form-group"><label>Feedback if clicked</label><input type="text" name="opt1_feedback" value="{{ $scenario->options[0]['feedback'] ?? '' }}"></div>

                    <h4>Option 2</h4>
                    <div class="grid-2">
                        <div class="form-group"><label>Button Text</label><input type="text" name="opt2_text" value="{{ $scenario->options[1]['text'] ?? '' }}"></div>
                        <div class="form-group"><label>Result</label><select name="opt2_result"><option value="correct" {{ ($scenario->options[1]['result'] ?? '') == 'correct' ? 'selected' : '' }}>Correct</option><option value="incorrect" {{ ($scenario->options[1]['result'] ?? '') == 'incorrect' ? 'selected' : '' }}>Incorrect</option></select></div>
                    </div>
                    <div class="form-group"><label>Feedback if clicked</label><input type="text" name="opt2_feedback" value="{{ $scenario->options[1]['feedback'] ?? '' }}"></div>
                </div>
            @endif

            <button type="submit" class="btn-submit">Update Scenario</button>
        </form>
    </div>

</body>
</html>