const fs = require('fs');
const path = require('path');

const srcDir = path.resolve(__dirname, '../out');
const destDir = path.resolve(__dirname, '../../backend/public');

// Files and folders in Laravel public that should NOT be overwritten or deleted
const PRESERVED_ITEMS = new Set([
  'index.php',
  '.htaccess',
  'robots.txt',
  'css',
  'js',
  'storage',
  'icons.svg',
]);

function copyRecursive(src, dest) {
  if (!fs.existsSync(src)) {
    console.error(`[Error] Source directory does not exist: ${src}`);
    return;
  }

  if (!fs.existsSync(dest)) {
    fs.mkdirSync(dest, { recursive: true });
  }

  const entries = fs.readdirSync(src, { withFileTypes: true });

  for (const entry of entries) {
    const srcPath = path.join(src, entry.name);
    const destPath = path.join(dest, entry.name);

    // Skip protected Laravel core files
    if (PRESERVED_ITEMS.has(entry.name) && dest === destDir) {
      continue;
    }

    if (entry.isDirectory()) {
      copyRecursive(srcPath, destPath);
    } else {
      fs.copyFileSync(srcPath, destPath);
    }
  }
}

console.log('🔄 Syncing Next.js export to Laravel public directory...');
console.log(`📂 From: ${srcDir}`);
console.log(`📁 To:   ${destDir}`);

try {
  copyRecursive(srcDir, destDir);
  
  // Ensure public/admin directory never exists so Nginx never shadows Laravel's admin routes
  const adminDir = path.join(destDir, 'admin');
  if (fs.existsSync(adminDir)) {
    fs.rmSync(adminDir, { recursive: true, force: true });
  }
  
  console.log('✅ Successfully exported Next.js frontend into Laravel public directory!');
} catch (error) {
  console.error('❌ Error during sync to Laravel public:', error);
  process.exit(1);
}
