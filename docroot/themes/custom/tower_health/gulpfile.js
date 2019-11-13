/* globals require */

// eslint-disable-next-line strict
'use strict';

// General
var gulp = require('gulp-help')(require('gulp'));
var localConfig = {};

try {
  localConfig = require('./local.gulp-config');
}
catch (e) {
  if (e.code !== 'MODULE_NOT_FOUND') {
    throw e;
  }
}
require('emulsify-gulp')(gulp, localConfig);
var gulpCopy = require('gulp-copy');

var sourceFiles = ['../../../core/assets/vendor/jquery/jquery.min.js', '../../../core/misc/drupal.js'];
var destination = 'components/js/';

gulp.task('copy_js', function() {
  return gulp.src(sourceFiles)
    .pipe(gulp.dest(destination));
});
