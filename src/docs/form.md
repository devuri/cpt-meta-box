# Form Class

The **`Form`** class in the **cptMeta** package handles the generation, rendering, and basic processing of form fields within WordPress. It provides a set of methods for building various field types (e.g., `input`, `select`, `textarea`, `editor`), as well as utilities for managing contexts, generating HTML tables, sanitizing field names, and more.

---

## Namespace

```php
namespace Urisoft\PostMeta\Form;
```

---

## Overview

- **Renders** form fields (input, textarea, select, editor, etc.) and organizes them in an HTML table layout.
- **Manages** an internal array of `$fields` to keep track of all generated fields.
- **Provides** utility methods for sanitation, nonce verification, spinners, and user feedback messages.
- **Integrates** seamlessly with other classes (e.g., `Settings`, `MetaBox`) to handle WordPress meta field rendering.

---

## Properties

| Property          | Type     | Description                                                                                                       |
|-------------------|----------|-------------------------------------------------------------------------------------------------------------------|
| `static $version` | `string` | Tracks the version of this class.                                                                                 |
| `$processing`     | `bool`   | Indicates whether the form is in a processing state. Default is `false`.                                          |
| `$context`        | `array`  | An associative array containing context information, such as `fields` or `post_type`.                            |
| `$fields`         | `array`  | Holds details for each field registered via `addField()`.                                                         |
| `$wpnonce`        | `string` | Stores the name of the WordPress nonce field after creation.                                                      |

---

## Constructor

```php
public function __construct(?array $context = [])
```

**Description:**
Creates a new instance of the `Form` class and optionally sets up an initial `$context`. The constructor also initializes an empty `$fields` array.

**Parameters:**
- **`$context`** (`?array`, optional):
  An associative array of initial context data (e.g., `['fields' => [...], 'post_type' => 'book']`). Defaults to an empty array.

---

## Public Methods

### 1. `setContext(array $context = []): self`

```php
public function setContext(array $context = []): self
```

**Description:**
Updates the internal `$context` property with the provided array and returns the current `Form` instance for method chaining.

**Parameters:**
- **`$context`** (`array`, optional): Array of context data.

**Returns:**
- **`self`**: The current `Form` instance.

---

### 2. `getContext(): ?array`

```php
public function getContext(): ?array
```

**Description:**
Retrieves the entire `$context` array.

**Returns:**
- **`?array`**: The context array, or `null` if none is set.

---

### 3. `make(array $params = []): void`

```php
public function make(array $params = []): void
```

**Description:**
Creates a form field based on a `field` type specified in `$params`. Supported types are:
- `input`
- `textarea`
- `select`
- `editor`

It then calls the corresponding method (`input()`, `textarea()`, `select()`, or `editor()`) after validating required parameters.

**Parameters:**
- **`$params`** (`array`): Must include at least a `'field'` key with the type, plus other keys depending on the field type:
  - `'label'`, `'val'`, `'options'`, `'id'`, etc.

**Throws:**
- **`InvalidArgumentException`** if the `'field'` key is missing or if required parameters for a specific field type are missing.

---

### 4. `getFields(): ?array`

```php
public function getFields(): ?array
```

**Description:**
Returns the internal array of fields registered via `addField()`.

**Returns:**
- **`?array`**: The array of fields, or `null` if none exist.

---

### 5. `textarea(string $fieldTitle = 'name', string $val = '', array $args = []): string`

```php
public function textarea($fieldTitle = 'name', $val = '', array $args = []): string
```

**Description:**
Generates and returns an HTML `<textarea>` field wrapped in a table row, including a `<th>` for the label and a `<td>` for the textarea itself.

**Parameters:**
- **`$fieldTitle`** (`string`, optional): The label/name for the field (used for ID and name attributes).
- **`$val`** (`string`, optional): Pre-filled value for the textarea.
- **`$args`** (`array`, optional): Additional parameters or attributes.

**Returns:**
- **`string`**: The generated HTML for the textarea.

---

### 6. `text_area(...)`

```php
public function text_area($fieldTitle = 'name', $val = '', $args = []): string
```

**Description:**
Alias for `textarea()`. Functionally identical, just a different name.

---

