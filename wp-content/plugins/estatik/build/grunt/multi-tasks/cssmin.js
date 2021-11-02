'use strict';

module.exports = {
    options: {
        keepSpecialComments: 0,
    },
    plugin: {
        files: [{
            expand: true,
            cwd: '<%= path %>/css/custom',
            src: ['*.css', '!*.min.css'],
            dest: '<%= path %>/css/custom',
            ext: '.min.css',
        }]
    },
};
