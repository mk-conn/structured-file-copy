<?php

declare(strict_types=1);

use Composer\InstalledVersions;
use DI\ContainerBuilder;

if (InstalledVersions::isInstalled('mk-conn/structured-file-copy', true)) {
    // global composer
    require InstalledVersions::getInstallPath('mk-conn/structured-file-copy') . '/autoload.php';
} else {
    // local composer
    require __DIR__ . '/../vendor/autoload.php';
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
