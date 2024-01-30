'use strict';
/*
 * Development environment must be placed with plugins directory for this to work.
 * Currently, I am not sure why Gulp cannot process a directory path like "/c/xampp/htdocs/..." This may be related to how the directory path is being translated for globbing and also handling windows file systems under the hood. Either way, we have this limitation as a result.
 * I will look into this later but for now it's not worth the effort as I might move away from using Gulp in the future.
 *
 * Tasks:
 * [x] Run update on YouTube feature of plugin using build script.
 *
 * [x] Compile Sass files into minified css in the same folder
 * [x] Transpile Modern JavaScript into older JavaScript
 * [x] Compile JavaScript into minified JavaScript
 * [x] Live reloads:
 *     [x] Live reloads of changes in Sass
 *     [x] Live reloads of changes in JavaScript
 *     [x] Live reloads of changes in PHP files
 *
 * Bonus: 
 * [x] Compile PHP to distribution (only copying so far - need to look into compiling it further to make it potentially more effecient to run and perhaps capable of obsfucating the code, idk yet)
 * [ ] 
 *  
 */

const { src, dest, watch, parallel, series } = require("gulp");
const babelify = require("babelify");
const browserify = require("browserify");
const browserSync = require("browser-sync");
const buffer = require("vinyl-buffer");
const fs = require("fs");
const rename = require("gulp-rename");
const sass = require("gulp-sass")(require("sass"));
const source = require("vinyl-source-stream");
const sourcemaps = require("gulp-sourcemaps");
const uglify = require("gulp-uglify");

// Set the plugin folder file path
const pluginFolder = "../../j8ahmed-test-plugin-1"

// Async to allow for importing of ESM autoprefixer package in CommonJS.
exports.buildStyles = buildStyles;
async function buildStyles() {
    try{
        const autoprefixer = (await import("./node_modules/gulp-autoprefixer/index.js")).default;
        // compile all sass files into css, ignore partials
        return src(["src/**/*.scss", "!src/**/_*.scss"])
            .pipe(sourcemaps.init())
            .pipe(sass({outputStyle: "compressed"})
                .on("error", sass.logError))
            .pipe(autoprefixer({
                cascade: false
            }))
            .pipe(rename((path) => {
                path.dirname = path.dirname.replace("scss", "css");
                path.extname = ".min.css";
            }))
            .pipe(dest("testcss"))
            .pipe(sourcemaps.write("./"))
            .pipe(dest(pluginFolder + '/src'))
            .pipe(browserSync.stream());
    }catch(e){
        console.error(e, "problem with autoprefixer import");
    }
};

exports.buildJS = buildJS;
async function buildJS() {
    // Present all the entry points for each JavaScript module we want to bundle, transpile, and minify into one JavaScript file.
    // Similar to Compiling Sass where are required files are included for the main file (i.e. the entry file) to run.
    // In my case I am identifying all entry points with the "app.mjs" file name. As in each script bundle should be a miniature application.
    const entries = await fs.readdirSync("./src", {recursive: true})
        .filter(fn => fn.endsWith("app.mjs"))
        .map(fn => "src/" + fn.replace(new RegExp("\\\\", "g"), "/"))

    entries.map(entry => {
        return browserify(entry)
            .transform(babelify, {presets: ["@babel/preset-env"]})
            .bundle()
            .on("error", (err) => {
                console.log("Browserify Error : " + err.message); 
            })
            .pipe(source(entry))
            .pipe(rename({basename: "bundle", extname: ".min.js"}))
            .pipe(buffer())
            .pipe(sourcemaps.init({loadMaps: true}))
            .pipe(uglify())
            .pipe(sourcemaps.write("./"))
            .pipe(dest(pluginFolder))
            .pipe(browserSync.stream())
    })
}

function copyCorePluginFile() {
    return src(["./*.php"])
        .pipe(dest(pluginFolder));
}
function copyPHPFilesFromSrc() {
    return src(["src/**/*.php"])
        .pipe(dest(pluginFolder + '/src'));
}
function copyPHPFilesFromVendor() {
    return src(["vendor/**/*.php"])
        .pipe(dest(pluginFolder + '/vendor'));
}
const copyPHP = parallel(copyCorePluginFile, copyPHPFilesFromSrc, copyPHPFilesFromVendor);


function reload(done) {
    browserSync.reload();
    done();
}

exports.watchStyles = series(buildStyles, parallel(sync, watchStyles));
function watchStyles() {
    return watch(["src/**/*.scss"],
        {},
        series(buildStyles, reload)
    );
}

exports.watchJS = series(buildJS, parallel(sync, watchJS));
function watchJS() {
    watch(["src/**/*.?js"],
        {},
        series(buildJS, reload)
    );
}

exports.watchPHP = series(copyPHP, parallel(sync, watchPHP));
function watchPHP() {
    watch(["./*.php"],
        {},
        series(copyCorePluginFile, reload)
    );

    watch(["src/**/*.php"],
        {},
        series(copyPHPFilesFromSrc, reload)
    );

    watch(["vendor/**/*.php"],
        {},
        series(copyPHPFilesFromVendor, reload)
    );
}

exports.sync = sync;
function sync() {
    return browserSync.init({
        open: false,
        injectChanges: true,
        proxy: "http://local.blogj8ahmed.com",
    })
}

const build = parallel(
    buildStyles,
    buildJS,
    copyPHP
);
exports.default = build

const watchAll = parallel(
    watchStyles,
    watchJS,
    watchPHP,
);
exports.watch = series(build, parallel(sync, watchAll));
