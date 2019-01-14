
'use strict';
/*environment variable*/
const flag = (process.env.NODE_ENV=='development') ? 'development' : 'production';
const status = (flag === 'development') ? true :false;

/* loading plugins */
const { gulp,src,dest,task,watch,parallel,series } = require('gulp');
const del         = require('del'),
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
      log         = require('fancy-log');

/*
* setting parth requirements 
*/
const theme_dir  = dir.dirname(__dirname) + '/' ;

const path       = 
{
  /*              source folders              dest files                       dest folders   */
	style: [theme_dir + 'sources/sass/'  ,theme_dir + 'assets/css/*.*'  ,theme_dir + 'assets/css' ],
	js:    [theme_dir + 'sources/js/'    ,theme_dir + 'assets/js/*.*'   ,theme_dir + 'assets/js'  ],
};

/*
* browser sync func 
*/
function bSync(done){
  browserSync.init({
        proxy:"localhost:8888",
        open:true,
        ghostMode:{
          click:true,
          scroll:true
      }
  });
  done();
}

/*
*  browser reload on changing
*/
function bReload(done){
  browserSync.reload();
  done();
}

/* theme main css func*/
function css(){
   css.description = 'css is on build';
  return src(path.style[0] + 'main.scss')
         .pipe(sass({
             project:theme_dir,
             sass:'sources/sass',
             css:'assets/css',
             style: 'expanded',
             sourcemap:status
         }).on('error',util.log))
         .pipe(postcss([prefix('last 2 versions','> 2%')]))
         .pipe(gIf(flag === 'production',cleanCss()))
         .pipe(gIf(flag === 'production',rename({suffix:'.min'})))
         .pipe(dest(path.style[2]))
}

/* theme main js func*/
function js(){
  js.description = 'js stuff in on the run';
  return src(path.js[0]+'main.js')
         .pipe(sourcemaps.init())
         .pipe(jshint())
         .pipe(jshint.reporter('default',{ verbose: true }))
         .pipe(jshint.reporter('fail'))
         .pipe(gIf(flag === 'production',uglify()))
         .pipe(gIf(flag === 'production',rename({suffix:'.min'})))
         .pipe(sourcemaps.write('maps'))
         .pipe(dest(path.js[2]));
}

/* watch files changing */
function watchFiles(){
  watch( path.style[0] + '**/*.scss',series( css,bReload ) );
  watch( path.js[0] + '*.js',series( js,bReload ) );
  watch( theme_dir + '**/*.php',bReload );
}

/* clean */
function clean(done){
  del([path.style[1],path.js[1],theme_dir + 'assets/**/maps'],{force:true});
  done();
}

/*set task */
const build = series(clean,parallel(css,js));
const watcher = series(bSync,watchFiles);

task('default',series( build ,watcher) );


