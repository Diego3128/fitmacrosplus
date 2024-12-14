import path from 'path'
import fs from 'fs'
import { glob } from 'glob'
import { src, dest, watch, series } from 'gulp'
import * as dartSass from 'sass'
import gulpSass from 'gulp-sass'
import terser from 'gulp-terser'
import sharp from 'sharp'
import postcss from 'gulp-postcss';
import concat from 'gulp-concat';
import sourcemaps from 'gulp-sourcemaps';
import autoprefixer from 'autoprefixer';
import rename from 'gulp-rename';
import cssnanoPlugin from 'cssnano';
const plugins = [autoprefixer(), cssnanoPlugin];

const sass = gulpSass(dartSass)

const paths = {
    scss: 'src/scss/**/*.scss',
    js: 'src/js/**/*.js'
}

function buildStyles() {
    return src('./src/scss/app.scss', { sourcemaps: true })
        .pipe(sass().on('error', sass.logError))
        .pipe(postcss(plugins))
        .pipe(dest('./public/build/css/', { sourcemaps: '.' }));
}
//build javascript files, keeping the same folder structure
function buildJS() {
    return src(paths.js, { base: './src' }) // keeps the same structure of the ./src folder
        .pipe(sourcemaps.init())
        .pipe(rename({ suffix: '.min' })) // add .min
        .pipe(sourcemaps.write('.')) // writte sourcemaps
        .pipe(dest('./public/build/')); // final folder
}

//Version below for production
function buildJSProduction() {
    return src(paths.js, { base: './src' }) // keeps the same structure of the ./src folder
        .pipe(sourcemaps.init())
        .pipe(terser()) //uglify the code //comment to use the debugger
        .pipe(rename({ suffix: '.min' })) // add .min
        .pipe(sourcemaps.write('.')) // writte sourcemaps
        .pipe(dest('./public/build/')); // final folder
}

export async function images(done) {
    const srcDir = './src/img';
    const buildDir = './public/build/img';
    const images = await glob('./src/img/**/*');

    images.forEach(file => {
        // verify it's a file, not a folder
        if (fs.statSync(file).isFile()) {
            const relativePath = path.relative(srcDir, path.dirname(file));
            const outputSubDir = path.join(buildDir, relativePath);
            processImages(file, outputSubDir);
        } else {
            console.log(`Ignoring folder: ${file}`);
        }
    });

    done();
}
function processImages(file, outputSubDir) {
    const supportedFormats = ['.jpg', '.jpeg', '.png', '.webp', '.gif', '.tiff', '.svg'];
    const extName = path.extname(file).toLowerCase();

    if (!supportedFormats.includes(extName)) {
        // console.log(`Formato no compatible: ${file}`);
        return; // Ignora archivos no soportados
    }

    if (!fs.existsSync(outputSubDir)) {
        fs.mkdirSync(outputSubDir, { recursive: true });
    }

    const baseName = path.basename(file, extName);

    if (extName === '.svg') {
        const outputFile = path.join(outputSubDir, `${baseName}${extName}`);
        fs.copyFileSync(file, outputFile);
    } else {
        const outputFile = path.join(outputSubDir, `${baseName}${extName}`);
        const outputFileWebp = path.join(outputSubDir, `${baseName}.webp`);
        const options = { quality: 80 };

        sharp(file).jpeg(options).toFile(outputFile);
        sharp(file).webp(options).toFile(outputFileWebp);
    }
}


export function dev() {
    watch(paths.scss, buildStyles);
    watch(paths.js, buildJS);
    watch('src/img/**/*.{png,jpg}', images)
}
// "npm run build"
export function build(done) {
    return series(buildJSProduction, buildStyles, images)(done);
}

//default task when executing "npm run dev"
export default series(buildJS, buildStyles, images, dev)