# Settings Class

The **`Settings`** abstract class in the **cptMeta** package provides a foundation for defining, rendering, and retrieving meta settings associated with a specific post type in WordPress. It implements the `SettingsInterface` and uses a `Form` object to handle input fields.



## Namespace

```php
namespace Urisoft\PostMeta;
```



## Overview

- **Manages** meta data for a specified post type.  
- **Provides** methods to render and retrieve data for different field types (e.g., `input`, `textarea`, `editor`, `select`).  
- **Integrates** with a `Form` instance to sanitize and structure fields.  
- **Expects** subclasses to define specific fields in the `settings()` method.



## Properties

| Property       | Type      | Description                                                                                                   |
|-|--||
| **`$postType`**  | `string`  | Holds the slug of the post type (e.g., `'vehicle'`, `'movie'`).                                            |
| **`$fields`**    | `array`   | A list of field keys used to manage permissible fields and data sanitation.                                |
| **`$metaData`**  | `?array`  | Stores retrieved meta data for the current post. `null` until a post is set or data is loaded.             |
| **`$postObject`**| `?WP_Post`| Reference to the WordPress post object, or `null` if not set.                                               |
| **`$form`**      | `?Form`   | An instance of the `Form` class, used to generate and manage form fields.                                  |



## Constructor

```php
public function __construct(string $postType, ?array $context = null)
```

**Description:**  
Initializes the `Settings` object for a given post type. Creates and configures the internal `Form` instance either via the provided `$context` or a default context containing `$fields` and `$postType`.

**Parameters:**
- **`$postType`** (*string*):  
  The post type slug (e.g., `'vehicle'`). Throws an exception if empty.
- **`$context`** (*?array*, optional):  
  An associative array used to configure the `Form` object. If `null`, a default context is built internally.

**Throws:**  
- **`Exception`**: If `$postType` is empty.



## Public Methods

### 1. `init(): SettingsInterface`

```php
public function init(): SettingsInterface
```

**Description:**  
Calls the `settings()` method to allow the subclass to define its fields. Returns the current instance, enabling method chaining.

**Returns:**  
- `SettingsInterface`: The current `Settings` instance.



### 2. `getPostType(): string`

```php
public function getPostType(): string
```

**Description:**  
Retrieves the slug of the post type associated with this settings instance.

**Returns:**  
- `string`: The post type slug.



### 3. `getForm(): Form`

```php
public function getForm(): Form
```

**Description:**  
Returns the `Form` instance in use.

**Returns:**  
- `Form`: The form object.



### 4. `create(?WP_Post $postObject, string $metaField): SettingsInterface`

```php
public function create(
    ?WP_Post $postObject,
    string $metaField
): SettingsInterface
```

**Description:**  
Sets up the settings for a specific post. Loads meta data from the WordPress database (using `get_post_meta` on `$metaField`) and injects those values into the relevant form fields. If there are no defined fields, it simply calls `settings()` and returns.

**Parameters:**  
- **`$postObject`** (*?WP_Post*):  
  The current WordPress post object or `null`.  
- **`$metaField`** (*string*):  
  The meta key under which this data is stored.

**Returns:**  
- `SettingsInterface`: Returns itself for method chaining.

**Notes:**  
- Each field’s `output` is processed, with any `{{value}}` placeholder replaced by the actual meta value.



### 5. `settings()`

```php
public function settings()
```

**Description:**  
An empty method meant to be overridden by subclasses to define their specific fields. By default, it returns without changes.

**Usage Example in a subclass** (pseudo-code):
```php
public function settings()
{
    $this->input('Vehicle Name', ['placeholder' => 'Enter the vehicle name']);
    // ... additional fields ...
    return $this;
}
```



### 6. `withContext(MetaBox $metaBox): Form`

```php
public function withContext(MetaBox $metaBox): Form
```

**Description:**  
Adds additional context (post type, meta box reference, etc.) to the `Form` instance. Useful for ensuring the form knows about the environment in which it is rendered.

**Parameters:**  
- **`$metaBox`** (*MetaBox*): The `MetaBox` object that holds additional info (context, meta, etc.).

**Returns:**  
- `Form`: The updated `Form` object.



### 7. `data(): array`

```php
public function data(): array
```

**Description:**  
Builds a sanitized array of post data (`$_POST`) restricted to the keys defined in `$fields`. Uses `sanitize_text_field()` to clean values.

**Returns:**  
- `array`: An associative array of sanitized data keyed by the allowed field names.

**Implementation Details:**  
- Uses `array_filter` with `ARRAY_FILTER_USE_KEY` to only keep items whose keys are in `$fields`.
- Applies `sanitize_text_field()` to each value via `array_map`.



