# Input Fields in the `input` Method

The `input` method provides a flexible way to generate HTML `<input>` fields, each wrapped in a table row (`<tr>`) for easy layout within the WordPress admin or other UIs. It accepts various parameters to customize the input field’s behavior, styling, and additional elements such as a submit button.



## Method Signature

```php
public function input(
    string $fieldTitle = 'item name',
    string $val = '',
    array $args = []
): string
```

- **`$fieldTitle`**: The user-facing label for the field, also used to generate the `name` and `id` attributes.  
- **`$val`**: The default value that appears in the `<input>` tag.  
- **`$args`**: An associative array of options to customize the input’s behavior, styling, or additional features.



## Parameters Overview

Below is a breakdown of each parameter and how it affects the generated HTML.

### **1. `$fieldTitle`**
- **Type:** `string`
- **Default:** `'item name'`
- **Description:**  
  - Serves as both the visible label (`<label>`) and the name/id of the input.  
  - Automatically sanitized into two forms:
    - Hyphenated for CSS classes (e.g., `item-name`)  
    - Underscored for the field’s ID (e.g., `item_name`)  

**Example**:  
```php
$this->input('Product Name');
```
Generates an ID like `product_name` and a class reference `input-product-name`.



### **2. `$val`**
- **Type:** `string`
- **Default:** `''` (empty string)
- **Description:**  
  - Determines the `<input>` element’s initial `value` attribute.

**Example**:  
```php
$this->input('Quantity', '5');
```
Produces an `<input value="5">` by default.



### **3. `$args`**
- **Type:** `array`
- **Default:** `[]`
- **Description:**  
  - Allows advanced customization of the field.  
  - Each key modifies a specific behavior or attribute.

The following keys are supported:

#### **`required`**
- **Type:** `bool`
- **Default:** `false`
- **Description:**
  - Adds an HTML `required` attribute to the `<input>`.
  - Typically used for validation when submitting forms.

**Example**:  
```php
['required' => true]
```
Produces `<input required>`.



#### **`class`**
- **Type:** `string`
- **Default:** `'uk-input form-control'` (or similar, depending on defaults)
- **Description:**  
  - Adds one or more CSS classes to the input element for styling.

**Example**:  
```php
['class' => 'custom-input large-input']
```
Generates `<input class="custom-input large-input">`.



#### **`type`**
- **Type:** `string`
- **Default:** `'text'`
- **Description:**  
  - Sets the HTML input type, such as `text`, `email`, `password`, `number`, `search`, or `hidden`.

**Example**:  
```php
['type' => 'email']
```
Creates `<input type="email">`.



#### **`button`**
- **Type:** `string`
- **Default:** `null`
- **Description:**  
  - If defined, a `<button type="submit">` is rendered in a separate `<td>` next to the input field with the given label text.

**Example**:  
```php
['button' => 'Search']
```
Generates a “Search” submit button alongside the input.



#### **`hidden`**
- **Type:** `bool`
- **Default:** `false`
- **Description:**  
  - If `true`, adds a `hidden` attribute to the `<input>` field.

**Note**:  
Usually, for an actual hidden field, you would set `type => 'hidden'`. This parameter can be used for consistency with other attribute toggles.



#### **`disabled`**
- **Type:** `bool`
- **Default:** `false`
- **Description:**  
  - If `true`, adds a `disabled` attribute, preventing user interaction.

**Example**:  
```php
['disabled' => true]
```
Produces `<input disabled>`.



#### **`info`**
- **Type:** `bool|string`
- **Default:** `false`
- **Description:**  
  - If a string is provided, that text is rendered below the input field in a `<p>` tag.  
  - If `true`, a descriptive indicator (e.g., “Required”) may be shown, depending on your implementation.

**Example**:  
```php
['info' => 'Enter a valid email address.']
```
Outputs `<p class="description">Enter a valid email address.</p>` below the field.



#### **`width`**
- **Type:** `string`
- **Default:** `''` (empty, meaning no width specified)
- **Description:**  
  - Sets the `width` attribute on the `<td>` wrapper. Can be a percentage or pixel value.

**Example**:  
```php
['width' => '50%']
```
Generates `<td width="50%">`.



