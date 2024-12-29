<?php

/*
 * This file is part of the cptMeta package.
 *
 * (c) Uriel Wilson
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Urisoft\PostMeta\MetaBox;
use Urisoft\PostMeta\Settings;

require_once \dirname(__FILE__) . '/bootstrap.php';

class Details extends Settings
{
    public function settings(): void
    {
        echo self::form()->input("Title", $this->get_meta("title"));
        echo self::form()->textarea(
            "Description",
            $this->get_meta("description")
        );
    }
}

// For `vehicle` post type. metabox uses class name `Details` as label.
$details = new Details("vehicle");

// dump($details);

// adds metabox no stripes.
$meta = new MetaBox($details);

// dump($meta);


exit;
