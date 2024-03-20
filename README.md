Simple Implemetation for custom meta boxes and fields for WordPress custom post types

This is a PHP Composer package that can help you create WordPress Meta Boxes and Meta Fields. It also includes a `Data` class that you can use to retrieve the saved meta data.

### Installation

To install this package, run the following command in your terminal:
```shell
composer require devuri/cpt-meta-box
```

# MetaBox and Settings

This is a set of PHP code snippets for creating a WordPress meta box and its related settings using the `MetaBox` and `Settings` classes. These classes provide functionality to define and handle custom meta boxes and their settings in WordPress.

## MetaBox Class

The `MetaBox` class allows you to create a custom meta box in WordPress. It provides the following features:

- Automatic creation and rendering of the meta box based on settings.
- Saving and updating the meta data when the post is saved.
- Integration with the `Settings` class for handling the meta box settings.

### Usage

To use the `MetaBox` class, follow these steps:

1. Include the code in your WordPress project.
2. Create an instance of the `Settings` subclass, such as the `Details` class in the provided example, passing the relevant post type as a parameter.
3. Implement the `settings()` method in your `Settings` subclass to define the meta box settings. This method defines the fields to be displayed in the meta box.
4. Implement the `data()` method in your `Settings` subclass to handle the data submitted from the meta box fields. This method sanitizes and prepares the data before saving it.
5. Customize the `settings()` and `data()` methods by adding fields and defining their settings within the `settings()` method.
6. Create an instance of the `MetaBox` class, passing the `Settings` object and optional arguments. This will automatically create and display the meta box on the relevant post types.
7. The entered data will be saved when the post is updated.

### Settings Class

The `Settings` abstract class provides a foundation for defining and handling settings related to a specific post type. It works in conjunction with the `MetaBox` class to define the settings for the meta box.

### Usage

To use the `Settings` class, follow these steps:

1. Create a subclass that extends the `Settings` class.
2. Implement the `settings()` method to define the meta box settings.
3. Implement the `data()` method to handle the data submitted from the meta box fields (be sure to sanitize).
4. Customize the `Settings` subclass by adding fields and defining their settings within the `settings()` method.
5. Instantiate the `Settings` subclass and pass it to the `MetaBox` class constructor to create and display the meta box.

## Example Usage

The following example demonstrates the usage of the `MetaBox` and `Settings` classes:

```php
use DevUri\PostTypeMeta\MetaBox;
use DevUri\PostTypeMeta\Settings;

// Implement meta fields
class Details extends Settings
{
    // The metabox settings
    public function settings(): void
    {
        // Basic input field
        echo self::form()->input('Title', $this->get_meta('title'));

        // Regular textarea
        echo self::form()->textarea('Description', $this->get_meta('description'));

        // Editor using a simplified version of wp_editor
        echo self::editor('Description', $this->get_meta('description'));
    }

    // The data, is $post_data $_POST and needs to be sanitized
    public function data($post_data): array
    {
        return [
            'title' => sanitize_textarea_field($post_data['title']),
            'description' => sanitize_textarea_field($post_data['description_textarea']),
        ];
    }
}

// Create a new instance of the Details class for the 'vehicle' post type
$details = new Details('vehicle');

// Create a meta box without stripes
new MetaBox($details);

// Create a meta box with zebra table
new MetaBox($details, true);

// Create a meta box with 	NO zebra table
new MetaBox($details, false);

// Create a meta box with a custom label 'Vehicle Details'
// and the meta key will be `vehicle-details_cpm`
new MetaBox($details, ['name' => 'Vehicle Details']);

// Create a meta box with a custom label 'Vehicle Details' and zebra stripes
new MetaBox($details, [
    'name' => 'Vehicle Details',
    'zebra' => true,
]);

// Zebra styles are applied by default, this will also use zebra style
new MetaBox($details, ['name' => 'Vehicle Details']);

// Or instantiate directly, in this case the metabox will be `Details` based on the class name
// and the meta key will be `details_cpm`
new MetaBox(new Details('vehicle'));
```

This example demonstrates how to create a meta box for the 'vehicle' post type using the `MetaBox` and `Settings` classes. The `Details` class is a subclass of `Settings` and defines the meta box settings and data handling. The `MetaBox` class is used to create and display the meta box with various customization options.


# Data Class

The `Data` class provides various utility methods for working with data in WordPress. It includes functions for retrieving and manipulating post-related data such as post meta, post items, and generating custom edit links.

## Usage

To use the `Data` class, follow these steps:

1. Create an instance of the `Data` class, optionally passing a post type as a parameter. If no post type is specified, the default post type 'post' will be used.
2. Utilize the available methods of the `Data` class to perform data-related operations.

### Methods

The `Data` class provides the following methods:

- `__construct($post_type = null)`: Initializes the `Data` object with an optional post type parameter. If no post type is provided, the default post type 'post' is used.
- `init($post_type)`: Static method to create a new instance of the `Data` class with a specified post type. Returns a `Data` object.
- `edit(int $id, $class = '')`: Generates an edit link for a given post ID. Returns the edit link HTML as a string. This method checks if the current user has the capability to edit posts before generating the link.
- `list()`: Retrieves a list of post items as key-value pairs. The keys are the post IDs, and the values are the post titles. Returns an array.
- `getkey($key, $data)`: Retrieves a value from an array by its key. If the key is not set, `null` is returned. If the data is not an array, `false` is returned.
- `meta($ID, $name = null)`: Retrieves the post meta data for a given post ID. The meta data is retrieved using the specified meta name, or if not provided, the post type is used as the meta name. Returns an array of meta data, including the post ID and thumbnail ID.
- `items($n = -1)`: Retrieves an array of the latest posts or posts matching the given criteria. The number of posts to retrieve can be specified with the `$n` parameter. Returns an array of post objects or post IDs.

### Example Usage

Here's an example of how you can utilize the `Data` class:

```php
use DevUri\PostTypeMeta\Data;

// Create a new instance of the Data class for the 'vehicle' post type
$data = Data::init('vehicle');

// Get a list of post items
$postItems = $data->list();

// If you are not using the post type name as meta name you need to pass in the name in this example `details_cpm`:
$metaData = $data->meta(123, 'details_cpm);

// Retrieve the meta data for a specific post
$metaData = $data->meta(123);

// Generate an edit link for a post
$editLink = Data::edit(123);

// Get a value from an array by its key
$value = Data::getkey('key_name', $dataArray);

// Retrieve the latest 10 posts
$latestPosts = $data->items(10);

// Retrieve the latest 10 posts,
// you can pass in array of arguments to filter retrieved posts.
// See WP_Query::parse_query() for all available arguments.
$latestPosts = $data->items(10, [ 'orderby' => 'date' ]);

// if no arguments are provided these will be used.
$defaults = [
	'numberposts'      => 5,
	'category'         => 0,
	'orderby'          => 'date',
	'order'            => 'DESC',
	'include'          => array(),
	'exclude'          => array(),
	'meta_key'         => '',
	'meta_value'       => '',
	'post_type'        => 'vehicle', // based on the current Data class.
	'suppress_filters' => true,
];

```

In this example, we create a `Data` object for the 'vehicle' post type and use its methods to retrieve post items, post meta data, generate an edit link, get a value from an array, and retrieve the latest posts. You can adapt these examples to suit your specific needs.

### Conclusion

This package provides a simple and easy-to-use way to create MetaBoxes and meta fields in WordPress. If you have any questions or issues, please feel free to submit an issue.
