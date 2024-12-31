# cpt-meta Library

The **`cpt-meta`** library simplifies the creation, management, and rendering of custom post types, meta boxes, and fields in WordPress. Its modular design makes it easier to build structured content management solutions by providing intuitive APIs for defining and sanitizing metadata, registering post types, generating custom fields, and interacting with WordPress hooks and REST endpoints.



## Installation

Install via Composer:

```bash
composer require devuri/cpt-meta-box
```

Once installed, ensure that your plugin or theme autoloads dependencies:

```php
require_once __DIR__ . '/vendor/autoload.php';
```


##  Simplified Example
Below is a snippet of how a baisc plugin might set everything up:

```php
/**
 * Plugin Name: Vehicle Management
 */

use Urisoft\PostMeta\Settings;

// Define settings fields
class VehicleSettings extends Settings
{
    public function settings()
    {
        $this->input('Vehicle Name');
        $this->textarea('Description');
        $this->select('Type', [
            'car'        => 'Car',
            'truck'      => 'Truck',
            'motorcycle' => 'Motorcycle',
            'selected'   => $this->getMeta('type'),
        ]);
        $this->input('Top Speed (mph)', ['type' => 'number']);
    }
}

// Create the meta box
createMeta(new VehicleSettings('vehicle'), [
    'name' => 'Vehicle Details',
]);
```

> When you visit **Vehicles > Add New** in the admin area, you’ll see a “Vehicle Details” meta box. Any data entered into these fields will automatically save to the post’s metadata.

## Key Features

- **Custom Post Types**  
  Easily register and configure custom post types using the `PostType` class.

- **Meta Boxes and Fields**  
  Define and manage meta boxes via the `MetaBox` class, and create custom field definitions with the `Settings` class.

- **Form Rendering**  
  Leverage the `Form` class to generate input fields, textareas, selects, color pickers, and more—complete with zebra striping, table-based layouts, and built-in sanitization hooks.

- **Metadata Handling**  
  Retrieve and manage metadata with the `Data` class, which provides easy methods to fetch post meta, create lists of posts, and fetch additional information (like featured image IDs).

- **REST API Integration**  
  The `PostType` class can register custom REST endpoints for retrieving or manipulating data. Extend WordPress’s REST API easily.

- **Admin Columns**  
  Manage custom columns in the WordPress admin, making them sortable or adding additional information in list tables.

- **Modular & Extensible**  
  Built around interfaces, traits, and abstract classes to ensure maximum flexibility. You can override default behaviors or hook into key extension points.



## Example: Vehicle Management

Below is a basic walkthrough on how you might use this library to manage a “Vehicle” custom post type and its metadata.

### 1. Register a Custom Post Type

First, create a `PostType` instance and register it:

```php
use Urisoft\PostMeta\PostType;

// Create and configure a custom post type: "Vehicle"
$vehiclePostType = new PostType(
    'vehicle',       // post type slug
    'Vehicle',       // singular name
    'Vehicles',      // plural name
    [
        'menu_icon' => 'dashicons-car',
        'supports'  => ['title', 'thumbnail'],
    ]
);

// Register the post type with WordPress
$vehiclePostType->register();
```

This will make a new post type called “Vehicle” in the WordPress admin menu with a car dashicon and support for `title` and `thumbnail`.

### 2. Define Metadata Fields in a Settings Class

Next, create a subclass of `Settings` to define the custom fields (e.g., name, description, type, speed):

