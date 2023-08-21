<?php

use DevUri\Meta\MetaBox;
use DevUri\Meta\Settings;

// implement meta fields
class Details extends Settings
{
    /**
     * the metabox settings
     * @param $get_meta
     */
    public function settings(): void
    {
        // basic input field
        echo self::form()->input("Title", $this->get_meta("title"));

        // regular textarea.
        echo self::form()->textarea(
            "Description",
            $this->get_meta("description")
        );

        // editor uses a simplified version wp_editor.
        echo self::editor("Description", $this->get_meta("description"));
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
