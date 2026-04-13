$files = @(
    "c:\Users\consi\MSWDO_Analysis\resources\views\admin\dashboard.blade.php",
    "c:\Users\consi\MSWDO_Analysis\resources\views\admin\requirements.blade.php",
    "c:\Users\consi\MSWDO_Analysis\resources\views\admin\detailed-analysis.blade.php",
    "c:\Users\consi\MSWDO_Analysis\resources\views\admin\data\dashboard.blade.php",
    "c:\Users\consi\MSWDO_Analysis\resources\views\admin\data\municipality.blade.php",
    "c:\Users\consi\MSWDO_Analysis\resources\views\admin\data\barangays.blade.php",
    "c:\Users\consi\MSWDO_Analysis\resources\views\admin\data\programs.blade.php",
    "c:\Users\consi\MSWDO_Analysis\resources\views\admin\data\yearly-data.blade.php"
)

$oldPattern = "        .navbar-brand { font-weight: 800; font-size: 1.55rem; color: white !important; display:flex; align-items:center; gap:12px; }"

$newPattern = @"
        .navbar-brand { font-weight: 800; font-size: 1.55rem; color: white !important; display:flex; align-items:center; gap:12px; }
        .navbar-toggler { order: -1; }
        .navbar-brand { order: 0; margin-left: auto !important; margin-right: 0 !important; }
        @media (min-width: 992px) {
            .navbar-toggler { order: 0; }
            .navbar-brand { order: 0; margin-left: 0 !important; margin-right: auto !important; }
        }
"@

foreach ($file in $files) {
    if (Test-Path $file) {
        $content = Get-Content $file -Raw
        if ($content -match [regex]::Escape($oldPattern)) {
            $content = $content -replace [regex]::Escape($oldPattern), $newPattern
            Set-Content -Path $file -Value $content -NoNewline
            Write-Host "Updated: $file" -ForegroundColor Green
        } else {
            Write-Host "Pattern not found in: $file" -ForegroundColor Yellow
        }
    } else {
        Write-Host "File not found: $file" -ForegroundColor Red
    }
}

Write-Host "`nAll admin files updated!" -ForegroundColor Cyan
