module.exports = function(grunt) {

    // Project configuration.
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json')
        , concat: grunt.file.readJSON('grunt/concat.json')
        , copy : grunt.file.readJSON('grunt/copy.json')
        , uglify: grunt.file.readJSON('grunt/uglify.json')
        , cssmin: grunt.file.readJSON('grunt/cssmin.json')
        , imagemin : {
            dynamic: {
                files: [{
                    expand: true,
                    cwd: 'dist/',
                    src: ['**/*.{png,jpg,gif}'],
                    dest: 'dist/'
                }]
            }
        }
        , replace: {
            dist: {
                options: {
                    patterns: [
                        {
                            match: /fonts\.googleapis\.com/,
                            replacement: 'fonts.useso.com'
                        }
                    ]
                },
                files: [
                    {expand: true, flatten: false, src: ['dist/css/**/*.css'], dest: ''}
                ]
            }
        }
        , watch: {
            scripts: {
                files: [ 'src/**/*.js' , 'src/**/*.css' , 'bower_components/**' , 'grunt/*.json' , 'Gruntfile.js']
                , tasks: [ 'default' ]
                , options: {
                    debounceDelay: 1000
                }
            }
        }
    });



    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-requirejs');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-imagemin');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-replace');

    grunt.registerTask( 'default', [ 'concat','uglify','cssmin','copy','replace' ] );
    grunt.registerTask( 'build', [ 'concat','uglify','cssmin','copy','replace' ] );

};