```php
use Urisoft\PostMeta\Settings;

class VehicleSettings extends Settings
{
    /**
     * Override the settings() method to define your form fields.
     */
    public function settings(): void
    {
        // Simple text input
        $this->input('Vehicle Name', [
            'placeholder' => 'Enter the vehicle name',
        ]);

        // Textarea
        $this->textarea('Description');

        // Select dropdown (car, truck, motorcycle)
        $this->select('Type', [
            'car'        => 'Car',
            'truck'      => 'Truck',
            'motorcycle' => 'Motorcycle',
            'selected'   => $this->getMeta('type'), // set currently saved value as selected
        ]);

        // Number input
        $this->input('Top Speed (mph)', [
            'type'        => 'number',
            'placeholder' => 'Enter top speed in mph',
        ]);

        // Color field
        $this->input('Available Colors', [
            'type'        => 'color',
            'placeholder' => 'Choose a color',
        ]);
    }
}
```

Within these methods (`input`, `textarea`, `select`), the library handles rendering, sanitization, and labeling. You can further tailor data sanitization by overriding methods like `data()` or hooking into form submission processes.

### 3. Attach the Settings to a Meta Box

Create a `MetaBox` instance, passing in your `VehicleSettings` object and any config options, then register it:

```php
use Urisoft\PostMeta\MetaBox;

// Instantiate VehicleSettings for the "vehicle" post type
$vehicleSettings = new VehicleSettings('vehicle');

// Create a MetaBox for these settings
$vehicleMetaBox = new MetaBox($vehicleSettings, [
    'name'  => 'Vehicle Details', // The meta box title in the WP admin
    'zebra' => true,              // Zebra striping for table rows
]);

// Register the meta box so it appears in the edit screen
$vehicleMetaBox->register();
```

When editing a “Vehicle” post, you’ll now see a “Vehicle Details” meta box containing all defined fields.

### 4. Retrieving Metadata

With the `Data` class, you can retrieve post data in one place:

```php
use Urisoft\PostMeta\Data;

// Initialize a Data instance for "vehicle" post type
$vehicleData = Data::init('vehicle');

// Retrieve meta for a specific Vehicle post (ID=100)
$info = $vehicleData->meta(100);

// Access custom fields
echo 'Vehicle Name: ' . esc_html($info['vehicle_name']);
echo 'Top Speed: ' . esc_html($info['top_speed']) . ' mph';
```

Additionally, you can fetch a list of vehicles, generate edit links, or retrieve featured image IDs.



## Advanced Usage

### 1. Custom REST Endpoints

If you’d like to expose a custom endpoint, the `PostType` class offers a helper method:

```php
$vehiclePostType->addCustomRestEndpoint('/custom-details', function ($request) {
    // Do something with $request...
    return ['message' => 'Custom endpoint data!'];
}, 'GET');
```

Your custom endpoint will be available at `/{post_type}/v1/custom-details`.

### 2. Admin Columns

Add or modify admin columns for your post type:

```php
$vehiclePostType->addAdminColumns(
    function ($columns) {
        $columns['type']  = 'Type';
        $columns['speed'] = 'Top Speed';
        return $columns;
    },
    function ($column, $post_id) {
        switch ($column) {
            case 'type':
                echo esc_html(get_post_meta($post_id, 'type', true));
                break;
            case 'speed':
                echo intval(get_post_meta($post_id, 'top_speed', true)) . ' mph';
                break;
        }
    }
);

// Make the "Top Speed" column sortable:
$vehiclePostType->addSortableColumns(['speed' => 'top_speed']);
```

### 3. Bulk Registration & Autosave

If you maintain multiple custom post types, you can bulk-register them. Or you can rely on the built-in autosave mechanism (by default, `MetaBox` hooks into `save_post_{$post_type}`).

### 4. Extensible Form Rendering

All fields are generated by the `Form` class, which you can override or extend for specialized needs (e.g., custom JavaScript fields, advanced validation, etc.).  
Common methods include `input`, `textarea`, `select`, `editor`, and more.



Below are two examples demonstrating how to use the `urisoft/postmeta` package in a plugin context. Both examples register a custom post type called “Vehicle” and create a meta box for managing vehicle data in the WordPress admin.

---

## Example 1: Step-by-Step Setup with PostType and MetaBox

<details>
  <summary>Click to expand code</summary>

