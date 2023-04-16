# cpt-meta-box
Simple Implemetation for custom meta boxes and fields for WordPress custom post types

This is a PHP Composer package that can help you create WordPress Meta Boxes and Meta Fields. It also includes a `Data` class that you can use to retrieve the saved meta data.

### Installation

To install this package, run the following command in your terminal:
```shell
composer require devuri/cpt-meta-box
```
### Usage  
Here's how you can use this package to create a Meta Box and a Meta Field:
```php
    use DevUri\Meta\Settings;
    
    // implement meta fields
    class Details extends Settings
    {
        /**
         * the metabox settings
         * @param $get_meta
         */
        public function settings($get_meta): void
        {
            // basic input field
            echo self::form()->input("Title", self::meta("title", $get_meta));
    
            // regular textarea.
            echo self::form()->textarea(
                "Description",
                self::meta("description", $get_meta)
            );
    
            // editor uses a simplified version wp_editor.
            echo self::editor("Description", self::meta("description", $get_meta));
        }
    
        /**
         * the data
         * @param $post_data
         * @return array
         */
        public function data($post_data): array
        {
            return [
                "title" => sanitize_textarea_field($post_data["title"]),
                "description" => sanitize_textarea_field(
                    $post_data["description_textarea"]
                ),
            ];
        }
    }
```
This is all we need to setup a few fields.

### Implement
Then when ready we can implement the Meta Box for specific post type, in this example we are setting up a metabox called `Details` for the `vehicle` post type
```php
    // For `vehicle` post type. metabox uses class name `Details` as label.
    $details = new Details("vehicle");
    
    // adds metabox no stripes.
    new MetaBox($details);
    
    // adds metabox with zebra table.
    new MetaBox($details, true);
    
    // set metabox name `Vehicle Details` as label.
    new MetaBox($details, ["name" => "Vehicle Details"]);
    
    // set metabox name `Vehicle Details` as label and zebra stripes
    new MetaBox($details, [
        "name" => "Vehicle Details",
        "zebra" => true,
    ]);
    
    // zebra styles are applied by default, this will also use zebra style.
    new MetaBox($details, [
        "name" => "Vehicle Details",
    ]);
    
    // or
    new MetaBox(new Details("vehicle"));
```

### Conclusion

This package provides a simple and easy-to-use way to create MetaBoxes and MetaFields in WordPress. If you have any questions or issues, please feel free to submit an issue on GitHub.
