The `Settings` class is an abstract foundation for creating and managing meta box settings in WordPress. It integrates seamlessly with the `PostType` and `MetaBox` classes, providing a structured way to define custom fields and handle metadata for custom post types.

## Features

- **Dynamic Field Rendering**: Use the built-in `Form` helper to render input fields for meta boxes.
- **Metadata Management**: Simplify saving and retrieving custom post metadata.
- **Validation and Sanitization**: Ensure clean and valid data is stored.
- **Customizable Integration**: Extend the class to define custom field types and logic.


## How It Works

1. Extend the `Settings` class to define fields for your custom meta box.
2. Use the `settings()` method to define and render fields using the `Form` helper.
3. Implement the `data()` method to sanitize and prepare the submitted data.
4. Use the extended `Settings` class in combination with the `MetaBox` class to register the meta box with a custom post type.


## Usage

### Creating a Custom Settings Class

Extend the `Settings` class to define the fields and data logic:

```php
use Urisoft\PostMeta\Settings;

class VehicleSettings extends Settings
{
    public function settings(): void
    {
        echo self::form()->input('Vehicle Model', $this->get_meta('vehicle_model'), [
            'placeholder' => 'Enter the vehicle model',
        ]);
        echo self::form()->textarea('Description', $this->get_meta('description'));
        echo self::form()->select('Type', $this->get_meta('type'), [
            'car' => 'Car',
            'truck' => 'Truck',
            'motorcycle' => 'Motorcycle',
        ]);
        echo self::form()->input('Top Speed (mph)', $this->get_meta('top_speed'), [
            'type' => 'number',
        ]);
        echo self::form()->checkbox('Electric Vehicle', $this->get_meta('is_electric'));
    }

    public function data($post_data): array
    {
        return [
            'vehicle_model' => sanitize_text_field($post_data['vehicle_model']),
            'description' => sanitize_textarea_field($post_data['description']),
            'type' => sanitize_text_field($post_data['type']),
            'top_speed' => intval($post_data['top_speed']),
            'is_electric' => !empty($post_data['is_electric']),
        ];
    }
}
```

### Integrating with a Meta Box

```php
use Urisoft\PostMeta\MetaBox;

// Create a settings instance for the "vehicle" post type
$vehicleSettings = new VehicleSettings('vehicle');

// Register the meta box with a custom label
(new MetaBox($vehicleSettings, [
    'name' => 'Vehicle Details',
]))->register();
```

## Working with Fields

The `Form` helper provides methods for rendering fields dynamically.

### Supported Field Types

#### Input Field

```php
echo self::form()->input('Vehicle Model', $this->get_meta('vehicle_model'), [
    'placeholder' => 'Enter the vehicle model',
    'type' => 'text',
]);
```

#### Textarea

```php
echo self::form()->textarea('Description', $this->get_meta('description'), [
    'placeholder' => 'Enter the vehicle description',
]);
```

#### Select Dropdown

```php
echo self::form()->select('Type', $this->get_meta('type'), [
    'car' => 'Car',
    'truck' => 'Truck',
    'motorcycle' => 'Motorcycle',
]);
```

#### Checkbox

```php
echo self::form()->checkbox('Electric Vehicle', $this->get_meta('is_electric'));
```

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
        echo self::form()->checkbox('Electric Vehicle', $this->get_meta('is_electric'));
    }

    public function data($post_data): array
    {
        return [
            'vehicle_model' => sanitize_text_field($post_data['vehicle_model']),
            'description' => sanitize_textarea_field($post_data['description']),
            'type' => sanitize_text_field($post_data['type']),
            'top_speed' => intval($post_data['top_speed']),
            'is_electric' => !empty($post_data['is_electric']),
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
]))->register();
```

## Notes

- **Validation and Sanitization**: Ensure clean data by implementing the `data()` method to sanitize inputs.
- **Extensible**: Add custom fields by extending the `Settings` class and using `Form` methods.
- **Seamless Integration**: Works effortlessly with the `PostType` and `MetaBox` classes.

This class is ideal for managing custom meta boxes and fields in WordPress, enabling developers to create structured and maintainable solutions for custom post types.
