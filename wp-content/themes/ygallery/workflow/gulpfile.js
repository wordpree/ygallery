

/*environment variable*/
var flag = (process.env.NODE_ENV=='development') ? 'development' : 'production';
var status = (flag === 'development') ? true :false;

/* loading plugins */
var gulp        = require('gulp'),
    del         = require('del'),
    dir         = require('path'),
    gIf         = require('gulp-if'),
    util        = require('gulp-util'),
    notify      = require('gulp-notify'),
    sourcemaps  = require('gulp-sourcemaps'),
    sass        = require('gulp-compass'),
    cleanCss    = require('gulp-clean-css'),
    jshint      = require('gulp-jshint'),
    uglify      = require('gulp-uglify'),
    concat      = require('gulp-concat'),
    prefix      = require('autoprefixer'),
    postcss     = require('gulp-postcss'),
    browserSync = require('browser-sync').create(),
    runSeq      = require('run-sequence'),
    rename      = require('gulp-rename'),
    cache       = require('gulp-cache'),
    stats       = require('gulp-stats');
stats(gulp);

/*
* setting parth requirements 
*/
var themes_dir = dir.dirname(__dirname);
var name       = 'storefront-child';
var theme_dir  = themes_dir + '/' + name + '/';
var plugin_dir = dir.join( dir.dirname( themes_dir ),'plugins/fancy-slider/' );

var path       = 
{
	style: [theme_dir + 'sources/sass/'  ,theme_dir + 'assets/sass/*.*'  ,theme_dir + 'assets/sass/'  ],
	js:    [theme_dir + 'sources/js/'    ,theme_dir + 'assets/js/*.*'    ,theme_dir + 'assets/js/'    ],
	fonts: [theme_dir + 'sources/fonts/' ,theme_dir + 'assets/fonts/*.*' ,theme_dir + 'assets/fonts/' ],
	img:   [theme_dir + 'sources/images/',theme_dir + 'assets/images/*.*',theme_dir + 'assets/images/'],
  plugin_style1: [plugin_dir + 'sources/sass/'  ,plugin_dir + 'admin/css/*.*' ,plugin_dir + 'admin/css/' ],
  plugin_js1:    [plugin_dir + 'sources/js/'    ,plugin_dir + 'admin/js/*.*'  ,plugin_dir + 'admin/js/'  ],
  plugin_style2: [plugin_dir + 'sources/sass/'  ,plugin_dir + 'public/css/*.*',plugin_dir + 'public/css/'],
  plugin_js2:    [plugin_dir + 'sources/js/'    ,plugin_dir + 'public/js/*.*' ,plugin_dir + 'public/js/' ]
};
/*
* gulp tasks: 
*/

gulp.task('browser-sync',function(){
      browserSync.init({
      	proxy:"localhost:8888",
        open:true,
      	ghostMode:{
      		click:true,
      		scroll:true
      	}
      });
});

gulp.task('cleanCache',function(){
      cache.clearAll();
});
/* plugin admin task */
gulp.task('admin-sass',function(){
  return gulp.src(path.plugin_style1[0] + 'fancy-slider-admin.scss')
         .pipe(sass({
             project:plugin_dir,
             sass:'sources/sass',
             css:'admin/css',
             style: 'expanded',
             sourcemap:status
         }).on('error',util.log))
         .pipe(postcss([prefix('last 2 versions','> 2%')]))
         .pipe(gIf(flag === 'production',cleanCss()))
         .pipe(gIf(flag === 'production',rename({suffix:'.min'})))
         .pipe(gulp.dest(path.plugin_style1[2]))
});

gulp.task('admin-js',function(){
  return gulp.src(path.plugin_js1[0]+'fancy-slider-admin.js')
         .pipe(sourcemaps.init())
         .pipe(jshint())
         .pipe(jshint.reporter('jshint-stylish',{ verbose: true }))
         .pipe(jshint.reporter('fail'))
         .pipe(gIf(flag === 'production',uglify()))
         .pipe(gIf(flag === 'production',rename({suffix:'.min'})))
         .pipe(sourcemaps.write('maps'))
         .pipe(gulp.dest(path.plugin_js1[2]));
});

