const { watch } = require('gulp');

exports.default = function() {
    // The task will be run (concurrently) for every change made
    watch('src/*.js', { queue: false }, function(cb) {
        // body omitted
        cb();
    });
}
