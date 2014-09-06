module.exports = function (grunt) {
  'use strict';
  grunt.initConfig({
    bower: {
      install: {
        options: {
          targetDir: './web/lib',
          layout: 'byComponent',
          install: true,
          verbose: false,
          cleanTargetDir: true,
          cleanBowerDir: false
        }
      }
    }
  });
  grunt.loadNpmTasks('grunt-bower-task');
};
