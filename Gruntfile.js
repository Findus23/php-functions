'use strict';
module.exports = function(grunt) {
	// load all grunt tasks
	require('time-grunt')(grunt);
	grunt.initConfig();
	grunt.loadNpmTasks('grunt-notify');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-exec');
	//
	grunt.initConfig({
		watch: {
			options: {
				atBegin: true,
			},
			php: {
				files: ['functions.php'],
				tasks: 'php',
			}
		},
		exec: {
			document: {
				command: 'vendor/bin/phpdoc -d ./ -i /vendor/,/plugins/,node_modules/'
			},
			correct: {
				command: 'vendor/bin/phpcbf --standard=PSR2 functions.php'
			},
			codesniffer: {
				command: 'vendor/bin/phpcs --standard=PSR2 functions.php'
			}
		},
	});
	// the default task (running "grunt" in console) is "watch"
	grunt.registerTask('default', ['watch']);
	grunt.registerTask('php', ['exec:document', 'exec:correct', 'exec:codesniffer']);
};