### 7. `editor(string $fieldTitle, $content = '', ?string $editor_id = null, $options = []): string`

```php
public function editor(
    string $fieldTitle,
    $content = '',
    ?string $editor_id = null,
    $options = []
): string
```

**Description:**
Generates and returns a table row containing the WordPress rich text editor. You can pass additional editor settings in `$options`.

**Parameters:**
- **`$fieldTitle`** (`string`): Used to construct IDs/labels for the editor.
- **`$content`** (`string`): Initial content.
- **`$editor_id`** (`?string`): A specific ID for the editor instance (default uses the sanitized field title).
- **`$options`** (`array`): Additional arguments for `wp_editor()`.

**Returns:**
- **`string`**: The generated HTML table row containing the editor.

---

### 8. `userFeedback($message = 'Options updated', $class = 'success', $element_id = 'user-feedback'): string`

```php
public function userFeedback(
    $message = 'Options updated',
    $class = 'success',
    $element_id = 'user-feedback'
): string
```

**Description:**
Generates a styled WordPress admin notice `<div>` containing a feedback message.

**Parameters:**
- **`$message`** (`string`): The message to display.
- **`$class`** (`string`): The CSS class for styling (e.g., `'success'`, `'error'`).
- **`$element_id`** (`string`): The HTML `id` of the notice element.

**Returns:**
- **`string`**: The generated HTML notice.

---

### 9. `thickboxLink($linktext = 'click here', $id = ''): string`

```php
public function thickboxLink($linktext = 'click here', $id = ''): string
```

**Description:**
Generates an `<a>` link that opens a ThickBox inline content overlay. Useful for displaying hidden content within a modal.

**Parameters:**
- **`$linktext`** (`string`, optional): The link text.
- **`$id`** (`string`, optional): ID pointing to the ThickBox inline content.

**Returns:**
- **`string`**: The generated `<a>` element.

---

### 10. `getOption($option)`

```php
public function getOption($option)
```

**Description:**
Fetches an option value from the WordPress database via `get_option()` and escapes it with `esc_attr()`.

**Parameters:**
- **`$option`** (`string`): The name of the option to retrieve.

**Returns:**
- **`string`**: The escaped option value, or an empty string if not found.

---

### 11. `isDescription($descriptionInfo = false): ?string`

```php
public function isDescription($descriptionInfo = false): ?string
```

**Description:**
Creates a `<span>` element for additional descriptive text if `$descriptionInfo` is provided. Can also indicate required fields.

**Parameters:**
- **`$descriptionInfo`** (`bool|string`, optional): If truthy, returns an HTML `<span>`. Otherwise returns `null`.

**Returns:**
- **`?string`**: The HTML description `<span>` or `null`.

---

### 12. `tr($html = null, $hr = '<hr>'): string`

```php
public function tr($html = null, $hr = '<hr>'): string
```

**Description:**
Wraps the provided `$html` content in a `<tr>` element with a bottom border. Optionally includes a `<hr>`.

**Parameters:**
- **`$html`** (`?string`, optional): Content to go inside the table row.
- **`$hr`** (`string`, optional): Unused param for a horizontal rule or extra content.

**Returns:**
- **`string`**: The resulting `<tr>` block.

---

### 13. `table($tag = 'close', $tbclass = ''): ?string`

```php
public function table($tag = 'close', $tbclass = ''): ?string
```

**Description:**
Creates either the opening or closing `<table>` tag with optional CSS classes. Pass `'open'` for opening and `'close'` for closing. Returns `null` if an invalid parameter is given.

**Parameters:**
- **`$tag`** (`string`, optional): `'open'` or `'close'`.
- **`$tbclass`** (`string`, optional): Additional class names for the `<table>`.

**Returns:**
- **`?string`**: The `<table>` or `</table>` tag, or `null` if invalid.

---

### 14. `submitButton($text = 'Save Changes', $type = 'primary large', $name = 'submit', $wrap = ''): string`

```php
public function submitButton(
    $text = 'Save Changes',
    $type = 'primary large',
    $name = 'submit',
    $wrap = ''
): string
```

**Description:**
Generates a WordPress-style submit button via `get_submit_button()`.

