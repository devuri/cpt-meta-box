## 1. Basic Text Input

```php
$form->input(
    'Field Label',     // The label/name for the field
    'Initial Value',   // The default value
    [
        'type' => 'text',            // Default is 'text'
        'class' => 'wide-input',     // Custom CSS classes
        'info'  => 'Field hint...',  // Displays in a description paragraph
    ]
);
```
**Key Parameters**  
- `type`: `'text'` (default), `'email'`, `'password'`, etc.  
- `class`: Add CSS classes to style the `<input>`.  
- `info`: Additional descriptive text below the input.

---

## 2. Textarea

```php
$form->textarea(
    'Content',         // Field label
    'Default text',    // Default value
    [
        'placeholder' => 'Enter full description...',
    ]
);
```
**Key Notes**  
- `textarea()` automatically generates a multiline `<textarea>` in a table row.  
- You can add extra parameters (e.g., `['rows' => 8, 'cols' => 50]`) via the `$args`.

---

## 3. Editor (WordPress WYSIWYG)

```php
$form->editor(
    'Field Label',     // Name/label
    'Existing content',// Default editor content
    'custom_editor_id',// (Optional) Editor ID
    [
        // Additional wp_editor() settings, e.g., 'media_buttons' => true
    ]
);
```
**Key Notes**  
- Relies on WordPress’s built-in `wp_editor`.  
- `$editor_id` is optional; if omitted, it uses a sanitized version of your field label.

---

## 4. Select Dropdown

```php
$form->select(
    'Field Label',      // Name/label
    [
        'option_value1' => 'Display Text 1',
        'option_value2' => 'Display Text 2',
        'selected'      => 'option_value2',  // Defaults to this if present
    ],
    [
        'js'      => 'myJavaScriptFunction()', // Onchange JS function
        'required' => true,
    ]
);
```
**Key Parameters**  
- `'selected' => 'option_value'`: Sets which `<option>` is pre-selected.  
- `'js' => 'alert(\"Changed!\")'`: A simple JS snippet for `onchange`.  
- `'required' => true`: Adds an HTML `required` attribute to `<select>`.

---

## 5. Number Input

```php
$form->input(
    'Quantity',
    '1',
    [
        'type' => 'number',
        'info' => 'Enter a quantity between 1 and 1000.',
    ]
);
```
**Key Parameters**  
- Use `type => 'number'` for a numeric spinner in HTML5.  
- Add placeholders, `min`, `max`, or other HTML attributes as needed in `$args`.

---

## 6. Hidden Input

```php
$form->input(
    'Secret Token',
    'abc123',
    [
        'type' => 'hidden',
    ]
);
```
**Key Notes**  
- Renders a `<tr>` with label in `<th>`. If you truly want no visible row, you can style or hide it.  
- Alternatively, pass `'hidden' => true` to hide a text field. But `'type' => 'hidden'` is the standard.

---

## 7. Color Input

```php
$form->input(
    'Favorite Color',
    '#ff0000',
    [
        'type' => 'color',
        'info' => 'Pick a color',
    ]
);
```
**Key Notes**  
- HTML5 `<input type="color">` provides a color picker in modern browsers.

---

## 8. Checkbox / Radio (Using `inputRow` or `type => 'checkbox'`)

**Option 1**: Using `inputRow()` for quick inline checkbox.

```php
$form->inputRow(
    'Agree to Terms?',  // Column heading
    'agree_terms',      // Field name
    '1',                // Current value
    '',                 // Description
    'checkbox',         // Type
    '1'                 // Checked value
);
```
**Option 2**: Using `input()` with `type => 'checkbox'` (less common in table layout).
```php
$form->input(
    'Agree to Terms',
    '1',
    [
        'type' => 'checkbox',
    ]
);
```
**Key Notes**  
- For checkboxes, you’ll likely store a boolean. The library merges the user input accordingly.

---

## 9. Upload Field (Button)

