'use strict';

module.exports = {
    options: {
        // more options here if you want to override JSHint defaults
        globals: {
            jQuery: true
        },
        // 'esnext': true,
    },
    // test the source javascript
    src: [
        'javascripts/front.js',
        'javascripts/front-archive.js',
        'javascripts/front-single.js',
        'javascripts/map.js',
    ],
    dest: [
        '<%= path %>/js/custom/front.js',
        '<%= path %>/js/custom/front-archive.js',
        '<%= path %>/js/custom/front-single.js',
        '<%= path %>/js/custom/map.js'
    ], // test the distributed javascript
    grunt: ['Gruntfile.js'], // test the gruntfile itself
};