**Parameters:**
- **`$text`** (`string`): Button label.
- **`$type`** (`string`): Class(es) for styling (e.g., `'primary large'`).
- **`$name`** (`string`): The `name` attribute.
- **`$wrap`** (`bool|string`): Whether to wrap the button in a `<p>` tag.

**Returns:**
- **`string`**: The generated HTML for the button.

---

### 15. `access($role = 'admin'): string`

```php
public function access($role = 'admin'): string
```

**Description:**
Returns the WordPress capability required for a given role (admin, editor, author, etc.). By default returns `'manage_options'` for admin.

---

### 16. `getSpinner($css = []): void`

```php
public function getSpinner($css = []): void
```

**Description:**
Outputs a custom spinner loader with inline CSS. The `$css` array can customize padding and size.

**Parameters:**
- **`$css`** (`array`, optional):
  - `'padding'` (string), `'padding-bottom'` (string), `'size'` (string).

---

### 17. `select(string $fieldTitle = 'name', array $options = [], array $args = []): string`

```php
public function select(
    string $fieldTitle = 'name',
    array $options = [],
    array $args = []
): string
```

**Description:**
Creates a `<select>` field within a table row, including a label, description, and default placeholder option. It automatically sets the `'selected'` item if provided in `$options['selected']`.

**Parameters:**
- **`$fieldTitle`** (`string`, optional): Label or name for the field.
- **`$options`** (`array`, optional): Key-value pairs for `<option>` elements.
- **`$args`** (`array`, optional): Additional attributes like `'js'`, `'required'`, etc.

**Returns:**
- **`string`**: The generated HTML `<select>` block.

---

### 18. `getNonce($action = -1, $name = '_cptmeta_wpnonce', $referer = true, $display = false): string`

```php
public function getNonce(
    $action = -1,
    $name = '_cptmeta_wpnonce',
    $referer = true,
    $display = false
): string
```

**Description:**
Generates a hidden nonce field via `wp_nonce_field()` and saves the `$name` in `$this->wpnonce`.

**Parameters:**
- **`$action`** (`int|string`, optional): Nonce action.
- **`$name`** (`string`, optional): Nonce name attribute.
- **`$referer`** (`bool`, optional): Whether to include a hidden referrer field.
- **`$display`** (`bool`, optional): Whether to echo the field (`true`) or return it (`false`).

**Returns:**
- **`string`**: The nonce field HTML markup.

---

### 19. `nonce($name = '_cptmeta_wpnonce'): void`

```php
public function nonce($name = '_cptmeta_wpnonce'): void
```

**Description:**
Echos the result of `getNonce()`. Allows quickly inserting a nonce field in a form.

**Parameters:**
- **`$name`** (`string`, optional): The name of the nonce field.

---

### 20. `verifyNonce(?string $noncefield = '_cptmeta_wpnonce'): bool`

```php
public function verifyNonce(?string $noncefield = '_cptmeta_wpnonce'): bool
```

**Description:**
Checks if the nonce in `$_POST` with the provided `$noncefield` is valid using `wp_verify_nonce()`.

**Parameters:**
- **`$noncefield`** (`?string`, optional): The key in `$_POST` storing the nonce.

**Returns:**
- **`bool`**: `true` if valid, `false` otherwise.

---

### 21. `input($fieldTitle = 'item name', $val = '', array $args = []): string`

```php
public function input($fieldTitle = 'item name', $val = '', array $args = []): string
```

**Description:**
Generates a standard `<input>` field (e.g., `type="text"`, `type="number"`, etc.) in an HTML table row with label and description. Allows optional CSS classes, placeholder text, and a submit button.

**Parameters:**
- **`$fieldTitle`** (`string`, optional): Name/label for the input.
- **`$val`** (`string`, optional): Default value.
- **`$args`** (`array`, optional): Additional parameters (`required`, `disabled`, `type`, etc.).

**Returns:**
- **`string`**: The generated HTML for the input field.

---

### 22. `inputVal($input_field = null): ?string`

```php
public function inputVal($input_field = null): ?string
```

**Description:**
Retrieves a sanitized value (`sanitize_text_field()`) from `$_POST[$input_field]`. Returns `null` if not set or empty.

**Parameters:**
- **`$input_field`** (`?string`, optional): Name of the field in `$_POST`.