### 8. `getMeta(?string $key = null)`

```php
public function getMeta(?string $key = null)
```

**Description:**  
Retrieves the currently loaded meta data (`$this->metaData`). If `$key` is provided, returns only that key’s value or an empty string if not found; otherwise, returns the entire meta array.

**Parameters:**  
- **`$key`** (*?string*, optional):  
  A specific key within `$metaData`. If `null`, returns the entire array.

**Returns:**  
- `mixed`: The entire meta array or a specific meta value (string if found, empty string if the key does not exist).



## Protected Methods

### 1. `output(string $fieldOutput): void`

```php
protected static function output(string $fieldOutput): void
```

**Description:**  
Echoes a string (often containing rendered HTML for a field). Useful in the `create()` loop for outputting field markup.

**Parameters:**  
- **`$fieldOutput`** (*string*): The string to be output.



### Field Helper Methods

Each of the following helper methods delegates field construction to the internal `Form` object, injecting meta values retrieved from `$this->metaData`. They return the resulting HTML or handle it directly (depending on the `Form` implementation).

1. **`textarea(string $fieldtitle, array $params = [])`**  
2. **`editor(string $fieldtitle)`**  
3. **`input(string $fieldtitle, array $params = [])`**  
4. **`select(string $fieldtitle, array $opts = [], array $params = [])`**

**Parameters (common):**  
- **`$fieldtitle`** (*string*): The label or name for the field.  
- **`$params`** (*array*, optional): Additional attributes for the field (e.g., placeholders, class names, or any custom data).  
- **`$opts`** (*array*, optional for `select`): An array of options, plus the `'selected'` key to mark a default selected value.  



## Private Methods

### `setForm(?array $context = []): Form`

```php
private static function setForm(?array $context = []): Form
```

**Description:**  
A static factory-like method to instantiate the `Form` object with the given context. If no context is provided, a default set (containing `$fields` and `$postType`) is used.

**Parameters:**  
- **`$context`** (*?array*, optional): The context array for the `Form`.

**Returns:**  
- `Form`: A new `Form` instance configured with the context.



## Usage Example

Below is a pseudo-code example of a subclass that extends `Settings` to define fields for a “Vehicle” post type:

```php
use Urisoft\PostMeta\Settings;

class VehicleSettings extends Settings
{
    protected $fields = [
        'vehicle_name',
        'vehicle_description',
        'vehicle_type',
        'top_speed',
    ];

    public function settings()
    {
        // Define meta fields and any parameters needed
        $this->input('vehicle_name', [
            'placeholder' => 'Enter the vehicle name',
        ]);
        $this->textarea('vehicle_description', [
            'placeholder' => 'Enter a description for the vehicle',
        ]);
        $this->select('vehicle_type', [
            'car'        => 'Car',
            'truck'      => 'Truck',
            'motorcycle' => 'Motorcycle',
        ]);
        $this->input('top_speed', [
            'type'        => 'number',
            'placeholder' => 'Top Speed (mph)',
        ]);

        return $this; // allow chaining
    }
}
```

**Integration with MetaBox:**

```php
$vehicleSettings = new VehicleSettings('vehicle');
$metaBox = new MetaBox($vehicleSettings);
$metaBox->register();
```

Once registered, WordPress will call the `create()` method on `VehicleSettings` when rendering the meta box, loading existing meta data and rendering the defined fields.



## Notes

1. **Abstract Class**:  
   The `Settings` class must be subclassed. You can’t instantiate it directly.

2. **Meta Loading**:  
   The meta data (`$metaData`) is loaded in the `create()` method via `get_post_meta()`. Ensure this is called during the appropriate WordPress hooks (usually within meta box rendering).

3. **Field Definitions**:  
   Subclasses should implement their own logic in `settings()` to define which fields to render (using the helper methods).

4. **Sanitization**:  
   The `data()` method uses `sanitize_text_field()` for all fields by default. For more complex field types (e.g., WYSIWYG editors), consider overriding or adding additional sanitization logic if needed.

5. **Customization**:  
   Each helper method (`input`, `textarea`, etc.) can be overridden to apply custom markup or logic if the default `Form` output is insufficient.

6. **Error Handling**:  
   An exception is thrown if an invalid (empty) `$postType` is passed to the constructor. Consider catching this in your plugin or theme setup code.



## Related

- **`MetaBox`** class: Handles the registration of the meta box, saving data, and hooking into WordPress actions.  
- **`Form`** class: Responsible for generating HTML for the defined fields.  
- **`MetaTrait`**: Additional helper methods for meta operations.  
- **`SettingsInterface`**: Contract that `Settings` must fulfill to ensure consistency in handling meta fields.
