const gulp = require('gulp'),
    replace = require('gulp-replace'),
    pkg = require('./_build/config.json');

const year = new Date().getFullYear();

let phpversion;
let modxversion;
pkg.dependencies.forEach(function (dependency, index) {
    switch (pkg.dependencies[index].name) {
        case 'php':
            phpversion = pkg.dependencies[index].version.replace(/>=/, '');
            break;
        case 'modx':
            modxversion = pkg.dependencies[index].version.replace(/>=/, '');
            break;
    }
});

gulp.task('bump-copyright', function () {
    return gulp.src([
        'core/components/formit2db/model/formit2db/formit2db.class.php',
        'core/components/formit2db/src/FormIt2Db.php',
    ], {base: './'})
        .pipe(replace(/Copyright 2013(-\d{4})? by/g, 'Copyright ' + (year > 2013 ? '2013-' : '') + year + ' by'))
        .pipe(gulp.dest('.'));
});
gulp.task('bump-version', function () {
    return gulp.src([
        'core/components/formit2db/src/FormIt2db.php',
    ], {base: './'})
        .pipe(replace(/version = '\d+.\d+.\d+[-a-z0-9]*'/ig, 'version = \'' + pkg.version + '\''))
        .pipe(gulp.dest('.'));
});
gulp.task('bump-docs', function () {
    return gulp.src([
        'mkdocs.yml',
    ], {base: './'})
        .pipe(replace(/&copy; 2013(-\d{4})?/g, '&copy; ' + (year > 2013 ? '2013-' : '') + year))
        .pipe(gulp.dest('.'));
});
gulp.task('bump-requirements', function () {
    return gulp.src([
        'docs/index.md',
    ], {base: './'})
        .pipe(replace(/[*-] MODX Revolution \d.\d.*/g, '* MODX Revolution ' + modxversion + '+'))
        .pipe(replace(/[*-] PHP (v)?\d.\d.*/g, '* PHP ' + phpversion + '+'))
        .pipe(gulp.dest('.'));
});
gulp.task('bump', gulp.series('bump-copyright', 'bump-version', 'bump-docs', 'bump-requirements'));


// Default Task
gulp.task('default', gulp.series('bump'));