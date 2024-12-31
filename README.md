The `cpt-meta` library is a powerful package for managing WordPress custom post types, meta boxes, and custom fields. It simplifies the creation of structured content management systems by providing tools for defining, rendering, and saving metadata, all while integrating seamlessly with WordPress hooks and the REST API.


## Installation

Install the library via Composer:

```shell
composer require devuri/cpt-meta-box
```

---

## Features

- **Custom Post Types**: Easily create and manage WordPress custom post types.
- **Meta Boxes and Fields**: Define and manage meta boxes and custom fields with ease.
- **Metadata Retrieval**: Use the `Data` class to fetch and manage metadata programmatically.
- **Customizable Settings**: Extend the `Settings` class to define fields and sanitize data.
- **REST API Integration**: Extend WordPress REST API with custom endpoints.
- **Dynamic Field Rendering**: Render input fields, select boxes, checkboxes, and more using the `Form` helper.
- **Admin Column Customization**: Add sortable and custom columns in the WordPress admin.

---

## Example Use Case: Vehicle Management System

This library can be used to create a **Vehicle Management System**, where administrators manage vehicles with metadata such as name, description, type, specifications, and more.

---

## Example Usage

### Registering a Custom Post Type

Create a custom post type for "Vehicles":

```php
use Urisoft\PostMeta\PostType;

// Register a "Vehicle" post type
$vehiclePostType = new PostType('vehicle', 'Vehicle', 'Vehicles',[
	'menu_icon' => 'dashicons-car',
	'supports' => ['title', 'thumbnail'],
]);
$vehiclePostType->register();
```

---

### Adding Custom Settings

Extend the `Settings` class to define custom fields for vehicles:

```php
use Urisoft\PostMeta\Settings;

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
    }

    public function data($post_data): array
    {
        return [
            'vehicle_name' => sanitize_text_field($post_data['vehicle_name']),
            'description' => sanitize_textarea_field($post_data['description']),
            'type' => sanitize_text_field($post_data['type']),
            'top_speed' => intval($post_data['top_speed']),
            'is_electric' => !empty($post_data['is_electric']),
        ];
    }
}
```

### Registering a Meta Box

Use the `MetaBox` class to register the meta box:

```php
use Urisoft\PostMeta\MetaBox;

$vehicleSettings = new VehicleSettings('vehicle');

(new MetaBox($vehicleSettings, [
    'name' => 'Vehicle Details',
    'zebra' => true,
]))->register();
```

### Retrieving Saved Metadata

Retrieve metadata for a vehicle using the `Data` class:

```php
use Urisoft\PostMeta\Data;

$data = Data::init('vehicle');
$vehicle_meta = $data->meta($post_id);

echo 'Vehicle Name: ' . esc_html($vehicle_meta['vehicle_name']);
```

## Advanced Usage

### Adding Custom Fields

Add a color picker field for vehicles:

```php
echo self::form()->input('Color', $this->get_meta('color'), ['type' => 'color']);

// https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/color
```

### Custom Admin Columns

Add custom columns to the WordPress admin for the "Vehicle" post type:

```php
$vehiclePostType->addAdminColumns(
    function ($columns) {
        $columns['type'] = 'Type';
        $columns['speed'] = 'Top Speed';
        return $columns;
    },
    function ($column, $post_id) {
        if ($column === 'type') {
            echo get_post_meta($post_id, 'type', true);
        }
        if ($column === 'speed') {
            echo get_post_meta($post_id, 'top_speed', true) . ' mph';
        }
    }
);
```

---

### Adding Sortable Columns

Make the "Top Speed" column sortable:

```php
$vehiclePostType->addSortableColumns([
    'speed' => 'top_speed',
]);
```

---

### Filtering Vehicles by Metadata

Retrieve only electric vehicles:

```php
$electric_vehicles = $data->items(-1, [
    'meta_key' => 'is_electric',
    'meta_value' => true,
]);

foreach ($electric_vehicles as $vehicle) {
    $meta = $data->meta($vehicle->ID);
    echo '<h2>' . esc_html($meta['vehicle_name']) . '</h2>';
}
```

---

### Adding Custom REST Endpoints

Add a custom REST endpoint for vehicle details:

```php
$vehiclePostType->addCustomRestEndpoint('/custom-details', function ($data) {
    return [
        'message' => 'Custom endpoint data!',
    ];
}, 'GET');
```

---

## Notes

The `cpt-meta` library simplifies WordPress custom post type and metadata management, making it an excellent tool for building content management systems like a Vehicle Management System. Its flexibility and feature-rich architecture make it suitable for developers of all skill levels.

For further information, check out WordPress's official documentation:

- [Custom Post Types](https://developer.wordpress.org/plugins/post-types/)
- [Custom Meta Boxes](https://developer.wordpress.org/plugins/metadata/custom-meta-boxes/)
- [REST API](https://developer.wordpress.org/rest-api/)