**Returns:**
- **`?string`**: Sanitized value or `null`.

---

### 23. `button(string $name = 'Send', $label = null, $description = '', ?string $bg_color = null): string`

```php
public function button(
    string $name = 'Send',
    $label = null,
    $description = '',
    ?string $bg_color = null
): string
```

**Description:**
Creates a `<tr>` containing a button and optional label/description. Often used for inline actions in forms.

**Parameters:**
- **`$name`** (`string`, optional): Button text.
- **`$label`** (`?string`, optional): Label shown in `<th>`.
- **`$description`** (`string`, optional): Additional descriptive text in `<td>`.
- **`$bg_color`** (`?string`, optional): Background color for the row.

**Returns:**
- **`string`**: The generated HTML table row with a button.

---

### 24. `info(?string $th_label = null, $text = null, $description = null, $hr = false, $bg_color = null): string`

```php
public function info(
    ?string $th_label = null,
    $text = null,
    $description = null,
    $hr = false,
    $bg_color = null
): string
```

**Description:**
Creates a table row displaying some informational text. Includes an optional bottom border (`$hr`) and a background color (`$bg_color`).

---

### 25. `inputRow(...)`

```php
public function inputRow(string $title, string $field_title, string $value = '', string $description = '', string $type = 'text', string $checked_value = '1'): void
```

**Description:**
Echoes a table row containing a label in `<th>` and an `<input>` or `<checkbox>` in `<td>`, plus an optional description.

**Parameters:**
- **`$title`** (`string`): The label in `<th>`.
- **`$field_title`** (`string`): Name attribute for the input.
- **`$value`** (`string`, optional): Current input value or default.
- **`$description`** (`string`, optional): Adds a `<p class="description">`.
- **`$type`** (`string`, optional): `'text'` or `'checkbox'`.
- **`$checked_value`** (`string`, optional): Value to check if `checkbox` is selected.

---

### 26. `upload($fieldTitle = 'upload_image_button', $val = 'Upload Image', $required = false, $type = 'button'): string`

```php
public function upload(
    $fieldTitle = 'upload_image_button',
    $val = 'Upload Image',
    $required = false,
    $type = 'button'
): string
```

**Description:**
Generates an upload button table row with label and optional attributes.

---

### 27. `imageGrid(array $images = [], array $element = []): ?string`

```php
public function imageGrid(array $images = [], array $element = []): ?string
```

**Description:**
Creates a draggable grid layout (using inline images) if `$images` are provided. Each image is wrapped with the class and IDs from `$element`.

**Parameters:**
- **`$images`** (`array`): Array of image attachment IDs.
- **`$element`** (`array`): Custom attributes like `'img_class'`, `'div_id'`, `'input_id'`.

**Returns:**
- **`?string`**: The generated `<tr>` or `null` if no images.

---

### 28. `image($itm_id, string $itm_class): string`

```php
public function image($itm_id, string $itm_class): string
```

**Description:**
Creates an `<img>` tag for a specific attachment ID with a given CSS class.

**Parameters:**
- **`$itm_id`** (`int`): Attachment ID.
- **`$itm_class`** (`string`): CSS class.

**Returns:**
- **`string`**: The generated `<img>` tag.

---

### 29. `thumbnail(): ?string`

```php
public function thumbnail(): ?string
```

**Description:**
If `$this->postObject` is set and the post has a featured image, returns an `<img>` showing it. Otherwise `null`.

---

### 30. `list(?array $options = [], string $fieldTitle = 'name', ?string $js = null, bool $required = false): string`

```php
public function list(
    ?array $options = [],
    string $fieldTitle = 'name',
    ?string $js = null,
    bool $required = false
): string
```

**Description:**
Generates a `<datalist>` input field, allowing users to pick from a predefined list. Similar to an autocomplete.

---

### 31. `getPages($arg = []): array`

```php
public function getPages($arg = []): array
```

**Description:**
Retrieves an array of pages from WordPress, returning an array of `[page_id => page_title]`.

**Parameters:**
- **`$arg`** (`array`, optional): Arguments for `get_pages()`. Default sorts by date descending.

**Returns:**
- **`array`**: `[id => 'Page Title']`.

---

