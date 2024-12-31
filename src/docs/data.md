The `Data` class provides a utility for managing and retrieving WordPress posts and associated data. It is designed for easy interaction with WordPress posts, post metadata, and common functionalities such as generating edit links.

## Features

- Retrieve posts of a specific type.
- Fetch post metadata.
- Generate custom edit links.
- Retrieve key-value pairs of post titles and IDs.
- Access the latest posts or posts matching specific criteria.

---

## Class Properties

### `$list`
- **Type:** `array`
- **Description:** Stores a list of posts for the specified post type.

### `$post_type`
- **Type:** `string`
- **Default:** `'post'`
- **Description:** Defines the type of posts to work with (e.g., `post`, `page`, or any custom post type).

---

## Constructor

### `__construct($post_type = null)`
- **Parameters:**
  - `$post_type` *(optional)*: String specifying the post type. Defaults to `'post'`.
- **Usage:** Initializes the class with a given post type and populates `$list` with the latest posts of that type.

---

## Methods

### `init($post_type): self`
- **Static Method**
- **Parameters:**
  - `$post_type`: The type of posts to initialize.
- **Returns:** A new instance of the `Data` class.
- **Usage:**  
  ```php
  $data = Data::init('custom_post_type');
  ```

### `edit(int $id, $class = ''): string`
- **Static Method**
- **Parameters:**
  - `$id`: The ID of the post.
  - `$class` *(optional)*: A CSS class for the edit link.
- **Returns:** An HTML string for the edit link or an empty string if the user lacks the necessary permissions.
- **Usage:**  
  ```php
  $edit_link = Data::edit(123, 'edit-link-class');
  ```

### `list(): array`
- **Returns:** An associative array of post IDs as keys and their titles as values.
- **Usage:**  
  ```php
  $post_list = $data->list();
  ```

### `getkey($key, $data)`
- **Static Method**
- **Parameters:**
  - `$key`: The key to retrieve.
  - `$data`: An array to search within.
- **Returns:** The value associated with the key, `null` if the key is not found, or `false` if `$data` is not an array.
- **Usage:**  
  ```php
  $value = Data::getkey('title', $post_array);
  ```

### `meta($ID, $name = null)`
- **Parameters:**
  - `$ID`: The post ID.
  - `$name` *(optional)*: The meta field name. Defaults to `{$post_type}_cpm`.
- **Returns:** An array containing the post meta, including:
  - `thumbnail`: The post's featured image ID.
  - `ID`: The post ID.
- **Usage:**  
  ```php
  $meta_data = $data->meta(123);
  ```

### `items(int $number_posts = 5, array $params = [ 'orderby' => 'date' ]): array`
- **Parameters:**
  - `$number_posts` *(optional)*: Number of posts to retrieve. Defaults to `5`.
  - `$params` *(optional)*: Additional query arguments for retrieving posts.
- **Returns:** An array of post objects or IDs.
- **Usage:**  
  ```php
  $posts = $data->items(10, ['order' => 'ASC']);
  ```

---

## Example Usage

```php
// Initialize the Data class for 'post' type.
$data = Data::init('post');

// Get a list of post titles and IDs.
$post_list = $data->list();

// Generate an edit link for a post.
$edit_link = Data::edit(123, 'edit-class');

// Fetch metadata for a specific post.
$meta = $data->meta(123);

// Retrieve the latest 10 posts.
$latest_posts = $data->items(10);
```

## Notes

- Ensure that the user has sufficient permissions when calling `edit()`.
- The class relies on WordPress core functions like `get_posts()` and `get_post_meta()`.
- Customizations can be made by passing additional parameters to methods like `items()`.

For more information, refer to the official WordPress documentation:
- [`get_posts`](https://developer.wordpress.org/reference/functions/get_posts/)
- [`get_post_meta`](https://developer.wordpress.org/reference/functions/get_post_meta/)
