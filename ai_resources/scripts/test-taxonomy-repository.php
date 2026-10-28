<?php
/**
 * @deprecated Moved into the package. Prefer:
 *
 *   wp eval-file vendor/wonderwp/repository/tests/Integration/smoke-taxonomy.php
 *
 * This wrapper keeps old commands working.
 */

if (!defined('ABSPATH')) {
    fwrite(STDERR, "Must run via WP-CLI.\n");
    exit(1);
}

$target = dirname(__DIR__, 2) . '/vendor/wonderwp/repository/tests/Integration/smoke-taxonomy.php';
if (!is_file($target)) {
    // Path when wonderwp/repository is a path repo under vendor/wonderwp/Repository
    $alt = dirname(__DIR__, 2) . '/vendor/wonderwp/Repository/tests/Integration/smoke-taxonomy.php';
    $target = is_file($alt) ? $alt : $target;
}

if (!is_file($target)) {
    fwrite(STDERR, "Cannot find repository Integration smoke at vendor/wonderwp/repository/tests/Integration/smoke-taxonomy.php\n");
    exit(1);
}

if (defined('WP_CLI') && class_exists('WP_CLI')) {
    WP_CLI::warning('Deprecated: use vendor/wonderwp/repository/tests/Integration/smoke-taxonomy.php');
}

require $target;
