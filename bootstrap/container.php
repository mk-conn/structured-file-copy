<?php

declare(strict_types=1);

use DI\ContainerBuilder;

if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    // local composer
    require __DIR__ . '/../vendor/autoload.php';
} elseif (file_exists(__DIR__ . '/../../../autoload.php')) {
    // global composer
    require __DIR__ . '/../../../autoload.php';
} else {
    echo 'Please run composer install';

    exit(1);
}

$cacheDI = true;
$arrEnv = $_ENV;
$env = getenv('APP_ENV') ?: 'production';

if (in_array($env, ['testing', 'development'])) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);

    $cacheDI = false;
}

$builder = new ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/dependencies.php');

if ($cacheDI) {
    $builder->enableCompilation(dirname(__DIR__) . '/cache/di');
    $builder->writeProxiesToFile(true, dirname(__DIR__) . '/cache/di/proxies');
}

try {
    return $builder->build();
} catch (Exception $e) {
    echo $e->getMessage();

    exit(1);
}
