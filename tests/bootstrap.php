<?php

/*
 * This file is part of the cptMeta package.
 *
 * (c) Uriel Wilson
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once \dirname(__FILE__, 2) . '/vendor/autoload.php';

// app test path
\define('APP_TEST_PATH', __DIR__);

if ( ! \function_exists('sanitize_title')) {
    function sanitize_title($title)
    {
        return strtolower($title);
    }
}
