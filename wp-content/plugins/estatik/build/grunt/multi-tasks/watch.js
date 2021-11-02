'use strict';

module.exports = {
    options: {
        spawn: false // add spawn option in watch task
    },
    grunt: {
        files: ['Gruntfile.js'],
        tasks: ['jshint:grunt'],
    },
    plugin: {
        files: ['stylesheets/**/*'],
        tasks: ['sass:plugin','postcss:plugin','cssmin:plugin'],
    },
    scripts: {
        files: [
            'javascripts/front.js',
            'javascripts/front-archive.js',
            'javascripts/map.js',
            'javascripts/front-single.js',
        ],
        tasks: ['jshint:src','uglify:scripts'],
    },
};
