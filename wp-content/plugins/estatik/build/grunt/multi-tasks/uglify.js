'use strict';

module.exports = {
    scripts: {
        files: {
            '<%= path %>/js/custom/front.min.js': ['javascripts/front.js'],
            '<%= path %>/js/custom/front-single.min.js': ['javascripts/front-single.js'],
            '<%= path %>/js/custom/front-archive.min.js': ['javascripts/front-archive.js'],
            '<%= path %>/js/custom/map.min.js': ['javascripts/map.js'],
        }
    },
};
