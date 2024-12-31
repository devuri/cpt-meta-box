# Input Fields in the `input` Method

The `input` method is a customizable way to generate HTML input fields with various optional parameters and features. Below is a guide to the possible input fields and configurations.

## Parameters Overview

### **1. `$fieldTitle`**
- **Type:** `string`
- **Description:** The name of the field, defaulting to `'item name'`.
- **Details:**
  - It is sanitized into two forms:
    - Hyphenated format for general usage (`item-name`).
    - Underscored format for the field ID (`item_name`).
  - Used as both the label and the `name` attribute of the input field.
- **Example:**
  - Input: `Product Name`
  - Sanitized Field Name: `product-name`
  - Sanitized Field ID: `product_name`

---

### **2. `$val`**
- **Type:** `string`
- **Description:** The default value of the input field.
- **Example:**
  - If `$val = '123'`, the input field's initial value will be `123`.

---

### **3. `$args`**
- **Type:** `array`
- **Description:** An optional array of additional parameters to customize the input field.
- **Supported Keys:**

#### **`required`**
- **Type:** `bool`
- **Description:** If `true`, the input field will be marked as required.
- **Default:** `false`
- **Effect:** Adds the `required` attribute to the `<input>` element.

---

#### **`class`**
- **Type:** `string`
- **Description:** Adds custom CSS classes to the input field.
- **Example:**
  - Input: `custom-class another-class`
  - Result: `<input class="custom-class another-class">`

---

#### **`type`**
- **Type:** `string`
- **Description:** Specifies the type of the input field. Examples include:
  - `text` (default)
  - `email`
  - `password`
  - `number`
  - `hidden`

---

#### **`button`**
- **Type:** `string`
- **Description:** If set, a submit button with the given label will be added.
- **Example:**
  - Input: `'Submit'`
  - Result: A submit button labeled "Submit" will appear next to the input field.

---

#### **`hidden`**
- **Type:** `bool`
- **Description:** If `true`, hides the input field.
- **Effect:** Adds the `hidden` attribute to the `<input>` element.

---

#### **`disabled`**
- **Type:** `bool`
- **Description:** If `true`, disables the input field.
- **Effect:** Adds the `disabled` attribute to the `<input>` element.

---

#### **`info`**
- **Type:** `bool|string`
- **Description:** Provides additional information or instructions for the input field.
- **Behavior:**
  - If a string is provided, it is displayed as a descriptive `<p>` tag under the input field.

---

#### **`width`**
- **Type:** `string`
- **Description:** Specifies the width of the `<td>` containing the input field.
- **Example:**
  - Input: `'50%'`
  - Result: `<td width="50%">`

---

## Input Field Construction

### **Generated HTML**
The function generates the following HTML structure:
```html
<tr class="input-item-name">
    <th>
        <span class="dashicons [icon-class]"></span>
        <label for="item_name">Item Name</label>
    </th>
    <td width="[width]">
        <input
            type="[type]"
            name="[field_name]"
            id="[field_id]"
            aria-describedby="[aria-description]"
            value="[value]"
            class="[classes]"
            [required|disabled]
        >
        <p class="description" id="[description-id]">[description-content]</p>
    </td>
    <td>
        [submit-button]
        <p class="description" style="visibility: hidden;">...</p>
    </td>
</tr>
```


## Accessibility

- **`aria-describedby`:** Associates the input field with a description paragraph for screen readers.
- **Descriptive Labels:** Ensures all inputs have an associated `<label>` for accessibility.


## Examples

### **Basic Input Field**
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
        <span class="dashicons"></span>
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

### **Input Field with Submit Button**
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
        <span class="dashicons"></span>
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


## Examples Fields in the `input` Method

Here are some additional fields using the `input` method with various input types and configurations:

### **Text Input with Default Value**
```php
$this->input('Full Name', 'John Doe', [
    'type' => 'text',
    'class' => 'full-name-input',
    'info' => 'Please enter your full name.'
]);
```

**Generated HTML:**
```html
<tr class="input-full-name">
    <th>
        <span class="dashicons"></span>
        <label for="full_name">Full Name</label>
    </th>
    <td width="">
        <input type="text" name="full_name" id="full_name" aria-describedby="full_name" value="John Doe" class="full-name-input">
        <p class="description" id="full_name">Please enter your full name.</p>
    </td>
    <td>
        <p class="description" style="visibility: hidden;">...</p>
    </td>
</tr>
```

---

### **Email Input with Required Field**
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
        <span class="dashicons"></span>
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

---

### **Password Input**
```php
$this->input('Password', '', [
    'type' => 'password',
    'class' => 'password-input',
    'info' => 'Create a strong password for security.',
]);
```

**Generated HTML:**
```html
<tr class="input-password">
    <th>
        <span class="dashicons"></span>
        <label for="password">Password</label>
    </th>
    <td width="">
        <input type="password" name="password" id="password" aria-describedby="password" value="" class="password-input">
        <p class="description" id="password">Create a strong password for security.</p>
    </td>
    <td>
        <p class="description" style="visibility: hidden;">...</p>
    </td>
</tr>
```

---

### **Hidden Input**
```php
$this->input('Token', '123456789', [
    'type' => 'hidden'
]);
```

**Generated HTML:**
```html
<tr class="input-token">
    <th>
        <span class="dashicons"></span>
        <label for="token">Token</label>
    </th>
    <td width="">
        <input type="hidden" name="token" id="token" aria-describedby="token" value="123456789">
        <p class="description" id="token" style="visibility: hidden;">...</p>
    </td>
    <td>
        <p class="description" style="visibility: hidden;">...</p>
    </td>
</tr>
```

---

### **Number Input with Width**
```php
$this->input('Quantity', '1', [
    'type' => 'number',
    'class' => 'quantity-input',
    'info' => 'Enter the quantity.',
    'width' => '50%'
]);
```

**Generated HTML:**
```html
<tr class="input-quantity">
    <th>
        <span class="dashicons"></span>
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

---

### **Search Input with Button**
```php
$this->input('Search Products', '', [
    'type' => 'search',
    'class' => 'search-input',
    'button' => 'Search',
    'info' => 'Search for products by name or SKU.',
]);
```

**Generated HTML:**
```html
<tr class="input-search-products">
    <th>
        <span class="dashicons"></span>
        <label for="search_products">Search Products</label>
    </th>
    <td width="">
        <input type="search" name="search_products" id="search_products" aria-describedby="search_products" value="" class="search-input">
        <p class="description" id="search_products">Search for products by name or SKU.</p>
    </td>
    <td>
        <button type="submit">Search</button>
        <p class="description" style="visibility: hidden;">...</p>
    </td>
</tr>
```

---

### **Disabled Input Field**
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
        <span class="dashicons"></span>
        <label for="read_only_value">Read Only Value</label>
    </th>
    <td width="">
        <input type="text" name="read_only_value" id="read_only_value" aria-describedby="read_only_value" value="42" class="" disabled>
        <p class="description" id="read_only_value">This field is not editable.</p>
    </td>
    <td>
        <p class="description" style="visibility: hidden;">...</p>
    </td>
</tr>
```

---

### **Custom Dashicon with Class**
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
