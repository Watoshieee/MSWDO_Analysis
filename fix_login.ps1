$filePath = "resources\views\auth\login.blade.php"
$lines = Get-Content $filePath
# Lines 243-248 are index 242-247 (0-based). Remove them.
$output = @()
for ($i = 0; $i -lt $lines.Length; $i++) {
    if ($i -ge 242 -and $i -le 247) { continue }
    $output += $lines[$i]
}
Set-Content $filePath $output
Write-Host "Done. Total lines after: $($output.Length)"
