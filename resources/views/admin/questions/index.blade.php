<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Scenarios</title>
    <style>
        :root { --bg-dark: #0f172a; --panel-bg: #1e293b; --text-main: #f8fafc; --primary: #3b82f6; --border: #334155; }
        body { background-color: var(--bg-dark); color: var(--text-main); font-family: sans-serif; padding: 20px; max-width: 1000px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid var(--border); padding-bottom: 10px; }
        .header-left { display: flex; align-items: center; gap: 15px; }
        .header h1 { margin: 0; }
        .btn-primary { background-color: var(--primary); color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .btn-back { background-color: transparent; border: 1px solid var(--border); color: var(--text-main); padding: 8px 15px; text-decoration: none; border-radius: 5px; font-weight: bold; transition: background 0.2s; }
        .btn-back:hover { background-color: rgba(255, 255, 255, 0.05); }
        .success-msg { background: rgba(34, 197, 94, 0.2); color: #4ade80; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; background-color: var(--panel-bg); margin-bottom: 30px; border-radius: 8px; overflow: hidden; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid var(--border); }
        th { background-color: #0f172a; color: #94a3b8; font-size: 13px; text-transform: uppercase; }
        h2 { margin-top: 30px; color: #cbd5e1; font-size: 18px; }
    </style>
</head>
<body>

    <div class="header">
        <div class="header-left">
            <a href="{{ route('admin.dashboard') }}" class="btn-back">&larr; Back</a>
            <h1>Manage CyberDrill Scenarios</h1>
        </div>
        <a href="{{ route('questions.create') }}" class="btn-primary">+ Add New Scenario</a>
    </div>

    @if(session('success'))
        <div class="success-msg">{{ session('success') }}</div>
    @endif

    <h2>Phishing Scenarios</h2>
    <table>
        <tr><th>ID</th><th>Title</th><th>Sender Email</th><th>Type</th><th>Actions</th></tr>
        @forelse($phishingScenarios as $p)
            <tr>
                <td>{{ $p->id }}</td>
                <td>{{ $p->title }}</td>
                <td>{{ $p->sender_email }}</td>
                <td>{{ $p->is_phishing ? 'Malicious' : 'Safe' }}</td>
                <td style="display: flex; gap: 10px;">
                    <a href="{{ route('questions.edit', ['question' => $p->id, 'module' => 'phishing']) }}" style="color:var(--primary); text-decoration:none;">Edit</a>
                    <form action="{{ route('questions.destroy', $p->id) }}" method="POST" style="margin:0;">
                        @csrf @method('DELETE')
                        <input type="hidden" name="module" value="phishing">
                        <button type="submit" onclick="return confirm('Delete Phishing Scenario?');" style="background:none; border:none; color:#ef4444; cursor:pointer;">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5">No phishing scenarios found.</td></tr>
        @endforelse
    </table>

    <h2>Malware Scenarios</h2>
    <table>
        <tr><th>ID</th><th>Title</th><th>File Name</th><th>Publisher</th><th>Actions</th></tr>
        @forelse($malwareScenarios as $m)
            <tr>
                <td>{{ $m->id }}</td>
                <td>{{ $m->title }}</td>
                <td>{{ $m->filename }}</td>
                <td>{{ $m->publisher }}</td>
                <td style="display: flex; gap: 10px;">
                    <a href="{{ route('questions.edit', ['question' => $m->id, 'module' => 'malware']) }}" style="color:var(--primary); text-decoration:none;">Edit</a>
                    <form action="{{ route('questions.destroy', $m->id) }}" method="POST" style="margin:0;">
                        @csrf @method('DELETE')
                        <input type="hidden" name="module" value="malware">
                        <button type="submit" onclick="return confirm('Delete Malware Scenario?');" style="background:none; border:none; color:#ef4444; cursor:pointer;">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5">No malware scenarios found.</td></tr>
        @endforelse
    </table>

    <h2>Spam Scenarios</h2>
    <table>
        <tr><th>ID</th><th>Title</th><th>Sender Name</th><th>Type</th><th>Actions</th></tr>
        @forelse($Question as $s)
            <tr>
                <td>{{ $s->id }}</td>
                <td>{{ $s->title }}</td>
                <td>{{ $s->sender_name }}</td>
                <td>{{ $s->type }}</td>
                <td style="display: flex; gap: 10px;">
                    <a href="{{ route('questions.edit', ['question' => $s->id, 'module' => 'spam']) }}" style="color:var(--primary); text-decoration:none;">Edit</a>
                    <form action="{{ route('questions.destroy', $s->id) }}" method="POST" style="margin:0;">
                        @csrf @method('DELETE')
                        <input type="hidden" name="module" value="spam">
                        <button type="submit" onclick="return confirm('Delete Spam Scenario?');" style="background:none; border:none; color:#ef4444; cursor:pointer;">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5">No spam scenarios found.</td></tr>
        @endforelse
    </table>

</body>
</html>