```php
/**
 * Plugin Name: Vehicle Management
 */

use Urisoft\PostMeta\PostType;
use Urisoft\PostMeta\Settings;
use Urisoft\PostMeta\MetaBox;

// 1. Register the post type
$vehicle = new PostType('vehicle', 'Vehicle', 'Vehicles', [
    'menu_icon' => 'dashicons-car',
    'supports'  => ['title', 'thumbnail'],
]);
$vehicle->register();

// 2. Define settings fields
class VehicleSettings extends Settings
{
    public function settings()
    {
        $this->input('Vehicle Name');
        $this->textarea('Description');
        $this->select('Type', [
            'car'        => 'Car',
            'truck'      => 'Truck',
            'motorcycle' => 'Motorcycle',
            'selected'   => $this->getMeta('type'),
        ]);
        $this->input('Top Speed (mph)', ['type' => 'number']);
    }
}

// 3. Create a meta box for those settings
(new MetaBox(new VehicleSettings('vehicle'), [
    'name'  => 'Vehicle Details',
    'zebra' => true,
]))->register();
```

</details>

When you visit **Vehicles > Add New** in the admin area, you’ll see a “Vehicle Details” meta box. Any data entered into these fields will automatically save to the post’s metadata.


## Example 2: Helper Function (`createMeta`)

<details>
  <summary>Click to expand code</summary>

```php
/**
 * Plugin Name: Vehicle Management
 */

use Urisoft\PostMeta\Settings;

// Define settings fields
class VehicleSettings extends Settings
{
    public function settings()
    {
        $this->input('Vehicle Name');
        $this->textarea('Description');
        $this->select('Type', [
            'car'        => 'Car',
            'truck'      => 'Truck',
            'motorcycle' => 'Motorcycle',
            'selected'   => $this->getMeta('type'),
        ]);
        $this->input('Top Speed (mph)', ['type' => 'number']);
    }
}

// Create the meta box
createMeta(new VehicleSettings('vehicle'), [
    'name' => 'Vehicle Details',
]);
```

</details>

> **Note**: The `createMeta` function will skip registering the “vehicle” post type if it already exists. If that’s the case, you only get the meta box for the existing post type.

Both approaches result in a “Vehicle Details” meta box when editing the “Vehicle” custom post type in your WordPress admin. Choose whichever method best suits your workflow.



## Additional Tips

- **Naming Conventions**:  
  By default, meta data may be stored in meta keys like `"{post_type}_cpm"` or a hashed field. Check your code or the `Settings` subclass to see how it’s configured.

- **Sanitization & Validation**:  
  Each field can be sanitized in the `data()` method of your `Settings` subclass. For advanced or non-textual fields (e.g., HTML fields, numeric ranges), override or extend the library’s defaults.

- **Compatibility**:  
  The library requires at least PHP 7.4+ and a WordPress environment (version 4.0 or higher is recommended).

- **Documentation**:  
  Inline PHPDoc comments are provided throughout the source code. You can also refer to WordPress’s official developer references for functions like `get_posts()`, `register_post_type()`, `wp_editor()`, etc.

The **`cpt-meta`** library offers a streamlined, powerful set of tools for building and maintaining custom post types in WordPress. By combining classes like `PostType`, `MetaBox`, `Settings`, `Form`, and `Data`, developers can organize complex metadata workflows with minimal boilerplate. Whether you’re setting up a single custom post type or crafting a fully-fledged content management system, `cpt-meta` aims to simplify the entire process—so you can focus on building great features.

For more advanced details on WordPress custom post types and metadata, refer to:
- [WordPress Custom Post Type](https://developer.wordpress.org/plugins/post-types/)
- [WordPress Custom Meta Boxes](https://developer.wordpress.org/plugins/metadata/custom-meta-boxes/)
- [WordPress REST API](https://developer.wordpress.org/rest-api/)
