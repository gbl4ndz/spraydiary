<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Not Found — Spray Diary</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
            background: #f8fafc;
            color: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 1.5rem;
        }
        .wrap { text-align: center; max-width: 380px; width: 100%; }
        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 72px; height: 72px;
            border-radius: 20px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            margin-bottom: 1.5rem;
        }
        .badge svg { width: 36px; height: 36px; color: #16a34a; }
        .code { font-size: 3.5rem; font-weight: 800; color: #0f172a; line-height: 1; letter-spacing: -0.03em; }
        h1 { font-size: 1.125rem; font-weight: 600; margin-top: 0.5rem; color: #1e293b; }
        p { margin-top: 0.5rem; color: #64748b; font-size: 0.875rem; line-height: 1.6; }
        a {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            margin-top: 1.75rem;
            padding: 0.625rem 1.25rem;
            background: #16a34a;
            color: #fff;
            border-radius: 0.625rem;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: background 0.15s;
        }
        a:hover { background: #15803d; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="badge">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607z" />
            </svg>
        </div>
        <div class="code">404</div>
        <h1>Page Not Found</h1>
        <p>We couldn't find what you were looking for. The page may have moved or no longer exists.</p>
        <a href="/admin">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:14px;height:14px">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            Back to Dashboard
        </a>
    </div>
</body>
</html>
