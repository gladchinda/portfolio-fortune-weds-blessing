// Require all packages
const gulp 			= require('gulp');
const path			= require('path');
const less 			= require('gulp-less');
const gutils 		= require('gulp-util');
const concat 		= require('gulp-concat');
const cssmin 		= require('gulp-csso');
const uglify 		= require('gulp-uglify');
const plumber 		= require('gulp-plumber');
const sourcemaps 	= require('gulp-sourcemaps');
const autoprefixer 	= require('gulp-autoprefixer');

// Configure paths
const APP_PATH 		= path.join(__dirname, '../../../');
const BUILD_PATH 	= path.normalize(APP_PATH + '/storage/app/public/assets/');
const ASSETS_PATH	= path.normalize(APP_PATH + '/resources/assets/app/');
const BOWER_PATH	= path.normalize(ASSETS_PATH + '/bower_components/');

const BOOTSTRAP 	= path.normalize(BOWER_PATH + '/bootstrap/dist/');

// Create vendor.css file
gulp.task('vendor-css', () => {

	const vendors = [
		BOOTSTRAP + '/css/bootstrap.min.css',
		BOOTSTRAP + '/css/bootstrap-theme.min.css',
	];

	return gulp.src(vendors)
		.pipe(sourcemaps.init({
			loadMaps: true,
			largeFile: true,
		}))
		.pipe(concat('vendor.css'))
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest(BUILD_PATH + '/css'));

});

// Create app.css file
gulp.task('build-css', () =>

	gulp.src(ASSETS_PATH + '/less/app.less')
		.pipe(sourcemaps.init())
		.pipe(less())
		.pipe(autoprefixer({
			browsers: ['last 6 versions']
		}))
		.pipe(cssmin())
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest(BUILD_PATH + '/css'))

);

// Copy icon fonts
gulp.task('copy-fonts', () =>

	gulp.src(BOOTSTRAP + '/fonts/**/*')
		.pipe(gulp.dest(BUILD_PATH + '/fonts'))

);

gulp.task('css', ['vendor-css', 'build-css', 'copy-fonts']);

// Create libs.js file
gulp.task('libs-js', () => {

	const libs = [
		BOWER_PATH + '/jquery/dist/jquery.min.js',
		BOOTSTRAP + '/js/bootstrap.min.js',
		BOWER_PATH + '/angular/angular.min.js',
	];

	return gulp.src(libs)
		.pipe(sourcemaps.init({
			loadMaps: true,
			largeFile: true,
		}))
		.pipe(concat('libs.js', {newLine: ';'}))
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest(BUILD_PATH + '/js'));

});

// Create app.js file
gulp.task('build-js', () => {

	return gulp.src(ASSETS_PATH + '/js/app.js')
		.pipe(sourcemaps.init({
			loadMaps: true,
			largeFile: true,
		}))
		.pipe(uglify())
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest(BUILD_PATH + '/js'));

});

gulp.task('js', ['libs-js', 'build-js']);

gulp.task('build', ['css', 'js']);

gulp.task('watch', () => {
	gulp.watch(ASSETS_PATH + '/less/**/*.less', ['build-css']);
	gulp.watch(ASSETS_PATH + '/js/app.js', ['build-js']);
});

gulp.task('default', ['build']);