```php
$form->upload(
    'upload_image_button', // Field title or name
    'Upload Image',        // Button text
    false,                 // Required?
    'button'               // Button type: 'button' or 'submit'
);
```
**Key Notes**  
- Creates a table row with a labeled button. Actual handling of file uploads would be in your code or via WordPress media scripts.

---

## 10. Table Start/Close

Use `table('open')` and `table('close')` to wrap your fields in a `<table>` if you need manual control:

```php
echo $form->table('open');
// Render multiple $form->input(), $form->textarea(), etc.
echo $form->table('close');
```

---

## 11. Nonce Generation & Verification

```php
// Output a nonce field
$form->nonce('_custom_nonce_field');

// Later, verify:
if ($form->verifyNonce('_custom_nonce_field')) {
    // process data...
}
```
**Key Notes**  
- Helps protect against CSRF.  
- Store or check the `_custom_nonce_field` name in your `save_post` or processing hook.

---

## 12. Summarized Methods & Their Signatures

Below is a concise method-to-usage mapping:

1. **`input($label, $value, array $args = [])`**  
   Renders an `<input>` of various `type`s (text, email, number, color, hidden, etc.) with optional placeholders, CSS classes, or info text.

2. **`textarea($label, $value, array $args = [])`**  
   Creates a multiline `<textarea>` in a table row.

3. **`text_area($label, $value, array $args = [])`**  
   Alias of `textarea()`.

4. **`select($label, array $options, array $args = [])`**  
   Builds a `<select>` field. Accepts `'selected'` in `$options` to pre-select an option.

5. **`editor($label, $content = '', ?string $editor_id = null, array $args = [])`**  
   Generates a WordPress WYSIWYG editor using `wp_editor()`.

6. **`upload($fieldTitle, $val, $required = false, $type = 'button')`**  
   Creates a button for uploading—commonly used alongside JavaScript or WordPress media scripts.

7. **`inputRow($title, $field_title, $value, $description, $type = 'text', $checked_value = '1')`**  
   A quick inline method for creating table rows with different input types (often used for checkboxes).

8. **`nonce($name = '_cptmeta_wpnonce')`** / **`verifyNonce($name)`**  
   Generates and verifies nonce fields.

9. **`table('open' | 'close', $tbclass = '')`**  
   Opens or closes an HTML table for form layout if you need manual control.

**Other Utility Methods**  
- **`getFields()`**: Returns the array of all fields added to the form.  
- **`spinnerStyle($css = [])`**, **`getSpinner($css = [])`**: Outputs CSS for loading spinners.  
- **`thickboxLink($text, $id)`**: Generates a ThickBox overlay link.  
- **`userFeedback($message, $class, $element_id)`**: Creates an admin notice `<div>`.

---

## Usage Example

```php
// In a meta box rendering callback, for instance:
echo $form->table('open'); // Start the table

echo $form->input('Product Name', 'Example Product', [
    'class' => 'widefat',
    'info'  => 'Enter the product name displayed to users.',
]);

echo $form->textarea('Product Description', '', [
    'placeholder' => 'Describe the product features...',
    'info'        => 'Max 300 words.',
]);

echo $form->select('Category', [
    'books' => 'Books',
    'music' => 'Music',
    'games' => 'Games',
    'selected' => 'music',
]);

echo $form->input('On Sale?', '1', [
    'type' => 'checkbox',
]);

// Nonce for saving
$form->nonce('my_product_nonce');

echo $form->table('close'); // End the table
echo $form->submitButton('Save Product', 'primary large');
```

**Result:**  
- A neat table layout with labeled fields, descriptions, and a final “Save Product” button.

---

## Final Tip

The **`Form`** class is **highly flexible**. You can combine methods to create exactly the fields you need, whether it’s text, numeric, select boxes, checkboxes, color pickers, or a full-fledged WYSIWYG editor. For more advanced needs—like conditional fields or JavaScript-driven logic—you can extend the default methods or hook into WordPress admin scripts for custom behavior.