### 32. `sanitize(string $fieldTitle, bool $use_dashes = false): string`

```php
public function sanitize(string $fieldTitle, bool $use_dashes = false): string
```

**Description:**
Sanitizes a string for use as an HTML ID or key. Uses `sanitize_file_name()` and `sanitize_key()`. By default, replaces dashes (`-`) with underscores (`_`), unless `$use_dashes` is `true`.

---

### 33. `categorylist($fieldTitle = null, $args = []): string`

```php
public function categorylist($fieldTitle = null, $args = []): string
```

**Description:**
Generates a table row containing a WordPress category dropdown via `wp_dropdown_categories()`, wrapped in `<th>` and `<td>`.

---

### 34. `createCategory(string $class = ''): void`

```php
public function createCategory(string $class = ''): void
```

**Description:**
Outputs a table row allowing the user to create a new category. Renders a simple text field.

---

### 35. `hashId(array $inputParams): string` *(protected static)*

```php
protected static function hashId(array $inputParams): string
```

**Description:**
Generates a unique hash string (FNVa64) from serialized input parameters, used to create unique field IDs.

---

### 36. `addField(array $inputParams): void` *(protected)*

```php
protected function addField(array $inputParams): void
```

**Description:**
Adds a new field definition to `$fields` after generating a unique UUID hash. Throws a PHP notice if the field ID already exists.

**Parameters:**
- **`$inputParams`** (`array`): Must include `'id'` and `'output'`, plus additional keys like `'field'`, `'title'`, etc.

---

### 37. `getParam(string $key, $params = [])` *(protected)*

```php
protected function getParam(string $key, $params = [])
```

**Description:**
Safely retrieves a parameter from `$params` by key. Returns `null` if not found.

---

### 38. `inputButton(?string $button): ?string` *(protected)*

```php
protected function inputButton(?string $button): ?string
```

**Description:**
If `$button` is not null, creates a submit button using `submitButton()`. Otherwise, returns null.

---

### 39. `spinnerStyle($css = []): void` *(private)*

```php
private function spinnerStyle($css = []): void
```

**Description:**
Outputs a custom CSS animation for an inline spinner. Respects optional styles like width, height, and padding from `$css`.

---

### 40. `selected(array $options): string` *(private static)*

```php
private static function selected(array $options): string
```

**Description:**
If `$options` has a `'selected'` key, returns that value; otherwise an empty string. Used to set the default selected option in `<select>` fields.

---

## Example Usage

```php
use Urisoft\PostMeta\Form\Form;

function my_custom_form_render() {
    $form = new Form([
        'post_type' => 'book',
        'fields'    => [],
    ]);

    // Start rendering the table
    echo $form->table('open');

    // Render an input field
    echo $form->input('Book Title', 'The Great Gatsby', [
        'type'     => 'text',
        'required' => true,
        'info'     => 'Title of the book',
        'class'    => 'widefat',
    ]);

    // Render a textarea
    echo $form->textarea('Synopsis', 'A novel about...', [
        'rows'    => 5,
        'columns' => 40,
    ]);

    // Closing the table
    echo $form->table('close');

    // Nonce for security
    $form->nonce('_my_form_nonce');

    // Submit button
    echo $form->submitButton('Save Book', 'primary', 'save_book', true);
}
```

---

## Notes

1. **Field Registration**:
   Each method (e.g., `input`, `textarea`, `select`) calls `addField()` internally, keeping track of all generated fields in `$this->fields`.

2. **Context Usage**:
   `$context` can store additional data needed by external classes or custom logic (e.g., `post_type`, `fields` array, etc.).

3. **WordPress Integration**:
   - Uses WordPress-specific functions like `wp_nonce_field()`, `wp_verify_nonce()`, `wp_dropdown_categories()`, and `wp_editor()`.
   - Ensure this class is used only in a WordPress environment.

4. **Sanitization & Security**:
   - The `sanitize()` method ensures IDs and names are consistent across form fields.
   - Nonce methods help prevent CSRF attacks.

5. **Extendability**:
   If additional field types are needed (e.g., datepickers, color pickers), you can create similar methods by following the same pattern.

6. **Spinner and Styles**:
   The spinner functions rely on inline CSS, which you can modify or replace with your own styling approach.
