$files = @(
    "c:\Users\consi\MSWDO_Analysis\resources\views\admin\requirements.blade.php",
    "c:\Users\consi\MSWDO_Analysis\resources\views\admin\detailed-analysis.blade.php",
    "c:\Users\consi\MSWDO_Analysis\resources\views\admin\data\dashboard.blade.php",
    "c:\Users\consi\MSWDO_Analysis\resources\views\admin\data\municipality.blade.php",
    "c:\Users\consi\MSWDO_Analysis\resources\views\admin\data\barangays.blade.php",
    "c:\Users\consi\MSWDO_Analysis\resources\views\admin\data\programs.blade.php",
    "c:\Users\consi\MSWDO_Analysis\resources\views\admin\data\yearly-data.blade.php"
)

foreach ($file in $files) {
    if (Test-Path $file) {
        $content = Get-Content $file -Raw
        
        # Find .navbar-brand { and add mobile positioning after it
        if ($content -match '(\.navbar-brand \{[^\}]+\})') {
            $navbarBrandBlock = $matches[1]
            
            # Check if already has mobile positioning
            if ($content -notmatch '\.navbar-toggler \{ order: -1;') {
                $mobileCSS = @"

        .navbar-toggler { order: -1; }
        .navbar-brand { order: 0; margin-left: auto !important; margin-right: 0 !important; }
        @media (min-width: 992px) {
            .navbar-toggler { order: 0; }
            .navbar-brand { order: 0; margin-left: 0 !important; margin-right: auto !important; }
        }
"@
                $content = $content -replace '(\.navbar-brand \{[^\}]+\})', "`$1$mobileCSS"
                Set-Content -Path $file -Value $content -NoNewline
                Write-Host "Updated: $file" -ForegroundColor Green
            } else {
                Write-Host "Already updated: $file" -ForegroundColor Cyan
            }
        } else {
            Write-Host "Pattern not found in: $file" -ForegroundColor Yellow
        }
    } else {
        Write-Host "File not found: $file" -ForegroundColor Red
    }
}

Write-Host "`nAll remaining admin files processed!" -ForegroundColor Cyan
