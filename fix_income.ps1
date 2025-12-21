$filePath = "d:\Mytime\resources\views\admin\financial\income.blade.php"
$content = Get-Content $filePath -Raw
$content = $content.Replace("DOMContentLoaded' function()", "DOMContentLoaded', function()")
$content | Set-Content $filePath
Write-Host "File fixed successfully"
