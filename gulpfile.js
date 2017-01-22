const gulp         = require('gulp');
const browserify   = require('browserify');
const babelify     = require('babelify');
const source       = require('vinyl-source-stream');
const uglify       = require('gulp-uglify');
const sass         = require('gulp-sass');
const autoprefixer = require('gulp-autoprefixer');
const cleanCSS     = require('gulp-clean-css');
const imagemin     = require('gulp-imagemin');

gulp.task('default', ['build', 'watch']);

gulp.task('build', ['js', 'css', 'images']);

gulp.task('watch', () => {
  gulp.watch('resources/scripts/*.js', ['js']);
  gulp.watch('resources/scss/**/*.scss', ['css']);
});

gulp.task('css', () => {
  gulp.src('resources/scss/**/*.scss')
      .pipe(sass().on('error', sass.logError))
      .pipe(autoprefixer({ browsers: ['last 2 versions', 'ie 6-8'] }))
      .pipe(cleanCSS({compatibility: 'ie8'}))
      .pipe(gulp.dest('public/css/'));
});

gulp.task('js', function () {
  return browserify({entries: 'resources/scripts/app.js', debug: true})
        .transform(babelify.configure({
          presets: ["es2015"]
        }))
        .bundle()
        .pipe(source('main.js'))
        .pipe(gulp.dest('public/js'));
});

gulp.task('images', () => {
  gulp.src('public/img/*')
      .pipe(imagemin())
      .pipe(gulp.dest('public/img/'));
});
