$file = 'resources\views\analysis\index.blade.php'
$lines = [System.IO.File]::ReadAllLines($file, [System.Text.Encoding]::UTF8)

Write-Host "Total lines: $($lines.Length)"
Write-Host "Line 1121: $($lines[1120])"
Write-Host "Line 1122: $($lines[1121])"

# The insertion goes AFTER index 1120 (line 1121)
$insert = @(
    '',
    '    {{-- SECTION 10: RECOMMENDATIONS --}}',
    '    <section class="section-wrap">',
    '        <div class="container">',
    '            <h2 class="sec-title">10. Recommendations</h2>',
    '            <div class="row g-3">',
    '                @foreach($recommendations as $rec)',
    '                    <div class="col-md-6">',
    '                        <div class="rec-card">',
    '                            <div style="font-size:.72rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:var(--blue);margin-bottom:6px;">{{ $rec[''label''] }}</div>',
    '                            <p style="font-size:.88rem;color:#475569;margin:0;line-height:1.65;">{{ $rec[''text''] }}</p>',
    '                        </div>',
    '                    </div>',
    '                @endforeach',
    '            </div>',
    '        </div>',
    '    </section>',
    '',
    '    @auth',
    '        @if(Auth::user()->isSuperAdmin())',
    '            <style>.admin-back-btn{position:fixed;bottom:28px;left:28px;z-index:9999;display:flex;align-items:center;gap:10px;background:var(--grad);color:#fff;border:none;border-radius:50px;padding:12px 22px 12px 16px;font-family:''Inter'',sans-serif;font-weight:800;font-size:.85rem;box-shadow:0 8px 28px rgba(44,62,143,.4);cursor:pointer;text-decoration:none;transition:all .3s;}.admin-back-btn:hover{transform:translateY(-4px);color:#fff;}</style>',
    '            <a href="{{ route(''superadmin.dashboard'') }}" class="admin-back-btn">&#8592; Super Admin Dashboard</a>',
    '        @elseif(Auth::user()->isAdmin())',
    '            <style>.admin-back-btn{position:fixed;bottom:28px;left:28px;z-index:9999;display:flex;align-items:center;gap:10px;background:linear-gradient(135deg,#FDB913,#E5A500);color:#1A2A5C;border:none;border-radius:50px;padding:12px 22px 12px 16px;font-family:''Inter'',sans-serif;font-weight:800;font-size:.85rem;box-shadow:0 8px 28px rgba(253,185,19,.45);cursor:pointer;text-decoration:none;transition:all .3s;}.admin-back-btn:hover{transform:translateY(-4px);color:#1A2A5C;}</style>',
    '            <a href="{{ route(''admin.dashboard'') }}" class="admin-back-btn">&#8592; Admin Dashboard</a>',
    '        @endif',
    '    @endauth',
    '',
    '    @include(''components.chatbot-widget'')',
    '',
    '    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>'
)

# Build new content: lines before insertion point + insert + rest
$newLines = $lines[0..1120] + $insert + $lines[1121..($lines.Length - 1)]

[System.IO.File]::WriteAllLines($file, $newLines, [System.Text.Encoding]::UTF8)
Write-Host "Done. New total lines: $($newLines.Length)"
