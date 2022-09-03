'use strict';

const { src, watch, series, dest } = require('gulp');
const autoPrefixer = require('gulp-autoprefixer');
const sass = require('gulp-sass')(require('sass'));
const clean = require('gulp-clean');

const wpPot = require('gulp-wp-pot');
const zip = require('gulp-zip');

const AUTOPREFIXER_BROWSERS = [
    "last 2 version",
    "> 1%",
    "ie >= 9",
    "ie_mob >= 10",
    "ff >= 30",
    "chrome >= 34",
    "safari >= 7",
    "opera >= 23",
    "ios >= 7",
    "android >= 4",
    "bb >= 10",
];

const buildSrcFiles = [
	'./**/*',
	'!./**/_*/',
	'!./node_modules/**',
	'!./.gitignore',
	'!./API.md',
	'!./package-lock.json',
	'!./package.json',
	'!./gulpfile.js',
	'!./README.md'
];

const frontendSassFiles = "./assets/dev/sass/*.scss";
const fontendJsFiles = "./assets/dev/js/*.js";
const adminSassFiles = "./assets/dev/admin/sass/*.scss";
const adminJsFiles = "./assets/dev/admin/js/*.js";

function makeFrontendCss() {
    return src(frontendSassFiles)
        .pipe(sass().on('error', sass.logError))
        .pipe(autoPrefixer(AUTOPREFIXER_BROWSERS))
        .pipe(dest('./assets/css'));
}

function makeFrontendJs() {
    return src(fontendJsFiles)
        .pipe(dest('./assets/js'));
}

function makeAdminCss() {
    return src(adminSassFiles)
        .pipe(sass().on('error', sass.logError))
        .pipe(autoPrefixer(AUTOPREFIXER_BROWSERS))
        .pipe(dest('./assets/admin/css'));
}

function makeAdminJs() {
    return src(adminJsFiles)
        .pipe(dest('./assets/admin/js'));
}

function startWatching() {
    watch(frontendSassFiles, makeFrontendCss);
    watch(fontendJsFiles, makeFrontendJs);
    watch(adminSassFiles, makeAdminCss);
    watch(adminJsFiles, makeAdminJs);
}

function deleteOld() {
    return src(["assets/css", "assets/admin", "assets/js"], {
        read: false,
    }).pipe(clean({ force: true }));
}

function makePot() {
    return src(['./**/*.php', './*.php'])
        .pipe(wpPot({
            domain: 'wp-breaking-news',
            package: 'WP Breaking News'
        }))
        .pipe(dest('./i18n/wp-breaking-news.pot'));
}

function buildZip() {
	return src(buildSrcFiles)
		.pipe(zip('wp-breaking-news.zip'))
		.pipe(dest('../'))
}

function buildRelease() {
	return src(buildSrcFiles)
		.pipe(dest('../wp-breaking-news-build'));
}

function deleteBuild() {
	return src(['../wp-breaking-news-build'], {
		read: false,
		allowEmpty: true,
	}).pipe(clean({ force: true }));
}

function deleteZip() {
	return src(['../wp-breaking-news.zip'], {
		read: false,
		allowEmpty: true,
	}).pipe(clean({ force: true }));
}

exports.build = series(
	deleteBuild,
	buildRelease,
);
exports.zip = series(
	deleteZip,
	buildZip,
);

exports.pot = makePot;

exports.clean = deleteOld;

exports.default = series(
    makeFrontendCss,
    makeFrontendJs,
    makeAdminCss,
    makeAdminJs,
    startWatching
);