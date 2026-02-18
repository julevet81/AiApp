<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Import Applications</title>
    <style>
        body { font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial, "Noto Sans", "Liberation Sans", sans-serif; margin: 40px; }
        .card { max-width: 760px; border: 1px solid #e5e7eb; border-radius: 10px; padding: 20px; }
        .row { margin-bottom: 14px; }
        .muted { color: #6b7280; font-size: 14px; }
        .ok { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; padding: 10px 12px; border-radius: 8px; margin-bottom: 14px; }
        .err { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; padding: 10px 12px; border-radius: 8px; margin-bottom: 14px; }
        input[type="file"] { width: 100%; }
        button { background: #111827; color: #fff; border: 0; padding: 10px 14px; border-radius: 8px; cursor: pointer; }
        button:hover { background: #0b1220; }
        a { color: #2563eb; text-decoration: none; }
        a:hover { text-decoration: underline; }
        ul { margin: 8px 0 0 18px; }
        code { background: #f3f4f6; padding: 2px 6px; border-radius: 6px; }
    </style>
</head>
<body>
    <div class="card">
        <h2 style="margin-top: 0;">Import Applications</h2>
        <p class="muted">
            Upload an <code>.xlsx</code>, <code>.xls</code>, or <code>.csv</code> file and import rows into the <code>applications</code> table.
            <br>
            Download template: <a href="{{ route('applications.import.template') }}">applications_import_template.csv</a>
        </p>

        @if (session('success'))
            <div class="ok">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="err">
                <div><strong>Import failed</strong></div>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('applications.import.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <label for="file"><strong>Excel/CSV file</strong></label><br>
                <input id="file" name="file" type="file" required accept=".xlsx,.xls,.csv">
            </div>

            <div class="row">
                <label>
                    <input type="checkbox" name="replace" value="1" {{ old('replace') ? 'checked' : '' }}>
                    <strong>Replace all existing applications</strong>
                </label>
                <div class="muted">This will delete all rows in <code>applications</code> before importing.</div>
            </div>

            <button type="submit">Import</button>
        </form>
    </div>
</body>
</html>

