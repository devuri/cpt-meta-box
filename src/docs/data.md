# Data Class

The **`Data`** class offers a concise way to work with WordPress posts of a particular post type. It can retrieve lists of posts, generate edit links, and fetch post meta information, all scoped by a specified post type.

## Namespace

```php
namespace Urisoft\PostMeta;
```

## Class Overview

- **Retrieve** posts from a specified post type in WordPress.
- **Return** an array of post objects or key-value pairs (ID => post title).
- **Generate** edit links for posts (if the user has the capability to edit).
- **Fetch** meta data for a given post, including the featured image ID.

## Properties

| Property      | Type     | Description                                                     |
||-|--|
| `$list`       | `array`  | An array of post objects matching the post type.               |
| `$post_type`  | `string` | The current post type for this data handler (default `'post'`).|



## Constructor

```php
public function __construct($post_type = null)
```

**Description:**  
Initializes the `Data` class, setting the `$post_type` and immediately populating `$this->list` using `items()`.

**Parameters:**  
- **`$post_type`** (`?string`):  
  The slug of the post type to work with. Defaults to `'post'`.

**Usage Example:**  
```php
$data = new Data('product');
```
Automatically loads the most recent 5 `product` posts into `$data->list`.



## Public Methods

### 1. `init($post_type): self`

```php
public static function init($post_type): self
```

**Description:**  
A static initializer for the class, returning a new instance.

**Parameters:**  
- **`$post_type`** (`string`): The post type to manage.

**Returns:**  
- **`Data`**: A fresh `Data` instance with the specified post type.

**Example:**
```php
$dataInstance = Data::init('book');
// $dataInstance is now a Data object for 'book' posts.
```



### 2. `edit(int $id, $class = ''): string`

```php
public static function edit(int $id, $class = ''): string
```

**Description:**  
Generates an “Edit” link for a given post if the current user has the capability to edit posts. Returns an empty string otherwise.

**Parameters:**  
- **`$id`** (`int`): The ID of the post to edit.  
- **`$class`** (`string`, optional): Additional CSS classes for the `<a>` element.

**Returns:**  
- **`string`**: An `<a>` tag linking to the edit screen, or an empty string if the user cannot edit.

**Example:**
```php
echo Data::edit(123, 'edit-link-class');
// Outputs: <a class="edit-link-class" href="...wp-admin/post.php?post=123&action=edit">Edit</a>
```



### 3. `list(): array`

```php
public function list(): array
```

**Description:**  
Returns a key-value array of post IDs and post titles for the current `$post_type`. Essentially a convenience wrapper around the results from `items()`, but organized in `[ID => post_title]` format.

**Returns:**  
- **`array`**: An associative array where keys are post IDs and values are post titles.

**Example:**
```php
$list = $data->list();
// $list might look like:
// [
//   10 => 'My First Product',
//   11 => 'Another Product',
//   12 => 'Featured Product'
// ]
```



### 4. `getkey($key, $data)`

```php
public static function getkey($key, $data)
```

**Description:**  
Safely retrieves a value from an array by key, returning `null` if the key does not exist, or `false` if `$data` is not an array.

**Parameters:**  
- **`$key`** (`mixed`): The key to retrieve from `$data`.  
- **`$data`** (`mixed`): An array or another data type.

**Returns:**  
- **`mixed`**:
  - The value at `$data[$key]` if it exists.
  - `null` if `$key` doesn’t exist in `$data`.
  - `false` if `$data` is not an array.

**Example:**
```php
$info = ['price' => 19.99, 'stock' => 10];
echo Data::getkey('price', $info); // Outputs 19.99
echo Data::getkey('invalid', $info); // Returns null
echo Data::getkey('someKey', 'string'); // Returns false
```



### 5. `meta($ID, $name = null)`

```php
public function meta($ID, $name = null)
```

**Description:**  
Retrieves meta data for a given post ID. Automatically populates:
- The featured image ID under `['thumbnail']`.
- The post ID under `['ID']`.

If `$name` is `null`, defaults to a meta key constructed from `{$this->post_type}_cpm`.

**Parameters:**  
- **`$ID`** (`int`): The ID of the post.  
- **`$name`** (`?string`): The meta key to retrieve. Defaults to `"{post_type}_cpm"` if not provided.

**Returns:**  
- **`array`**: Meta data array, including `['thumbnail']` and `['ID']` keys.

**Example:**
```php
$postMeta = $data->meta(20);
// e.g. returns something like:
// [
//   'thumbnail' => 123,   // ID of the featured image
//   'ID'        => 20,    // The current post ID
//   ... any other meta fields ...
// ]
```



### 6. `items(int $number_posts = 5, array $params = [ 'orderby' => 'date' ]): array`

```php
public function items(
    int $number_posts = 5,
    array $params = ['orderby' => 'date']
): array
```

**Description:**  
Retrieves an array of post objects for the configured `$post_type`, merging in optional query parameters. By default, fetches 5 posts in descending date order.

**Parameters:**  
- **`$number_posts`** (`int`, optional): How many posts to fetch. Use `-1` for all. Defaults to `5`.  
- **`$params`** (`array`, optional): Additional arguments to pass to `get_posts()`. Merged with defaults shown below.

**Default Arguments:**
```php
[
  'numberposts'      => $number_posts,
  'category'         => 0,
  'orderby'          => 'date',
  'order'            => 'DESC',
  'include'          => [],
  'exclude'          => [],
  'meta_key'         => '',
  'meta_value'       => '',
  'post_type'        => $this->post_type,
  'suppress_filters' => true,
]
```

**Returns:**  
- **`array`**: An array of WP_Post objects.

**Example:**
```php
$recentPosts = $data->items(10, ['orderby' => 'title', 'order' => 'ASC']);
// Returns up to 10 post objects, sorted alphabetically by title.
```



## Usage Example

Below is a quick example showcasing how you might use this class in your plugin or theme code:

```php
use Urisoft\PostMeta\Data;

// Initialize the Data class for a custom post type "product"
$productData = Data::init('product');

// Retrieve an array of products [id => title]
$products = $productData->list();

// Print an edit link for the first product, if any
foreach ($products as $id => $title) {
    echo "<li>{$title} " . Data::edit($id, 'edit-link') . "</li>";
}

// Get meta data for a specific product
$productMeta = $productData->meta(42);
// e.g. could return ['thumbnail' => 100, 'ID' => 42, ... other fields ...]
```



## Notes

1. **Default Post Type**  
   If no `$post_type` is passed to the constructor, the class defaults to `'post'`.

2. **Capability Check**  
   The `edit()` method relies on `current_user_can('edit_posts')`. Ensure your user’s role has the correct capabilities if you expect the edit link to appear.

3. **Meta Key**  
   By default, `meta()` retrieves data from `"{post_type}_cpm"`. You can override this by passing a `$name` argument.

4. **Filtering**  
   The class uses `get_posts()` which supports most WP_Query arguments. You can pass additional parameters like `category_name`, `meta_query`, etc. via the `$params` array in `items()`.

5. **Return Types**  
   - `items()` returns an array of `WP_Post` objects.  
   - `list()` returns an associative array of `ID => post_title`.  
   - `meta()` returns an associative array of meta fields plus `thumbnail` and `ID`.
