<?php

declare(strict_types=1);

$autoload = dirname(__DIR__, 3) . '/vendor/autoload.php';
if (!is_file($autoload)) {
    fwrite(STDERR, "Run from wonderwp-base with Composer autoload available.\n");
    exit(1);
}

require_once $autoload;

$coreAutoload = dirname(__DIR__, 3) . '/web/app/mu-plugins/wonderwp-plugin-core/vendor/autoload.php';
if (is_file($coreAutoload)) {
    require_once $coreAutoload;
}

$coreSrc = dirname(__DIR__, 3) . '/web/app/mu-plugins/wonderwp-plugin-core/includes';
if (is_dir($coreSrc)) {
    spl_autoload_register(static function (string $class) use ($coreSrc): void {
        $prefix = 'WonderWp\\Plugin\\Core\\';
        if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
            return;
        }
        $relative = substr($class, strlen($prefix));
        $file = $coreSrc . '/' . str_replace('\\', '/', $relative) . '.php';
        if (is_file($file)) {
            require_once $file;
        }
    });
}

spl_autoload_register(static function (string $class): void {
    $prefix = 'WonderWp\\Base\\AiResources\\Tests\\';
    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }
    $relative = substr($class, strlen($prefix));
    $file = __DIR__ . '/' . str_replace('\\', '/', $relative) . '.php';
    if (is_file($file)) {
        require_once $file;
    }
});
