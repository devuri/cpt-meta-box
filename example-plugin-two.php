<?php

use Urisoft\PostMeta\Data;
use Urisoft\PostMeta\MetaBox;
use Urisoft\PostMeta\PostType;
use Urisoft\PostMeta\Settings;

/**
 * Plugin Name:       Vehicle Management v2 (with data autosave)
 * Plugin URI:        https://example.com/plugins
 * Description:       An example plugin using the `cpt-meta` library to manage vehicles in WordPress.
 * Version:           2.0
 * Requires at least: 4.0
 * Requires PHP:      7.4
 * Author:            Your Name
 * Author URI:        https://example.com
 * Text Domain:       wp-vehicle-management
 * License:           GPLv2
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// start plugin.
if ( ! \defined('ABSPATH')) {
    exit;
}

// Autoload `cpt-meta` library
require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';


$vehiclePostType = new PostType('vehicle', 'Vehicle', 'Vehicles', [
    'menu_icon' => 'dashicons-car',
    'supports' => ['title', 'thumbnail'],
]);
$vehiclePostType->register();


class VehicleSettings extends Settings
{
    public function settings(): void
    {
        $this->input('Vehicle Name', [
            'placeholder' => 'Enter the vehicle name',
        ]);

        $this->textarea('Description');

        // TODO fix: Error: Call to undefined function get_userdata() i
        // $this->editor('Promo');

        $this->select('Type', [
            'car' => 'Car',
            'truck' => 'Truck',
            'motorcycle' => 'Motorcycle',
            'selected' => $this->getMeta('type'),
        ]);

        $this->input('Top Speed (mph)', [
            'type' => 'number',
            'placeholder' => 'Enter top speed in mph',
        ]);

        $this->input('Front Brakes');
        $this->input('Rear Brakes');

        $this->input('Colours', [
            'type' => 'color',
            'placeholder' => 'Available color',
        ]);
    }
}

$vehicleSettings = (new VehicleSettings('vehicle'));
(new MetaBox($vehicleSettings, [
    'name' => 'Vehicle Details',
    'zebra' => true,
]))->register();
