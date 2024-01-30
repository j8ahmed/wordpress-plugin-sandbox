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
 * [ ] Transpile Modern JavaScript into older JavaScript
 * [ ] Compile JavaScript into minified JavaScript
 * [ ] Live reloads:
 *     [x] Live reloads of changes in Sass
 *     [ ] Live reloads of changes in JavaScript
 *     [ ] Live reloads of changes in PHP files
 *
 * Bonus: 
 * [x] Compile PHP to distribution (only copying so far - need to look into compiling it further to make it potentially more effecient to run and perhaps capable of obsfucating the code, idk yet)
 * [ ] 
 *  
 */



const fs = require("fs");
const { src, dest, watch, parallel, series } = require("gulp");
const browserify = require("browserify");
const babelify = require("babelify");
const source = require("vinyl-source-stream");
const buffer = require("vinyl-buffer");
const rename = require("gulp-rename");
const sass = require("gulp-sass")(require("sass"));
const sourcemaps = require("gulp-sourcemaps");
const uglify = require("gulp-uglify");

// Set the plugin folder absolute file path
const pluginFolder = "../../j8ahmed-test-plugin-1"

// Async to allow for importing of ESM autoprefixer package in CommonJS.
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
            .pipe(rename({suffix: ".min"}))
            .pipe(sourcemaps.write("./"))
            .pipe(dest(pluginFolder + '/src'));
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
                console.log("Error : " + err.message); 
            })
            .pipe(source(entry))
            .pipe(rename({basename: "bundle", extname: ".min.js"}))
            .pipe(buffer())
            .pipe(sourcemaps.init({loadMaps: true}))
            .pipe(uglify())
            .pipe(sourcemaps.write("./"))
            .pipe(dest(pluginFolder))
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

//function copyDistToPluginFolder() {
//    return src(["dist/**/*"])
//        .pipe(dest(pluginFolder));
//}

function watchStyles() {
    watch(["src/**/*.scss"],
        {},
        buildStyles
    );
}

// exports.watch = function watch() {
//     watch('./sass/**/*.scss', ['sass']);
// };

exports.buildStyles = buildStyles;
exports.watchStyles = watchStyles;

exports.default = series(
    parallel(
        buildStyles,
        copyPHPFilesFromSrc,
        copyPHPFilesFromVendor,
        copyCorePluginFile,
    )
);

// exports.default = function default() {
//     // The task will be run (concurrently) for every change made
//     watch('src/*.s', { queue: false }, function(cb) {
//         // body omitted
//         cb();
//     });
// }
// testing adding a new line
