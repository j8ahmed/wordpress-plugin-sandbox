'use strict';
/*
 * Development environment must be placed with plugins directory for this to work.
 * Currently, I am not sure why Gulp cannot process a directory path like "/c/xampp/htdocs/..." This may be related to how the directory path is being translated for globbing and also handling windows file systems under the hood. Either way, we have this limitation as a result.
 * I will look into this later but for now it's not worth the effort as I might move away from using Gulp in the future.
 *
 * Tasks:
 * [x] Run update on YouTube feature of plugin using build script.
 *
 * [ ] Compile Sass files into minified css in the same folder
 * [ ] Transpile Modern JavaScript into older JavaScript
 * [ ] Compile JavaScript into minified JavaScript
 * [ ] Live reloads:
 *     [ ] Live reloads of changes in JavaScript and Sass
 *     [ ] Live reloads of changes in JavaScript and Sass
 *     [ ] Live reloads of changes in PHP files
 *
 * Bonus: 
 * [x] Compile PHP to distribution (only copying so far - need to look into compiling it further to make it potentially more effecient to run and perhaps capable of obsfucating the code, idk yet)
 * [ ] 
 *  
 */



const { src, dest, watch, parallel, series } = require("gulp");
const sass = require("gulp-sass")(require("sass"));
const rename = require("gulp-rename");
const sourcemaps = require("gulp-sourcemaps");

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
            .pipe(dest('dist/src'));
    }catch(e){
        console.error(e, "problem with autoprefixer import");
    }
};

function copyCorePluginFile() {
    return src(["./*.php"])
        .pipe(dest('dist/'));
}

function copyPHPFilesFromSrc() {
    return src(["src/**/*.php"])
        .pipe(dest('dist/src'));
}

function copyPHPFilesFromVendor() {
    return src(["vendor/**/*.php"])
        .pipe(dest('dist/vendor'));
}

function copyDistToPluginFolder() {
    return src(["dist/**/*"])
        .pipe(dest(pluginFolder));
}

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
    ),
    copyDistToPluginFolder,
);

// exports.default = function default() {
//     // The task will be run (concurrently) for every change made
//     watch('src/*.s', { queue: false }, function(cb) {
//         // body omitted
//         cb();
//     });
// }
// testing adding a new line
