<?php
namespace Deployer;

require 'recipe/common.php';

// Project name
set('application', 'my_project');

// Project repository
set('repository', '');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', false); 

// Shared files/dirs between deploys 
set('shared_files', []);
set('shared_dirs', []);

// Writable dirs by web server 
set('writable_dirs', []);


// Hosts
/* 
host('project.com')
    ->set('deploy_path', '~/{{application}}');    */ 
   
host('192.168.33.10')
    ->stage('dev')
    ->user('vagrant')
    ->set('deploy_path', '/var/www/test');

// Tasks
desc('upload');
task('file:upload', function () {
    upload('./src/', '{{release_path}}/static');
});

task('finished', function () {
    writeln('发布完成');
});

desc('Deploy your project');
task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:writable',
    'deploy:vendors',
    'deploy:clear_paths',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
    'success'
]);

// [Optional] If deploy fails automatically unlock.
after('deploy:update_code', 'file:upload');
after('deploy:failed', 'deploy:unlock');
after('success', 'finished');
