<?php

use Urisoft\PostMeta\Data;
use Urisoft\PostMeta\MetaBox;
use Urisoft\PostMeta\PostType;
use Urisoft\PostMeta\Settings;

/**
 * Plugin Name:       Vehicle Management v1 (manual return data and autosave)
 * Plugin URI:        https://example.com/plugins
 * Description:       An example plugin using the `cpt-meta` library to manage vehicles in WordPress.
 * Version:           1.0
 * Requires at least: 4.0
 * Requires PHP:      7.4
 * Author:            Your Name
 * Author URI:        https://example.com
 * Text Domain:       wp-vehicle-management
 * License:           GPLv2
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// start plugin.
if ( ! \defined( 'ABSPATH' ) ) {
    exit;
}

// Autoload `cpt-meta` library
require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';


$vehiclePostType = new PostType('vehicle', 'Vehicle', 'Vehicles',[
	'menu_icon' => 'dashicons-car',
	'supports' => ['title', 'thumbnail'],
]);
$vehiclePostType->register();


class VehicleSettings extends Settings
{
    public function settings(): void
    {
        echo self::form()->input('Vehicle Name', $this->getMeta('vehicle_name'), [
            'placeholder' => 'Enter the vehicle name',
        ]);
        echo self::form()->textarea('Description', $this->getMeta('description'));
		echo self::form()->select([
            'car' => 'Car',
            'truck' => 'Truck',
            'motorcycle' => 'Motorcycle',
            'selected' => $this->getMeta('type'),
        ],'Type');
        echo self::form()->input('Top Speed (mph)', $this->getMeta('top_speed'), [
            'type' => 'number',
            'placeholder' => 'Enter top speed in mph',
        ]);

		echo self::form()->input('Colours', $this->getMeta('colours'), [
			'type' => 'color',
			'placeholder' => 'Available color',
		]);
    }

    public function data($postData): array
    {
        return [
            'vehicle_name' => sanitize_text_field($postData['vehicle_name']),
            'description' => sanitize_textarea_field($postData['description_textarea']),
            'type' => sanitize_text_field($postData['type']),
            'top_speed' => intval($postData['top_speed_mph']),
            'is_electric' => !empty($postData['is_electric']),
            'colours' => sanitize_text_field($postData['colours']),
        ];
    }
}

(new MetaBox(new VehicleSettings('vehicle'), [
    'name' => 'Vehicle Details',
    'zebra' => true,
]))->register();




















<?php

use Urisoft\PostMeta\PostType;
use Urisoft\PostMeta\Settings;
use Urisoft\PostMeta\MetaBox;
use Urisoft\PostMeta\Data;

/**
 * Plugin Name:       Vehicle Management
 * Plugin URI:        https://example.com/plugins
 * Description:       An example plugin using the `cpt-meta` library to manage vehicles in WordPress.
 * Version:           1.0
 * Requires at least: 4.0
 * Requires PHP:      7.4
 * Author:            Your Name
 * Author URI:        https://example.com
 * Text Domain:       wp-vehicle-management
 * License:           GPLv2
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// start plugin.
if ( ! \defined( 'ABSPATH' ) ) {
    exit;
}

// Autoload `cpt-meta` library
require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

class VehicleManager
{
    public function __construct()
    {
        add_action('init', [$this, 'registerVehiclePostType']);
        add_action('add_meta_boxes', [$this, 'registerVehicleMetaBox']);
        add_action('rest_api_init', [$this, 'registerVehicleRestEndpoint']);
    }

    public function registerVehiclePostType(): void
    {
        $vehiclePostType = new PostType('vehicle', 'Vehicle', 'Vehicles', [
            'menu_icon' => 'dashicons-car',
            'supports' => ['title', 'editor', 'thumbnail', 'custom-fields'],
        ]);

		$vehiclePostType->register();

        $vehiclePostType->addTaxonomy('vehicle_type', 'Vehicle Type', 'Vehicle Types', [
            'hierarchical' => true,
            'show_in_rest' => true,
        ]);

        $vehiclePostType->addAdminColumns(
            function ($columns) {
                $columns['type'] = 'Type';
                $columns['speed'] = 'Top Speed';
                return $columns;
            },
            function ($column, $postId) {
                if ($column === 'type') {
                    echo get_post_meta($postId, 'type', true);
                }
                if ($column === 'speed') {
                    echo get_post_meta($postId, 'top_speed', true) . ' mph';
                }
            }
        );

        $vehiclePostType->addSortableColumns([
            'type' => 'type',
            'speed' => 'top_speed',
        ]);

        $vehiclePostType->addDefaultMetaFields([
            'top_speed' => 0,
            'type' => 'Unknown',
        ]);

        $vehiclePostType->register();
    }

    public function registerVehicleMetaBox(): void
    {
        $vehicleSettings = new VehicleSettings('vehicle');

        (new MetaBox($vehicleSettings, [
            'name' => 'Vehicle Details',
            'zebra' => true,
        ]))->register();
    }

    public function registerVehicleRestEndpoint(): void
    {
        register_rest_route('vehicle/v1', '/custom-data', [
            'methods' => 'GET',
            'callback' => function () {
                $data = Data::init('vehicle');
                $vehicles = $data->items(-1);

                return array_map(function ($vehicle) use ($data) {
                    $meta = $data->meta($vehicle->ID);
                    return [
                        'id' => $vehicle->ID,
                        'title' => $vehicle->post_title,
                        'type' => $meta['type'],
                        'top_speed' => $meta['top_speed'],
                        'is_electric' => $meta['is_electric'] ? 'Yes' : 'No',
                    ];
                }, $vehicles);
            },
        ]);
    }
}

class VehicleSettings extends Settings
{
    public function settings(): void
    {
        echo self::form()->input('Vehicle Name', $this->getMeta('vehicle_name'), [
            'placeholder' => 'Enter the vehicle name',
        ]);
        echo self::form()->textarea('Description', $this->getMeta('description'), [
            'placeholder' => 'Enter a description',
        ]);
        echo self::form()->select('Type', $this->getMeta('type'), [
            'car' => 'Car',
            'truck' => 'Truck',
            'motorcycle' => 'Motorcycle',
        ]);
        echo self::form()->input('Top Speed (mph)', $this->getMeta('top_speed'), [
            'type' => 'number',
            'placeholder' => 'Enter top speed in mph',
        ]);
        echo self::form()->checkbox('Electric Vehicle', $this->getMeta('is_electric'));
    }

    public function data($postData): array
    {
        return [
            'vehicle_name' => sanitize_text_field($postData['vehicle_name']),
            'description' => sanitize_textarea_field($postData['description']),
            'type' => sanitize_text_field($postData['type']),
            'top_speed' => intval($postData['top_speed']),
            'is_electric' => !empty($postData['is_electric']),
        ];
    }
}

// Initialize the Vehicle Manager
new VehicleManager();
