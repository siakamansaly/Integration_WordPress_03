'use strict';

module.exports = {
    options: {
        map: false, // turns off postcss sourcemap file
        processors: [
            require('pixrem')(), // add fallbacks for rem units
            require('postcss-flexbox')(), // add flexbox shortcuts
            require('autoprefixer')({browsers: 'last 4 versions'}), // add vendor prefixes
        ]
    },
    plugin: {
        src: '<%= path %>/css/custom/*.css',
    },
};