#### **`icon`**
- **Type:** `string`
- **Default:** `dashicons-arrow-right`
- **Description:**  
  - Displays a [Dashicon](https://developer.wordpress.org/resource/dashicons/) to the left of the input label.  
  - You can specify any Dashicon class name (e.g., `dashicons-admin-users`, `dashicons-search`).

**Example**:  
```php
['icon' => 'dashicons-admin-users']
```
Yields `<span class="dashicons dashicons-admin-users"></span>`.



## Generated HTML Structure

Given the parameters above, here is a representative structure for the output:

```html
<tr class="input-[hyphenated-fieldTitle]">
    <th>
        <span class="dashicons [icon-class]"></span>
        <label for="[underscored_fieldTitle]">
            [Formatted Field Title]
        </label>
    </th>
    <td width="[width]">
        <input
            type="[type]"
            name="[underscored_fieldTitle]"
            id="[underscored_fieldTitle]"
            aria-describedby="[hyphenated-fieldTitle]"
            value="[val]"
            class="[class]"
            [required|disabled|hidden...]
        >
        <p class="description" id="[underscored_fieldTitle]">
            [info text if provided]
        </p>
    </td>
    <td>
        [Optional Submit Button if 'button' => 'Search' or similar]
        <p class="description" style="visibility: hidden;">...</p>
    </td>
</tr>
```

- **`class="input-[hyphenated-fieldTitle]"`**: Helps identify rows for styling.  
- **`<th>`** element includes the optional Dashicon plus a `<label>` referencing the `id`.  
- **`<td width="[width]"`** sets a custom width if provided.  
- **`aria-describedby="[hyphenated-fieldTitle]"`** ties the field to its description for screen readers.  



## Accessibility

- Each `<input>` is wrapped with a corresponding `<label>` for.  
- `aria-describedby` associates screen readers with the `<p class="description">`.  
- Ensures that all form fields are labeled and accessible for assistive technologies.



## Usage Examples

### **1. Basic Input Field**

```php
$this->input('Username', '', [
    'type' => 'text',
    'required' => true,
    'class' => 'user-input',
    'info' => 'Enter your username.'
]);
```

**Generated HTML:**

```html
<tr class="input-username">
    <th>
        <span class="dashicons dashicons-arrow-right"></span>
        <label for="username">Username</label>
    </th>
    <td width="">
        <input type="text" name="username" id="username" aria-describedby="username" value="" class="user-input" required>
        <p class="description" id="username">Enter your username.</p>
    </td>
    <td>
        <p class="description" style="visibility: hidden;">...</p>
    </td>
</tr>
```



### **2. Input Field with Submit Button**

```php
$this->input('Search', '', [
    'type' => 'search',
    'button' => 'Go'
]);
```

**Generated HTML:**

```html
<tr class="input-search">
    <th>
        <span class="dashicons dashicons-arrow-right"></span>
        <label for="search">Search</label>
    </th>
    <td width="">
        <input type="search" name="search" id="search" aria-describedby="search" value="" class="">
        <p class="description" id="search"></p>
    </td>
    <td>
        <button type="submit">Go</button>
        <p class="description" style="visibility: hidden;">...</p>
    </td>
</tr>
```



### **3. Email Input (Required)**

```php
$this->input('Email Address', '', [
    'type' => 'email',
    'required' => true,
    'class' => 'email-input',
    'info' => 'We will never share your email with anyone else.'
]);
```

**Generated HTML:**

```html
<tr class="input-email-address">
    <th>
        <span class="dashicons dashicons-arrow-right"></span>
        <label for="email_address">Email Address</label>
    </th>
    <td width="">
        <input type="email" name="email_address" id="email_address" aria-describedby="email_address" value="" class="email-input" required>
        <p class="description" id="email_address">We will never share your email with anyone else.</p>
    </td>
    <td>
        <p class="description" style="visibility: hidden;">...</p>
    </td>
</tr>
```



### **4. Number Input with Custom Width**

```php
$this->input('Quantity', '1', [
    'type' => 'number',
    'class' => 'quantity-input',
    'info'  => 'Enter the quantity.',
    'width' => '50%'
]);
```

**Generated HTML:**

```html
<tr class="input-quantity">
    <th>
        <span class="dashicons dashicons-arrow-right"></span>
        <label for="quantity">Quantity</label>
    </th>
    <td width="50%">
        <input type="number" name="quantity" id="quantity" aria-describedby="quantity" value="1" class="quantity-input">
        <p class="description" id="quantity">Enter the quantity.</p>
    </td>
    <td>
        <p class="description" style="visibility: hidden;">...</p>
    </td>
</tr>
```



### **5. Disabled Input Field**

```php
$this->input('Read Only Value', '42', [
    'type' => 'text',
    'disabled' => true,
    'info' => 'This field is not editable.'
]);
```

**Generated HTML:**

```html
<tr class="input-read-only-value">
    <th>
        <span class="dashicons dashicons-arrow-right"></span>
        <label for="read_only_value">Read Only Value</label>
    </th>
    <td width="">
        <input type="text" name="read_only_value" id="read_only_value" aria-describedby="read_only_value" value="42" disabled>
        <p class="description" id="read_only_value">This field is not editable.</p>
    </td>
    <td>
        <p class="description" style="visibility: hidden;">...</p>
    </td>
</tr>
```



### **6. Input Field with a Custom Dashicon**

```php
$this->input('Username', '', [
    'type' => 'text',
    'class' => 'user-input',
    'icon' => 'dashicons-admin-users',
    'info' => 'Enter your username.',
]);
```

**Generated HTML:**

```html
<tr class="input-username">
    <th>
        <span class="dashicons dashicons-admin-users"></span>
        <label for="username">Username</label>
    </th>
    <td width="">
        <input type="text" name="username" id="username" aria-describedby="username" value="" class="user-input">
        <p class="description" id="username">Enter your username.</p>
    </td>
    <td>
        <p class="description" style="visibility: hidden;">...</p>
    </td>
</tr>
```



## Summary

The **`input`** method offers a highly customizable approach to generating `<input>` fields within an HTML table layout. By adjusting the `type`, classes, and optional parameters in `$args`, you can create a wide range of fields—from simple text inputs to specialized email, password, number, or search fields—complete with optional descriptive texts, submit buttons, and Dashicons.

1. **Accessibility**: Each generated field includes a matching `<label>` and `aria-describedby` attribute for better assistive technology support.  
2. **Styling & Layout**: Table-based rendering with optional custom widths and classes makes it easy to align multiple fields.  
3. **Validation & UX**: The `required` and `disabled` flags help enforce user input constraints and disable fields when necessary.  
4. **Additional Features**: A built-in option for generating a submit button in the same row, and easy-to-add descriptive text (`info`) for inline instructions.

Use these examples as a reference for integrating the `input` method in your plugin or theme to create well-structured and user-friendly admin forms.
