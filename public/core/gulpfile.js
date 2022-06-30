var gulp = require('gulp'),
    sass = require('gulp-sass'),
    postcss = require('gulp-postcss'),
    sourcemaps = require('gulp-sourcemaps'),
    autoprefixer = require('autoprefixer'),
    uglifyjs = require('gulp-uglifyjs'),
    bourbon = require('node-bourbon'),
    browserSync = require('browser-sync').create(),
    path = require("path"),
    runs = require('run-sequence'),
    clean = require('gulp-clean');
var minifyCSS = require('gulp-minify-css');
var rename = require('gulp-rename');
var targetPath = "/../assets";

var paths = {
    sass:
        [
            path.join(__dirname, 'sass/*.scss')
        ],
    js:
        {
            app:
                [
                    path.join(__dirname, "script/*.js"),
                ],
        },
    jspage:
        {
            app:
                [
                    path.join(__dirname, "script/pages/*.js"),
                ]
        }
}

// CLEANER
gulp.task('clean-sass', function (file) {
    return gulp.src(path.join(__dirname, targetPath + '/css/main.css'), {read: false})
        .pipe(clean({force: true}));
});
gulp.task('clean-js', function () {
    return gulp.src(path.join(__dirname, targetPath + '/js/*.js'), {read: false})
        .pipe(clean({force: true}));
});
gulp.task('clean-js-page', function () {
    return gulp.src(path.join(__dirname, targetPath + '/js/pages/*.js'), {read: false})
        .pipe(clean({force: true}));
});
// CLEANER

// copy the sass file task
gulp.task('sass', ['clean-sass'], function () {
    return gulp.src(paths.sass)
        .pipe(sourcemaps.init())
        .pipe(sass({
            outputStyle: 'compressed',
            includePaths: bourbon.includePaths
        }).on('error', sass.logError))
        .pipe(postcss([autoprefixer({browsers: ['> 0%']})]))
        .pipe(minifyCSS())
        .pipe(rename('app.min.css'))
        .pipe(gulp.dest(path.join(__dirname, targetPath + '/css/')));
});

gulp.task('js', ['clean-js'], function () {
    return gulp.src(paths.js.app)
    //minify js file
        .pipe(uglifyjs())
        //output js minify
        .pipe(gulp.dest(path.join(__dirname, targetPath + '/js/')));
});

gulp.task('jspage', ['clean-js-page'], function () {
    return gulp.src(paths.jspage.app)
    //minify js file
        .pipe(uglifyjs())
        //output js minify
        .pipe(gulp.dest(path.join(__dirname, targetPath + '/js/pages/')));
});

gulp.task('watch-styling', function () {
    //spin up dev server
    browserSync.init({
        //server dev
        proxy: "http://advision.test/",
        //server static
        /*server: {
            baseDir: "./"
        }*/
    });

    //when scss files change, run sass task first and reload browserSync second
    gulp.watch('./sass/*.scss', ['sass']).on('change', function () {
        runs('sass');
        browserSync.reload();
    });
});

gulp.task('watch-js', function () {
    gulp.watch('./script/*.js', ['js']).on('change', function () {
        runs('js');
    })
})

gulp.task('watch-js-pages', function () {
    gulp.watch('./script/pages/*.js', ['jspage']).on('change', function () {
        runs('jspage');
    })
})

gulp.task('default', ['watch-styling', 'watch-js', 'watch-js-pages']);
