module.exports = function(grunt) {
  'use strict';

  // Load all tasks
  require('load-grunt-tasks')(grunt);
  // Show elapsed time
  require('time-grunt')(grunt);

  // Force use of Unix newlines
  grunt.util.linefeed = '\n';

  // Find what the current theme's directory is, relative to the WordPress root
  var path = process.cwd().replace(/^[\s\S]+\/wp-content/, "\/wp-content");

  var cssLessFiles = {
    'css/gutenberg.css': 'less/gutenberg.less',
    'css/style.css': 'less/style.less',
    'css/editor-style.css': 'less/editor-style.less',
    'homepages/assets/css/single.css': 'homepages/assets/less/single.less',
    'homepages/assets/css/top-stories.css': 'homepages/assets/less/top-stories.less',
    'homepages/assets/css/legacy-three-column.css': 'homepages/assets/less/legacy-three-column.less'
  };

  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),

    less: {
      compile: {
        options: {
          paths: ['less'],
          sourceMap: true,
          outputSourceFiles: true,
          sourceMapBasepath: path,
        },
        files: cssLessFiles
      }
    },

    uglify: {
      target: {
        options: {
          report: 'gzip'
        },
        files: [{
          expand: true,
          cwd: 'js',
          src: [
            'custom-less-variables.js',
            'custom-sidebar.js',
            'custom-term-icons.js',
            'featured-media.js',
            'floating-social-buttons.js',
            'image-widget.js',
            'largoCore.js',
            'load-more-posts.js',
            'navigation.js',
            'top-terms.js',
            'update-page.js',
            'widgets-php.js',
            '!*.min.js'
          ],
          dest: 'js',
          ext: '.min.js'
        }]
      }
    },

    cssmin: {
      target: {
        options: {
          report: 'gzip'
        },
        files: [
          {
          expand: true,
          cwd: 'css',
          src: ['*.css', '!*.min.css'],
          dest: 'css',
          ext: '.min.css'
        },
        {
          expand: true,
          cwd: 'homepages/assets/css',
          src: ['*.css', '!*.min.css'],
          dest: 'homepages/assets/css',
          ext: '.min.css'
        }
        ]
      }
    },

    shell: {
      apidocs: {
        command: [
          'cd docs',
          'rm -Rf api _build/html/api _build/doctrees/api',
          'make php',
        ].join('&&'),
        options: {
          stdout: true
        }
      },
      sphinx: {
        command: [
          'cd docs',
          'make html',
        ].join('&&'),
        options: {
          stdout: true
        }
      },
      msgmerge: {
        command: [
          'msgmerge -o lang/es_ES.po.merged lang/es_ES.po lang/largo.pot',
          'mv lang/es_ES.po.merged lang/es_ES.po'
        ].join('&&')
      },
      pot: {
        command: [
          'wp i18n make-pot . lang/largo.pot'
        ].join('&&'),
        options: {
          stdout: true
        }
      },
    },

    watch: {
      less: {
        files: [
          'less/**/*.less',
          'less/**/**/*.less',
          'homepages/assets/less/**/*.less'
        ],
        tasks: [
          'less:compile',
          'cssmin'
        ]
      },
      sphinx: {
        files: ['docs/*.rst', 'docs/*/*.rst'],
        tasks: ['docs']
      }
    },

    po2mo: {
      files: {
        src: 'lang/*.po',
        expand: true
      }
    }
  });

  // Build API docs only
  grunt.registerTask('apidocs', ['shell:apidocs']);

  // Build ALL docs
  grunt.registerTask('docs', ['shell:sphinx']);

  // Former grunt-pot
  grunt.registerTask('pot', ['shell:pot']);

  // Build assets, docs and language files
  grunt.registerTask('build', 'Build assets, docs and language files', [
    'less',
    'cssmin',
    'uglify',
    'apidocs',
    'docs',
    'pot',
    'shell:msgmerge'
  ]);
}
