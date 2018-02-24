var gulp            = require('gulp'),
    browserSync     = require('browser-sync').create(),
    sass            = require('gulp-sass'),
    autoprefixer    = require('gulp-autoprefixer'),
    sourcemaps      = require('gulp-sourcemaps'),
    theme_dir       = './wp-content/themes/commbible/';

gulp.task('default', ['sass', 'browser-sync', 'watch']);

gulp.task('sass', function() {
    return gulp.src(theme_dir + 'style.scss')
        .pipe(sourcemaps.init())
        .pipe(sass().on('error', errorHandler))
        .pipe(autoprefixer())
        .pipe(sourcemaps.write())
        .pipe(gulp.dest(theme_dir))
        .pipe(browserSync.reload({
            stream : true
        }));
});

gulp.task('browser-sync', function() {
    var files = [
            './wp-content/**/*.php',
            './wp-content/**/*.js'
        ];
    browserSync.init({
        files : files,
        proxy: 'test.cbc',
        port: '3001',
        online : false,
        notify : false,
        ui : false
    });
});

gulp.task('watch', function() {
    gulp.watch(['./wp-content/**/*.scss'], ['sass']);
});

gulp.task('build', function() {
    return gulp.src(theme_dir + 'style.scss')
        .pipe(sass({
            outputStyle: "compressed"
        }).on('error', errorHandler))
        .pipe(autoprefixer())
        .pipe(gulp.dest(theme_dir));
});

function errorHandler (err) {
    console.log( err );
    browserSync.notify( err.message );
    this.emit('end');
}