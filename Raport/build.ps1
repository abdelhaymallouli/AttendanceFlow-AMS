# Pandoc Build Script for AttendanceFlow-AMS (DOCX Only)
# This script converts modular Markdown files into a professional DOCX report and updates README.md.

$outputDir = "docs"
$outputDocx = "$outputDir/AMS_Report.docx"
$readmeFile = "README.md"

$inputFiles = "00_title_page.md",
"01_toc.md",
"02_preliminary.md", 
"03_contexte.md", 
"03b_cahier_de_charge.md",
"04_methode.md", 
"05_branche_fonctionnelle.md", 
"06_technical.md", 
"07_design.md", 
"08_realisation.md", 
"09_conclusion.md"

# Ensure output directory exists
if (!(Test-Path $outputDir)) {
    New-Item -ItemType Directory -Path $outputDir
    Write-Host "Created directory: $outputDir" -ForegroundColor Yellow
}

Write-Host "--- Updating Consolidated README.md ---" -ForegroundColor Cyan
Get-Content $inputFiles | Set-Content $readmeFile
Write-Host "README.md updated from individual sections." -ForegroundColor Green

Write-Host "--- Starting Pandoc Build for AttendanceFlow-AMS (DOCX) ---" -ForegroundColor Cyan

Write-Host "Generating DOCX: $outputDocx..."
pandoc $inputFiles `
    --number-sections `
    --standalone `
    -o $outputDocx

if ($LASTEXITCODE -eq 0) {
    $fullPath = Resolve-Path $outputDocx
    Write-Host "DOCX SUCCESS: Generated at $fullPath" -ForegroundColor Green
}
else {
    Write-Host "BUILD FAILED: Please check for errors above." -ForegroundColor Red
}

Write-Host "--- Build Complete ---" -ForegroundColor Cyan



