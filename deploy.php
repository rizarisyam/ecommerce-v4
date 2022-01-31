<?php
namespace Deployer;

require 'recipe/laravel.php';

// Config

set('repository', 'https://github.com/rizarisyam/ecommerce-v4.git');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

host('128.199.224.71')
    ->set('remote_user', 'riza')
    ->set('deploy_path', '~/var/www/ecommerce-v4');

// Tasks

task('build', function () {
    cd('{{release_path}}');
    run('npm run build');
});

after('deploy:failed', 'deploy:unlock');
