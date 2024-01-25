'use strict';
/*
 * Tasks:
 * [ ] Run update on YouTube feature of plugin using build script.
 *
 * [ ] Compile Sass files into minified css in the same folder
 * [ ] Transpile Modern JavaScript into older JavaScript
 * [ ] Compile JavaScript into minified JavaScript
 * [ ] Live reloads of changes in JavaScript and Sass
 * [ ] Live reloads of changes in JavaScript and Sass
 *
 * Bonus: 
 * [ ] Compile PHP to distribution (maybe name it 'inc' directory)
 * [ ] 
 *  
 *
 *
 *

I have my project basically all in the `src` directory.


1. Build a gulp script to transpile JS, SCSS, bundle theme, minify, and watch and automatically reload on file changes, even for PHP - [reference - learn gulp from scratch playlist](https://www.youtube.com/watch?v=oRoy1fJbMls&list=PLriKzYyLb28lp0z-OMB5EYh0OHaKe91RV&pp=iAQB)  
    - Update / Write [Gulp JS](https://gulpjs.com/docs/en/getting-started/quick-start) notes
        - common error in gulp not reading files - [stackoverflow](https://stackoverflow.com/questions/22391527/gulps-gulp-watch-not-triggered-for-new-or-deleted-files)
    - See if gulp can compile PHP. Might need to be a separate task.

 */

const { src, dest, watch, parallel, series } = require("gulp");
const sass = require("gulp-sass")(require("sass"));

function buildStyles() {
    // compile all sass files into css, ignore partials
    return src(["src/**/*.scss", "!src/**/_*.scss"])
        .pipe(sass().on("error", sass.logError))
        .pipe(dest('dist/src'));
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

exports.buildStyles = buildStyles;

exports.watch = function watch() {
    watch('./sass/**/*.scss', ['sass']);
};

exports.default = parallel(
    buildStyles,
    copyPHPFilesFromSrc,
    copyPHPFilesFromVendor,
    copyCorePluginFile,
)

// exports.default = function default() {
//     // The task will be run (concurrently) for every change made
//     watch('src/*.s', { queue: false }, function(cb) {
//         // body omitted
//         cb();
//     });
// }
