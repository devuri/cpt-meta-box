The `PostType` class is a utility for creating and managing WordPress custom post types. It provides a robust API for defining post types, adding taxonomies, customizing admin columns, and integrating with the REST API, all while ensuring ease of use.

## Features

- **Simple Registration**: Easily register custom post types with sensible defaults.
- **Advanced Taxonomy Management**: Add, update, and manage taxonomies dynamically.
- **Custom Capabilities**: Define specific user capabilities for post type actions.
- **Admin List Table Integration**: Customize and make columns sortable.
- **REST API Integration**: Register custom REST endpoints.
- **Default Meta Fields**: Automatically add default metadata.
- **Bulk Registration**: Register multiple post types at once.
- **Rewrite Rules Management**: Configure rewrite rules and flush them on activation.

## Usage

### Registering a Post Type

```php
use Urisoft\PostMeta\PostType;

// Create and register a "Vehicle" post type
$vehiclePostType = new PostType('vehicle', 'Vehicle', 'Vehicles');
$vehiclePostType->register();
```

### Adding Taxonomies

```php
$vehiclePostType->addTaxonomy('vehicle_type', 'Vehicle Type', 'Vehicle Types', [
    'hierarchical' => true,
    'show_in_rest' => true,
]);
$vehiclePostType->register();
```

### Setting Custom Capabilities

```php
$vehiclePostType->setCapabilities([
    'edit_posts' => 'edit_vehicles',
    'publish_posts' => 'publish_vehicles',
]);
```

### Customizing Admin Columns

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
            echo get_post_meta($post_id, 'speed', true) . ' mph';
        }
    }
);
```

### Adding Sortable Columns

```php
$vehiclePostType->addSortableColumns([
    'type' => 'type',
    'speed' => 'speed',
]);
```

### Adding Custom REST Endpoints

```php
$vehiclePostType->addCustomRestEndpoint('/fuel-efficiency', function ($data) {
    return [
        'message' => 'Fuel efficiency data is not available yet.',
    ];
}, 'GET');
```

### Adding Default Metadata

```php
$vehiclePostType->addDefaultMetaFields([
    'speed' => 0,
    'type' => 'Unknown',
]);
```

### Bulk Registration

```php
PostType::bulkRegister([
    'vehicle' => [
        'singular' => 'Vehicle',
        'plural' => 'Vehicles',
        'args' => ['menu_icon' => 'dashicons-car'],
    ],
    'part' => [
        'singular' => 'Part',
        'plural' => 'Parts',
        'args' => ['menu_icon' => 'dashicons-hammer'],
    ],
]);
```


## Advanced Usage

### Rewrite Rules

```php
$vehiclePostType->setRewriteRules([
    'slug' => 'fleet/vehicles',
    'with_front' => false,
]);
```

### Validation for Existing Post Types

The class automatically checks for conflicts with existing post types. If a conflict exists, it throws an exception:

```php
try {
    $vehiclePostType = new PostType('vehicle', 'Vehicle', 'Vehicles');
    $vehiclePostType->register();
} catch (\Exception $e) {
    echo $e->getMessage(); // "Post type 'vehicle' already exists."
}
```

### Flushing Rewrite Rules on Activation

```php
$vehiclePostType->flushRewriteRulesOnActivation();
```


## Example Integration

```php
use Urisoft\PostMeta\PostType;

// Create the "Vehicle" post type
$vehiclePostType = new PostType('vehicle', 'Vehicle', 'Vehicles', [
    'menu_icon' => 'dashicons-car',
    'supports' => ['title', 'editor', 'thumbnail', 'custom-fields'],
]);

// Add a taxonomy for vehicle types
$vehiclePostType->addTaxonomy('vehicle_type', 'Vehicle Type', 'Vehicle Types', [
    'hierarchical' => true,
]);

// Add custom admin columns for displaying type and speed
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
            echo get_post_meta($post_id, 'speed', true) . ' mph';
        }
    }
);

// Add default metadata for vehicles
$vehiclePostType->addDefaultMetaFields([
    'speed' => 0,
    'type' => 'Unknown',
]);

// Register the "Vehicle" post type
$vehiclePostType->register();
```

## Notes

- **Extensibility**: The class is designed to be extended or modified for custom behaviors.
- **Sensible Defaults**: Default arguments and labels are provided, reducing boilerplate code.
- **REST API Ready**: Integrate post types and taxonomies with WordPress’s REST API out of the box.

For detailed WordPress documentation, visit:
- [Post Types](https://developer.wordpress.org/plugins/post-types/registering-custom-post-types/)
- [Taxonomies](https://developer.wordpress.org/plugins/taxonomies/)

This class is a robust and flexible solution for managing custom post types in WordPress, suitable for both beginners and advanced developers.
