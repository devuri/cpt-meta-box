The `MetaBox` class simplifies the creation and management of custom meta boxes in WordPress. It integrates seamlessly with the `Settings` class to define custom fields and save their data. This class allows developers to focus on creating meta box functionality without worrying about boilerplate code.

## Features

- **Dynamic Meta Box Creation**: Automatically generates meta boxes based on the `Settings` class.
- **Customizable Fields**: Use the `Settings` class to define the fields displayed in the meta box.
- **Data Handling**: Automatically handles saving and retrieving meta data.
- **Supports Multiple Configurations**: Customize labels, zebra styles, and more.


## How It Works

1. Extend the `Settings` class to define the fields and data logic for the meta box.
2. Use the `MetaBox` class to register the meta box and associate it with a custom post type.
3. The entered data is automatically sanitized and saved using the logic defined in the `Settings` class.


## Usage

### Creating a Meta Box

```php
use Urisoft\PostMeta\MetaBox;
use Urisoft\PostMeta\Settings;

class VehicleSettings extends Settings
{
    public function settings(): void
    {
        echo self::form()->input('Vehicle Model', $this->get_meta('vehicle_model'));
        echo self::form()->textarea('Description', $this->get_meta('description'));
        echo self::form()->select('Type', $this->get_meta('type'), [
            'car' => 'Car',
            'truck' => 'Truck',
            'motorcycle' => 'Motorcycle',
        ]);
    }

    public function data($post_data): array
    {
        return [
            'vehicle_model' => sanitize_text_field($post_data['vehicle_model']),
            'description' => sanitize_textarea_field($post_data['description']),
            'type' => sanitize_text_field($post_data['type']),
        ];
    }
}

// Create a settings instance
$vehicleSettings = new VehicleSettings('vehicle');

// Register the meta box with a custom label
(new MetaBox($vehicleSettings, [
    'name' => 'Vehicle Details',
]))->register();
```

## Configuration Options

When creating a `MetaBox` instance, you can pass configuration options as an array.

### Example

```php
(new MetaBox($vehicleSettings, [
    'name' => 'Vehicle Details',     // Meta box title
    'zebra' => true,                 // Enable zebra table styling
]))->register();
```

### Supported Options

- **`name`**: Title of the meta box (default: derived from the `Settings` class).
- **`zebra`**: Boolean indicating whether to apply zebra table styling (default: `true`).


## Example Integration

### Full Vehicle Management System

```php
use Urisoft\PostMeta\PostType;
use Urisoft\PostMeta\MetaBox;
use Urisoft\PostMeta\Settings;

class VehicleSettings extends Settings
{
    public function settings(): void
    {
        echo self::form()->input('Vehicle Model', $this->get_meta('vehicle_model'));
        echo self::form()->textarea('Description', $this->get_meta('description'));
        echo self::form()->select('Type', $this->get_meta('type'), [
            'car' => 'Car',
            'truck' => 'Truck',
            'motorcycle' => 'Motorcycle',
        ]);
        echo self::form()->input('Top Speed (mph)', $this->get_meta('top_speed'), [
            'type' => 'number',
        ]);
    }

    public function data($post_data): array
    {
        return [
            'vehicle_model' => sanitize_text_field($post_data['vehicle_model']),
            'description' => sanitize_textarea_field($post_data['description']),
            'type' => sanitize_text_field($post_data['type']),
            'top_speed' => intval($post_data['top_speed']),
        ];
    }
}

// Register the custom post type
$vehiclePostType = new PostType('vehicle', 'Vehicle', 'Vehicles');
$vehiclePostType->register();

// Register the meta box
$vehicleSettings = new VehicleSettings('vehicle');
(new MetaBox($vehicleSettings, [
    'name' => 'Vehicle Details',
    'zebra' => true,
]))->register();
```

## Notes

- **Field Definitions**: Define fields and their data logic in the associated `Settings` class.
- **Automatic Data Handling**: The `MetaBox` class saves and retrieves data automatically.
- **Integration Ready**: Works seamlessly with the `PostType` and `Settings` classes.

For more details on WordPress meta boxes, visit the [Meta Box Documentation](https://developer.wordpress.org/plugins/metadata/custom-meta-boxes/).

This class is perfect for creating and managing meta boxes in WordPress, making it easy to enhance the functionality of custom post types.
