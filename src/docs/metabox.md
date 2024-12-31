## MetaBox

The `MetaBox` class in the `cpt-meta` library simplifies the creation and management of WordPress meta boxes for your custom post types. It integrates with a `SettingsInterface` implementation for defining fields and sanitizing metadata, and uses a `Form` instance to render fields within the WordPress admin interface. This class abstracts away much of the boilerplate typically required when working with WordPress meta boxes.

### Namespace

```php
namespace Urisoft\PostMeta;
```

### Overview

- **Creates** WordPress meta boxes for specific post types.  
- **Renders** form fields by integrating a `Form` instance.  
- **Stores** metadata in a single meta key, which can be a serialized array of values.  
- **Manages** data validation and sanitization via a `SettingsInterface`.  

### Properties

Below are the primary properties used internally by the `MetaBox` class:

| Property         | Type                     | Description                                                                             |
||--|--|
| `$postType`      | `?string`               | The slug of the post type this meta box is attached to.                                 |
| `$settings`      | `?SettingsInterface`    | An instance of `SettingsInterface`, responsible for defining and sanitizing fields.     |
| `$metaData`      | `?array`                | Holds the metadata that will be stored or updated.                                      |
| `$metabox`       | `?string`               | The (slug-like) name/identifier for the meta box.                                       |
| `$metaId`        | `?string`               | The HTML `id` attribute for the meta box.                                               |
| `$args`          | `?array`                | Array of arguments/configuration options for the meta box.                              |
| `$fields`        | `?array`                | An array of form fields (used by the meta box).                                         |
| `$metaLabel`     | `?string`               | A more human-friendly label for the meta box in the WordPress UI.                       |
| `$metaField`     | `?string`               | The meta key used when saving/retrieving data from the post meta.                       |
| `$groupKey`      | `?string`               | A hashed key used to uniquely identify groups of fields in the database.                |
| `$metaContext`   | `?array`                | An associative array holding contextual information about the meta box.                 |
| `$formContext`   | `?Form`                 | The `Form` instance for rendering fields.                                               |



### Constructor

```php
public function __construct(SettingsInterface $settings, ?array $args = [])
```

**Description:**  
Initializes a new `MetaBox` instance. It sets up all essential properties such as the meta box name, meta field key, label, and context. It also pulls in configuration data from the provided `SettingsInterface` instance.

**Parameters:**

- **`$settings`** (`SettingsInterface`):  
  The settings instance, which implements `SettingsInterface`, responsible for defining the meta box fields and handling data sanitization.

- **`$args`** (`?array`, optional):  
  Additional arguments to configure the meta box. Defaults to an empty array. Supported keys include:
  - `name` (`string`): The label or name for your meta box.
  - `zebra` (`bool`): Whether to apply zebra striping to the table layout (default: `true`).
  - Any additional keys you need for your setup.



### Public Methods

#### 1. `register(?PostType $postType = null, bool $withSavePost = true): self`

Registers the meta box with WordPress, optionally registering a custom post type and hooking into the `save_post` action to save meta data.

```php
public function register(?PostType $postType = null, bool $withSavePost = true): self
```

- **Parameters**:
  - `?PostType $postType`:  
    (Optional) A custom PostType object that can be registered before adding the meta box.
  - `bool $withSavePost`:  
    (Optional) Whether to hook the `saveMeta()` method into `save_post_{post_type}`. Defaults to `true`.
- **Returns**:  
  `self` (the current `MetaBox` instance), allowing method chaining.

**Usage Example:**
```php
$vehicleSettings = new VehicleSettings('vehicle');

(new MetaBox($vehicleSettings, [
    'name'  => 'Vehicle Details',
    'zebra' => true,
]))->register();
```



#### 2. `context(): ?array`

Retrieves the array of contextual information for this meta box. This includes the meta box’s internal IDs, labels, post type, and other configuration values.

```php
public function context(): ?array
```

- **Returns**:  
  `?array` The associative array of meta box context.