/* plugin public task */
gulp.task('pub-sass',function(){
  return gulp.src(path.plugin_style2[0] + 'fancy-slider-public.scss')
         .pipe(sass({
             project:plugin_dir,
             sass:'sources/sass',
             css:'public/css',
             style: 'expanded',
             sourcemap:status
         }).on('error',util.log))
         .pipe(postcss([prefix('last 2 versions','> 2%')]))
         .pipe(gIf(flag === 'production',cleanCss()))
         .pipe(gIf(flag === 'production',rename({suffix:'.min'})))
         .pipe(gulp.dest(path.plugin_style2[2]))
});

gulp.task('pub-js',function(){
  return gulp.src(path.plugin_js2[0]+'fancy-slider-public.js')
         .pipe(sourcemaps.init())
         .pipe(jshint())
         .pipe(jshint.reporter('jshint-stylish',{ verbose: true }))
         .pipe(jshint.reporter('fail'))
         .pipe(gIf(flag === 'production',uglify()))
         .pipe(gIf(flag === 'production',rename({suffix:'.min'})))
         .pipe(sourcemaps.write('maps'))
         .pipe(gulp.dest(path.plugin_js2[2]));
});

/*  storefront child theme task */
gulp.task('sass',function(){
	return gulp.src(path.style[0] + 'style.scss')
	       .pipe(sass({
	       	   project:theme_dir,
             sass:'sources/sass',
             css:'assets/sass',
	       	   style: 'expanded',
             image: 'assets/images',
             font: 'assets/fonts',
             javascript: 'assets/js/**/',
	       	   sourcemap:status
	       }).on('error',util.log))
	       .pipe(postcss([prefix('last 2 versions','> 2%')]))
	       .pipe(gIf(flag === 'production',cleanCss()))
	       .pipe(gIf(flag === 'production',rename({suffix:'.min'})))
	       .pipe(gulp.dest(path.style[2]))
});

gulp.task('js',function(){
	return gulp.src(path.js[0]+'*.js')
	       .pipe(sourcemaps.init())
	       .pipe(jshint())
	       .pipe(jshint.reporter('jshint-stylish',{ verbose: true }))
	       .pipe(jshint.reporter('fail'))
	       .pipe(gIf(flag === 'production',uglify()))
	       .pipe(gIf(flag === 'production',rename({suffix:'.min'})))
	       .pipe(sourcemaps.write('../maps'))
	       .pipe(gulp.dest(path.js[2]));
});

gulp.task('fonts',function(){
    return gulp.src(path.fonts[0]+ '*.*')
           .pipe(gulp.dest(path.fonts[2]));
});

gulp.task('css-reload',['sass'],function(){
  browserSync.reload();
});

gulp.task('js-reload',['js'],function(){
  browserSync.reload();
});

gulp.task('admin-css-reload',['admin-sass'],function(){
  browserSync.reload();
});

gulp.task('admin-js-reload',['admin-js'],function(){
  browserSync.reload();
});

gulp.task('pub-css-reload',['pub-sass'],function(){
  browserSync.reload();
});

gulp.task('pub-js-reload',['pub-js'],function(){
  browserSync.reload();
});

gulp.task('fonts-reload',['fonts'],function(){
  browserSync.reload();
});

gulp.task('clear',function(){
	return del.sync([path.style[1],path.js[1],theme_dir + 'assets/maps'],{force:true});
});

gulp.task('watch',['browser-sync'],function(){
    gulp.watch(path.style[0] + '**/*.scss',['css-reload']);
    gulp.watch(path.js[0]    + '*.js'     ,['js-reload']);
    gulp.watch(path.plugin_style1[0] + '*.scss',['admin-css-reload']);
    gulp.watch(path.plugin_js1[0]    + '*.js'  ,['admin-js-reload']);
    gulp.watch(path.plugin_style2[0] + '*.scss',['pub-css-reload']);
    gulp.watch(path.plugin_js2[0]    + '*.js'  ,['pub-js-reload']);
    gulp.watch(path.fonts[0] + '*.*'      ,['fonts-reload']);
    gulp.watch(theme_dir   + '**/*.php' ).on('change',browserSync.reload);
    gulp.watch(plugin_dir  + '**/*.php' ).on('change',browserSync.reload);
});

gulp.task('default',['clear','fonts','sass','js','pub-sass','admin-sass','pub-js','admin-js','watch']);














/*gulp.task('set-prod',function(){
	return process.env.NODE_ENV='production';
});

gulp.task('set-dev',function(){
	return process.env.NODE_ENV='development';
});

gulp.task('dev',['set-dev','default']);

gulp.task('prod',['set-prod','default']);*/
