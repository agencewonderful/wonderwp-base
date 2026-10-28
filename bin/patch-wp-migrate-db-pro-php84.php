#!/usr/bin/env php
<?php
/**
 * PHP 8.4+: fix implicit nullable parameters in WP Migrate DB Pro's vendored php-di.
 *
 * The plugin ships an old php-di fork that still uses `Type $x = null` instead of
 * `?Type $x = null`. That triggers E_DEPRECATED on PHP 8.4+ during WP bootstrap
 * (visible in every WP-CLI integration smoke).
 *
 * Idempotent — safe to run from Composer post-install / post-update.
 */

$root = dirname(__DIR__);
$base = $root . '/web/app/plugins/wp-migrate-db-pro/vendor/php-di';

if (!is_dir($base)) {
    fwrite(STDOUT, "patch-wp-migrate-db-pro-php84: plugin vendor missing, skip\n");
    exit(0);
}

/**
 * Turn `Foo $bar = null` into `?Foo $bar = null` without double-prefixing.
 *
 * @param list<string> $typeNames Fully-qualified or short type names to rewrite
 */
$patchImplicitNullables = static function (string $contents, array $typeNames): array {
    $count = 0;
    foreach ($typeNames as $type) {
        $quoted = preg_quote($type, '/');
        $contents = preg_replace(
            '/(?<!\?)' . $quoted . '(\s+\$\w+\s*=\s*null)/',
            '?' . $type . '$1',
            $contents,
            -1,
            $replacements
        );
        if ($contents === null) {
            return [null, 0];
        }
        $count += $replacements;
    }

    return [$contents, $count];
};

$files = [
    $base . '/php-di/src/DI/Container.php' => [
        'ContainerInterface',
    ],
    $base . '/invoker/src/Invoker.php' => [
        'ParameterResolver',
        'ContainerInterface',
    ],
    $base . '/php-di/src/DI/Definition/Resolver/ParameterResolver.php' => [
        'MethodInjection',
        'ReflectionMethod',
    ],
    $base . '/php-di/src/DI/Definition/Source/CachedDefinitionSource.php' => [
        'Definition',
    ],
];

$changed = 0;
foreach ($files as $file => $types) {
    if (!is_file($file)) {
        continue;
    }
    $contents = file_get_contents($file);
    if ($contents === false) {
        fwrite(STDERR, "patch-wp-migrate-db-pro-php84: cannot read {$file}\n");
        exit(1);
    }

    [$updated, $count] = $patchImplicitNullables($contents, $types);
    if ($updated === null) {
        fwrite(STDERR, "patch-wp-migrate-db-pro-php84: preg_replace failed on {$file}\n");
        exit(1);
    }

    if ($count > 0 && $updated !== $contents) {
        if (file_put_contents($file, $updated) === false) {
            fwrite(STDERR, "patch-wp-migrate-db-pro-php84: cannot write {$file}\n");
            exit(1);
        }
        $changed += $count;
        fwrite(STDOUT, 'patched ' . str_replace($root . '/', '', $file) . " ({$count})\n");
    }
}

if ($changed === 0) {
    fwrite(STDOUT, "patch-wp-migrate-db-pro-php84: already applied or nothing to patch\n");
}

exit(0);