#### 3. `build(?WP_Post $postObject = null): SettingsInterface`

Creates or updates the settings context with the current post object (if provided) and the meta field key. This method is typically invoked inside the `render()` method to prepare the fields before rendering.

```php
public function build(?WP_Post $postObject = null): SettingsInterface
```

- **Parameters**:
  - `?WP_Post $postObject`: The current post object (optional).
- **Returns**:  
  `SettingsInterface` The settings instance used for field definitions and sanitization.

**Possible Exceptions**:
- May throw exceptions if the `SettingsInterface` implementation fails to create or prepare fields.



#### 4. `postTypeData(): ?WP_Post_Type`

Retrieves the `WP_Post_Type` object for the post type this meta box is associated with.

```php
public function postTypeData(): ?WP_Post_Type
```

- **Returns**:  
  `?WP_Post_Type` The WordPress post type object, or `null` if not found.



#### 5. `createMetaBox(): void`

Registers the actual meta box with WordPress using `add_meta_box()`.  
This method is automatically called from `register()`.

```php
public function createMetaBox(): void
```

- **Internal**:  
  Called by the WordPress `add_meta_boxes` hook.



#### 6. `render(WP_Post $post): void`

The callback for rendering the actual meta box fields in the WordPress admin. It outputs the HTML table (optionally with zebra striping), sets up the form, and executes the settings build logic.

```php
public function render(WP_Post $post): void
```

- **Parameters**:
  - `WP_Post $post`: The WordPress post object currently being edited.
- **Throws**:
  - `Exception`: If there’s an error initializing or rendering the form fields.
- **Notes**:
  - Uses `$this->build($post)->withContext($this);` to prepare the fields.
  - Applies styling if `zebra` is enabled in `$args`.



#### 7. `saveMeta(int $post_id): void`

Handles the saving of meta data when the post is saved. It verifies permissions, checks nonces, and updates the meta data in the database.

```php
public function saveMeta(int $post_id): void
```

- **Parameters**:
  - `int $post_id`: The ID of the post being saved.
- **Process**:
  1. Checks for `DOING_AUTOSAVE` to avoid overwriting during auto-saves.
  2. Verifies the user can edit the post.
  3. Validates the nonce generated by the form.
  4. Applies any filters or actions before and after updating the meta value.
- **Hooks**:
  - `apply_filters($this->metaField, $this->metaData, $post_id, $this->metaContext)`
  - `do_action('cpm_before_meta_update', ...)`
  - `do_action('cpm_after_meta_update', ...)`



#### 8. `getFormContext(): ?Form`

Retrieves the `Form` instance used to render fields.

```php
public function getFormContext(): ?Form
```

- **Returns**:  
  `?Form` The `Form` instance or `null` if none is set.



### Protected Methods

#### 1. `getPostFieldsData(): array`

Collects and sanitizes field values from `$_POST` based on the form field definitions. Throws exceptions if fields do not follow the expected structure.

```php
protected function getPostFieldsData(): array
```

- **Returns**:  
  `array` An associative array of sanitized field data.
- **Throws**:
  - `\InvalidArgumentException`: If the fields array from the form is invalid.
  - `\UnexpectedValueException`: If a required field does not have the necessary keys.



#### 2. `form(): Form`

A convenience method to retrieve the same `Form` instance from `$this->settings`.

```php
protected function form(): Form
```

- **Returns**:  
  `Form` The form instance.



#### 3. `setName(array $args): string`

Determines the meta box name (slug) based on constructor arguments or a fallback to the class name from `SettingsInterface`.

```php
protected function setName(array $args): string
```

- **Parameters**:
  - `array $args`: The constructor arguments.
- **Returns**:  
  `string` The sanitized meta box name.



#### 4. `setArgs(?array $args = null): array`

Processes and normalizes the meta box arguments, ensuring defaults (e.g., `zebra => true`) are set.

```php
protected function setArgs(?array $args = null): array
```

- **Parameters**:
  - `?array $args`: The raw arguments passed into the constructor.
