<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Local Business Directory' }}</title>
    <style>
        :root { --ink:#1f2933; --muted:#64707d; --line:#d8dee6; --brand:#0f766e; --accent:#b45309; --bg:#f7f9fb; --panel:#fff; }
        * { box-sizing:border-box; }
        body { margin:0; font-family:Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Arial, sans-serif; background:var(--bg); color:var(--ink); font-size:16px; line-height:1.6; -webkit-font-smoothing:antialiased; text-rendering:optimizeLegibility; }
        a { color:var(--brand); text-decoration:none; }
        header { background:var(--panel); border-bottom:1px solid var(--line); position:sticky; top:0; z-index:10; }
        nav, main { width:min(1120px, calc(100% - 32px)); margin:0 auto; }
        nav { min-height:64px; display:flex; gap:18px; align-items:center; justify-content:space-between; flex-wrap:wrap; }
        .brand { font-weight:700; font-size:1rem; color:var(--ink); }
        .nav-links { display:flex; align-items:center; gap:14px; flex-wrap:wrap; font-size:.95rem; }
        main { padding:38px 0 48px; }
        h1 { font-size:clamp(1.875rem, 2.6vw, 2.5rem); line-height:1.12; font-weight:750; margin:0 0 18px; letter-spacing:0; }
        h2 { font-size:1.25rem; line-height:1.25; font-weight:700; margin:0 0 14px; }
        h3 { margin:0 0 8px; font-size:1rem; line-height:1.3; font-weight:700; }
        p { margin-top:0; }
        .muted { color:var(--muted); }
        .toolbar, .panel { background:var(--panel); border:1px solid var(--line); border-radius:8px; padding:24px; }
        .grid { display:grid; grid-template-columns:repeat(auto-fit, minmax(250px, 1fr)); gap:16px; }
        .cards { display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:18px; margin-top:20px; }
        .card { background:var(--panel); border:1px solid var(--line); border-radius:8px; padding:18px; min-width:0; }
        .stats { display:grid; grid-template-columns:repeat(auto-fit, minmax(160px, 1fr)); gap:12px; margin:18px 0; }
        .stat { background:#eef7f5; border:1px solid #cce5df; border-radius:8px; padding:14px; }
        .stat strong { display:block; font-size:1.6rem; }
        form.stack, .stack { display:grid; gap:18px; }
        .form-grid { display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:16px 18px; }
        label { display:grid; gap:7px; font-size:.925rem; line-height:1.35; font-weight:650; color:#111827; }
        input, select, textarea { width:100%; border:1px solid var(--line); border-radius:7px; padding:10px 12px; min-height:44px; font:inherit; font-weight:400; background:white; color:var(--ink); }
        input:focus, select:focus, textarea:focus { outline:3px solid rgba(15, 118, 110, .16); border-color:var(--brand); }
        textarea { min-height:110px; resize:vertical; }
        button, .button { border:0; border-radius:7px; min-height:44px; padding:10px 15px; background:var(--brand); color:#fff; font-size:.95rem; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; gap:8px; }
        .button.secondary, button.secondary { background:#475569; }
        .button.warning, button.warning { background:var(--accent); }
        .button.danger, button.danger { background:#b91c1c; }
        .actions { display:flex; gap:8px; align-items:center; flex-wrap:wrap; }
        table { width:100%; border-collapse:collapse; background:var(--panel); border:1px solid var(--line); border-radius:8px; overflow:hidden; }
        th, td { text-align:left; padding:12px; border-bottom:1px solid var(--line); vertical-align:top; }
        th { background:#eef1f4; }
        .badge { display:inline-block; border-radius:999px; padding:3px 9px; background:#e8eef2; color:#334155; font-size:.85rem; }
        .badge.approved { background:#dcfce7; color:#166534; }
        .badge.pending { background:#fef3c7; color:#92400e; }
        .badge.rejected { background:#fee2e2; color:#991b1b; }
        .notice { border-left:4px solid var(--brand); background:#ecfdf5; padding:10px 12px; margin-bottom:16px; }
        .errors { border-left:4px solid #b91c1c; background:#fef2f2; padding:10px 12px; margin-bottom:16px; }
        .photo-grid { display:grid; grid-template-columns:repeat(auto-fit, minmax(160px, 1fr)); gap:12px; }
        .photo-grid img, .thumb { width:100%; aspect-ratio:4/3; object-fit:cover; border-radius:8px; border:1px solid var(--line); background:#eef1f4; }
        .pagination { margin-top:18px; }
        .auth-shell { min-height:calc(100vh - 150px); display:grid; place-items:start center; padding-top:18px; }
        .auth-card { width:min(100%, 620px); }
        .auth-card.compact { width:min(100%, 460px); }
        @media (max-width: 680px) { nav { align-items:flex-start; padding:12px 0; } table { display:block; overflow-x:auto; } .actions > * { width:100%; } }
    </style>
</head>
<body>
<header>
    <nav>
        <a class="brand" href="{{ route('home') }}">Local Business Directory</a>
        <div class="nav-links">
            <a href="{{ route('home') }}">Directory</a>
            @auth
                @if(auth()->user()->isOwner() || auth()->user()->isAdmin())
                    <a href="{{ route('owner.businesses.index') }}">Owner Dashboard</a>
                @endif
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}">Admin</a>
                @endif
                <span class="muted">{{ auth()->user()->name }}</span>
                <form method="post" action="{{ route('logout') }}">@csrf<button class="secondary">Logout</button></form>
            @else
                <a href="{{ route('login') }}">Login</a>
                <a class="button" href="{{ route('register') }}">Register</a>
            @endauth
        </div>
    </nav>
</header>
<main>
    @if(session('status')) <div class="notice">{{ session('status') }}</div> @endif
    @if($errors->any())
        <div class="errors">
            @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
        </div>
    @endif
    @yield('content')
</main>
</body>
</html>
