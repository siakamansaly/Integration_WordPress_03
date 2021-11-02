module.exports = function(grunt) {

        grunt.initConfig({

            //  ============================
            //  Variables
            //  ============================

            pkg: grunt.file.readJSON('package.json'),
            path: '../assets',


            //  ============================
            //  Tasks
            //  ============================

            // compile sass files into css
            sass: require('./grunt/multi-tasks/sass'),

            // postcss
            postcss: require('./grunt/multi-tasks/postcss'),

            // minify css
            cssmin: require('./grunt/multi-tasks/cssmin'),

            // test javascript and notify of errors
            jshint: require('./grunt/multi-tasks/jshint'),

            // minimise javascript
            uglify: require('./grunt/multi-tasks/uglify'),

            // watch files and make changes
            watch: require('./grunt/multi-tasks/watch'),

            // webfont: {
            //     icons: {
            //         src: 'icons/*.svg',
            //         dest: '../assets/fonts',
            //         destScss: 'stylesheets/fonts/',
            //         options: {
            //             templateOptions: {
            //                 baseClass: 'ert-icon',
            //                 classPrefix: 'ert-icon_'
            //             },
            //             font: 'ert-icons',
            //             stylesheet: 'scss',
            //             relativeFontPath: '../fonts'
            //         }
            //     }
            // }

        });

        //  ============================
        //  External Tasks
        //  ============================

        // load externally defined tasks
        require('load-grunt-tasks')(grunt);
        // grunt.loadNpmTasks( 'grunt-webfont' );

        //  ============================
        //  Register Tasks
        //  ============================

        // register tasks for command line
        grunt.registerTask('default',['sass:plugin','postcss:plugin','cssmin:plugin','jshint:src','jshint:modules','uglify:scripts']); // default
        grunt.registerTask('plugin',['sass:plugin','postcss:plugin','cssmin:plugin']); // plugin styles
        grunt.registerTask('scripts',['jshint:src','uglify:scripts']); // scripts
        grunt.registerTask('lint',['jshint:dist']); // lint compiled scripts
    };
