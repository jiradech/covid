/*=========== GULP + Plugins init ==============*/


var gulp = require('gulp'),
	plumber = require('gulp-plumber'), // generates an error message
	prefixer = require('gulp-autoprefixer'), // automatically prefixes to css properties
	sass = require('gulp-sass'), // for compiling scss-files to css
	browserSync = require('browser-sync').create(), // for online synchronization with the browser
	imagemin = require('gulp-imagemin'), // for minimizing images-files
	webp = require('gulp-webp'), // for convert png in webp
	cache = require('gulp-cache'), // connecting the cache library
	htmlhint = require("gulp-htmlhint"), // for HTML-validation
	cleanCSS = require('gulp-clean-css'), // plugin to minimize CSS
	rename = require('gulp-rename'), // to rename files
	runSequence = require('run-sequence'); // for sequential execution of Gulp-tasks


/*=========== Compile SCSS ==============*/

gulp.task('sass', function() {

	gulp.src('./HTML/sass/**/*.scss')
		.pipe(plumber())
		.pipe(sass())
		.pipe(prefixer(
			{
				browsers: ['last 12 versions'],
				cascade: false
			}
		))
		.pipe(gulp.dest('./HTML/css'))
		.pipe(cleanCSS())
		.pipe(rename({
			suffix: '.min'
		}))
		.pipe(gulp.dest('./HTML/css'))
		.pipe(browserSync.stream());
});


/*=========== Watch ==============*/
gulp.task('watch', ['sass'], function() {

	browserSync.init({
		server: "./"
	});

	gulp.watch("./HTML/sass/**/*.scss", ['sass']);

});


/*=========== Minimization IMAGE ==============*/

gulp.task('images', function () {
	gulp.src('./HTML/img/**/*.*')
		.pipe(cache(imagemin({
			interlaced: true
		})))
		.pipe(gulp.dest('./HTML/img'));
});

/*=========== Convert PNG in WEBP ==============*/

gulp.task('convert-webp', function () {
	gulp.src('./HTML/img/**/*.png')
		.pipe(webp({quality: 100}))
		.pipe(gulp.dest('./HTML/img'));
});


/*============= HTML-validator ==============*/

gulp.task('html-valid', function () {
	gulp.src("./HTML/*.html")
		.pipe(htmlhint());
});

/*=========== Minimization SVG ==============*/

gulp.task('svg-min', function () {
	gulp.src('./HTML/svg-icons/*.svg')
		.pipe(svgmin({
			plugins: [{
				removeDoctype: true
			}, {
				removeComments: true
			}, {
				cleanupNumericValues: {
					floatPrecision: 2
				}
			}, {
				convertColors: {
					names2hex: true,
					rgb2hex: true
				}
			}]
		}))
		.pipe(gulp.dest('./HTML/svg-icons/*.svg'));
});


/*============= Join tasks ==============*/

gulp.task('default', function (callback) {
	runSequence(['sass', 'watch'],
		callback
	)
});

gulp.task('build', function(done) {
	runSequence('sass', 'html-valid', 'svg-min', 'images', 'convert-webp', done);
});