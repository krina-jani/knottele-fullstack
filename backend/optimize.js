const sharp = require('sharp');
const fs = require('fs');
const path = require('path');

const imagesDir = path.join(__dirname, 'public/images');
const imagesToOptimize = [
    'rosted-seeds.jpeg',
    'dryfruits.jpeg',
    'pista.jpeg',
    'chocolate.jpeg',
    'logo-cropped.png'
];

async function optimizeImages() {
    for (const image of imagesToOptimize) {
        const inputPath = path.join(imagesDir, image);
        if (!fs.existsSync(inputPath)) {
            console.log(`Skipping ${image} - not found`);
            continue;
        }

        const ext = path.extname(image);
        const name = path.basename(image, ext);
        const outputPath = path.join(imagesDir, `${name}.webp`);

        try {
            if (image === 'logo-cropped.png') {
                await sharp(inputPath)
                    .resize(280) // 2x the 140px display size
                    .webp({ quality: 80 })
                    .toFile(outputPath);
            } else {
                await sharp(inputPath)
                    .resize(600) // Much smaller than 1254x1254, good for 220x220 container
                    .webp({ quality: 80 })
                    .toFile(outputPath);
            }
            console.log(`Optimized: ${image} -> ${name}.webp`);
        } catch (error) {
            console.error(`Error optimizing ${image}:`, error);
        }
    }
}

optimizeImages();
