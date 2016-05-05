var gulp = require('gulp');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');
var sass = require('gulp-sass');
var cleanCSS = require('gulp-clean-css');
var concatCss = require('gulp-concat-css');
var browserify = require('gulp-browserify');
var cssmin = require('gulp-cssmin');

gulp.task('build-js', function() {
    return gulp.src(['./src/assets/dist/js/index.js'])
        .pipe(browserify({
            insertGlobals: true
        }))
        .pipe(uglify())
        .pipe(rename("index.min.js"))
        .pipe(gulp.dest('./web/assets/dist/js'));
});

gulp.task('minify-css', function() {
    return gulp.src(
            [
                './node_modules/bootstrap/dist/css/bootstrap.css',
                './node_modules/bootstrap/dist/css/bootstrap-theme.css',
                './src/assets/dist/css/app.css'
            ])
        .pipe(concat('./src/assets/dist/css/bundle.css'))
        .pipe(rename("app.min.css"))
        .pipe(gulp.dest('./web/assets/dist/css'));
});

gulp.task('watch', function() {
    gulp.watch(['./web/assets/dist/js/index.js', './web/assets/dist/css/app.css'], ['prod']);
});

gulp.task('default', ['build-js', 'minify-css'], function() {});