- **Returns**:  
  `array` A normalized array of arguments used internally.



#### 5. `addTableStyle(bool $zebra): void`

Adds inline CSS for the meta box table. If `$zebra` is true, applies alternating row colors.

```php
protected function addTableStyle(bool $zebra): void
```

- **Parameters**:
  - `bool $zebra`: Whether to enable zebra striping.



#### 6. `show_field_id(string $field): string`

A static helper to display the field key/id in the `<th>` tag (for debugging or user reference).

```php
protected static function show_field_id(string $field): string
```

- **Parameters**:
  - `string $field`: The field ID/key.
- **Returns**:  
  `string` HTML for the field ID.



#### 7. `tableCss(): void` and `zebraTable(): void`

Internal helper methods that output specific CSS styles for the table, invoked by `addTableStyle()`.



#### 8. `getClassName(): string`

Retrieves a short name for the class implementing `SettingsInterface` using reflection, or returns `'Unknown'` if not found.

```php
private function getClassName(): string
```

- **Returns**:  
  `string` The short class name or `'Unknown'`.

- **Throws**:
  - `Exception`: If reflection fails for any reason.



### Example Usage

Below is a simple example of how to implement and use the `MetaBox` class. In this example, we have a `VehicleSettings` class that extends a `Settings` class (implementing `SettingsInterface`).

```php
use Urisoft\PostMeta\MetaBox;
use Urisoft\PostMeta\Settings; // Assume this implements SettingsInterface

class VehicleSettings extends Settings
{
    public function settings(): void
    {
        // Define fields (pseudo-code for demonstration).
        $this->input('Vehicle Name', [
            'placeholder' => 'Enter the vehicle name',
        ]);
        $this->textarea('Description');
        $this->select('Type', [
            'car'        => 'Car',
            'truck'      => 'Truck',
            'motorcycle' => 'Motorcycle',
        ]);
        $this->input('Top Speed (mph)', [
            'type'        => 'number',
            'placeholder' => 'Enter top speed in mph',
        ]);
    }
}

// Instantiate your settings class, passing a post type slug (e.g., 'vehicle').
$vehicleSettings = new VehicleSettings('vehicle');

// Create and register the MetaBox for 'vehicle' posts.
(new MetaBox($vehicleSettings, [
    'name'  => 'Vehicle Details',
    'zebra' => true, // Enable zebra striping in the admin table.
]))->register();
```

When you edit a post of type `vehicle` in the WordPress admin, you’ll see a "Vehicle Details" meta box containing the fields you defined in `VehicleSettings`.

WordPress will automatically call the `saveMeta()` method on post save/update (if you enabled it via `$withSavePost = true`), which handles sanitization and storage of the field data.


### Notes

1. **Validation & Sanitization**:  
   Validation and sanitization are delegated to the `SettingsInterface` and the field definitions you provide there. This ensures consistency and separation of concerns.

2. **Actions & Filters**:  
   - You can hook into `cpm_before_meta_update` or `cpm_after_meta_update` to run any additional logic before or after the data is saved.  
   - The filter `$this->metaField` allows you to intercept or modify data right before it is saved.

3. **Multiple Post Types**:  
   If your `SettingsInterface` or `$args` supports multiple post types, you can adapt this class accordingly by either passing in an array of post types or creating multiple `MetaBox` instances.

4. **Styling**:  
   The `zebra` argument toggles alternating row background colors for the meta box table. You can also implement your own styles by overriding the `tableCss()` or `zebraTable()` methods.

5. **Error Handling**:  
   Exceptions are thrown if required properties are missing or invalid in the form definition. Ensure you have adequate try/catch blocks where needed (e.g., inside your `render()` method).



### Related Classes / Interfaces

- **`SettingsInterface`**:  
  Defines a contract for building out meta fields and sanitizing data.  
- **`Form`**:  
  Used by `MetaBox` to generate the HTML for the fields.  
- **`PostType`** (if applicable):  
  A utility class for registering and managing custom post types, which can be passed to `register()`.
