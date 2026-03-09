const fs = require('fs');
const path = require('path');

const dir = 'c:/websites/AttendanceFlow-AMS/maquete-mobile';
const files = fs.readdirSync(dir).filter(f => f.endsWith('.html'));

let updatedCount = 0;

files.forEach(file => {
    const filePath = path.join(dir, file);
    let content = fs.readFileSync(filePath, 'utf8');
    const originalContent = content;
    
    // Replace mobile-specific colors with Web ones
    content = content.replace(/emerald/g, 'green');
    content = content.replace(/violet/g, 'indigo');
    content = content.replace(/teal/g, 'blue');
    content = content.replace(/purple/g, 'indigo');
    
    if (content !== originalContent) {
        fs.writeFileSync(filePath, content);
        updatedCount++;
    }
});
console.log(`Successfully updated ${updatedCount} files to match the web color scheme.`);
