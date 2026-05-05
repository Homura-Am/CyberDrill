<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Questions | CyberDrill</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Reusing your admin styles */
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .table-container { background: var(--bg-card); padding: 20px; border-radius: 12px; border: 1px solid var(--border-color); overflow-x: auto; }
        
        table { width: 100%; border-collapse: collapse; min-width: 600px; }
        th { text-align: left; padding: 12px; color: var(--text-muted); border-bottom: 1px solid var(--border-color); }
        td { padding: 12px; border-bottom: 1px solid rgba(255,255,255,0.05); color: var(--text-main); }
        tr:last-child td { border-bottom: none; }
        
        .badge-email { background: rgba(59, 130, 246, 0.2); color: #3b82f6; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; }
        .badge-sms { background: rgba(245, 158, 11, 0.2); color: #f59e0b; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; }
        
        .action-link { color: var(--primary-color); margin-right: 15px; font-weight: bold; text-decoration: none; }
        .action-link:hover { text-decoration: underline; }
        
        .btn-delete { background: none; border: none; color: #ef4444; cursor: pointer; font-weight: bold; }
        .btn-delete:hover { text-decoration: underline; }
    </style>
</head>
<body>

    @include('partials.navbar')

    <div class="container" style="margin-top: 30px;">

        <div class="page-header">
            <div>
                <a href="{{ route('admin.dashboard') }}" style="color: var(--text-muted); text-decoration: none; font-size: 0.9rem;">&larr; Back to Dashboard</a>
                <h1 style="margin-top: 10px;">Manage <span style="color: var(--primary-color);">Scenarios</span></h1>
            </div>
            <a href="{{ route('admin.questions.create') }}" class="btn btn-primary">+ Add New Scenario</a>
        </div>

        @if(session('success'))
            <div style="background: rgba(34, 197, 94, 0.1); color: #22c55e; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #22c55e;">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th style="width: 150px;">ID Key</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Sender Name</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($questions as $q)
                    <tr>
                        <td style="font-family: monospace; color: var(--text-muted);">{{ $q->key }}</td>
                        <td style="font-weight: bold;">{{ $q->title }}</td>
                        <td>
                            @if($q->type == 'email')
                                <span class="badge-email">Email</span>
                            @else
                                <span class="badge-sms">SMS</span>
                            @endif
                        </td>
                        <td>{{ $q->sender_name }}</td>
                        <td style="text-align: right;">
                            <a href="{{ route('admin.questions.edit', $q->id) }}" class="action-link">Edit</a>
                            
                            <form action="{{ route('admin.questions.destroy', $q->id) }}" method="POST" style="display:inline;">
                                @csrf 
                                @method('DELETE')
                                <button type="submit" class="btn-delete" onclick="return confirm('Delete this scenario? This cannot be undone.')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 30px; color: var(--text-muted);">
                            No scenarios found. <a href="{{ route('admin.questions.create') }}" style="color: var(--primary-color);">Create one now</a>.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    <script>
        if (localStorage.getItem('theme') === 'dark') document.documentElement.setAttribute('data-theme', 'dark');
    </script>
</body>
</html>