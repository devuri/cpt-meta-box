# Snippets

Below are several short **“recipes”** or **“snippets”** demonstrating common **cpt-meta** use cases, so you can quickly get started. 
Each snippet shows a minimal approach to accomplishing typical tasks like registering post types, adding meta boxes, defining fields, and retrieving data.


## 1. **Registering a Minimal Custom Post Type**

```php
use Urisoft\PostMeta\PostType;

/**
 * Registers a simple "Book" post type with default settings.
 */
function register_book_post_type() {
    $book = new PostType('book', 'Book', 'Books', [
        'menu_icon' => 'dashicons-book',
        'supports'  => ['title', 'editor'],
    ]);
    $book->register();
}
add_action('init', 'register_book_post_type');
```

**Key Points:**
- `PostType` constructor requires the slug (`book`), singular name (`Book`), plural name (`Books`), and an array of args.
- The `register()` method hooks into WordPress to finalize creation.

---

## 2. **Defining a Settings Class with Fields**

```php
use Urisoft\PostMeta\Settings;

class BookSettings extends Settings
{
    /**
     * Override the settings() method to define custom fields.
     */
    public function settings(): void
    {
        // Plain text input
        $this->input('Author', [
            'placeholder' => 'Enter the author name',
        ]);

        // Textarea
        $this->textarea('Synopsis', [
            'placeholder' => 'Short book summary',
        ]);

        // Dropdown
        $this->select('Genre', [
            'fiction'     => 'Fiction',
            'nonfiction'  => 'Non-Fiction',
            'mystery'     => 'Mystery',
            'scifi'       => 'Sci-Fi',
            'selected'    => $this->getMeta('genre'), 
        ]);

        // Number field
        $this->input('Page Count', [
            'type'     => 'number',
            'placeholder' => 'Number of pages',
        ]);
    }
}
```

**Key Points:**
- Extend `Settings` and implement `settings()`.
- Use built-in helper methods like `input`, `textarea`, and `select`.
- Call `$this->getMeta($key)` to retrieve existing values for each field.

---

## 3. **Attaching a Meta Box to a Custom Post Type**

```php
use Urisoft\PostMeta\MetaBox;

/**
 * Create and register a meta box for the "Book" post type.
 */
function add_book_meta_box() {
    // Instantiate our Settings class for "book"
    $bookSettings = new BookSettings('book');

    // Create a new MetaBox with a title and optional zebra styling
    (new MetaBox($bookSettings, [
        'name'  => 'Book Details',
        'zebra' => true,
    ]))->register();
}
add_action('init', 'add_book_meta_box');
```

**Key Points:**
- The second parameter to `MetaBox` (`['name' => 'Book Details']`) becomes the displayed meta box title.
- `zebra => true` enables alternate row shading in the form layout.

---

## 4. **Retrieving Metadata for a Given Post**

```php
use Urisoft\PostMeta\Data;

/**
 * Example usage in a theme template or plugin function.
 */
function display_book_info($post_id) {
    // Initialize a Data handler for "book" post type
    $bookData = Data::init('book');

    // Retrieve an array of meta fields
    $info = $bookData->meta($post_id);

    if (!empty($info)) {
        echo '<h2>' . esc_html($info['Author']) . '</h2>';
        echo '<p>' . esc_html($info['Synopsis']) . '</p>';
        echo '<p>Genre: ' . esc_html($info['genre']) . '</p>';
        echo '<p>Pages: ' . esc_html($info['page_count']) . '</p>';
    }
}
```

**Key Points:**
- `Data::init('book')` instantiates the data manager for the “book” post type.
- `meta($post_id)` returns an array of all meta data, including any custom fields defined in the `Settings` class.

---

## 5. **Adding Custom Admin Columns**

```php
use Urisoft\PostMeta\PostType;

function register_book_columns() {
    $bookType = new PostType('book', 'Book', 'Books');
    
    // Add new columns
    $bookType->addAdminColumns(
        function ($columns) {
            $columns['author'] = 'Author';
            $columns['pages']  = 'Page Count';
            return $columns;
        },
        function ($column, $post_id) {
            if ($column === 'author') {
                echo esc_html(get_post_meta($post_id, 'Author', true));
            }
            if ($column === 'pages') {
                echo esc_html(get_post_meta($post_id, 'page_count', true));
            }
        }
    );
    
    // Make 'pages' sortable
    $bookType->addSortableColumns([
        'pages' => 'page_count',
    ]);
}
add_action('init', 'register_book_columns');
```

**Key Points:**
- `addAdminColumns` allows you to modify the admin columns layout for a post type.
- The second callable outputs column data based on `$column` name.
- `addSortableColumns` ties a custom column to a meta key for sorting.

---

## 6. **Registering a Custom Taxonomy with the Post Type**

```php
use Urisoft\PostMeta\PostType;

function register_book_genre_taxonomy() {
    $book = new PostType('book', 'Book', 'Books');
    // Add a "genre" taxonomy
    $book->addTaxonomy('genre', 'Genre', 'Genres', [
        'hierarchical' => true,
        'rewrite'      => ['slug' => 'genre'],
    ]);
    $book->register();
}
add_action('init', 'register_book_genre_taxonomy');
```

**Key Points:**
- `addTaxonomy($slug, $singularName, $pluralName, array $args)` attaches a custom taxonomy to the post type.
- The `hierarchical` argument controls whether the taxonomy behaves like categories (true) or tags (false).

---

## 7. **Custom REST Endpoint for a Post Type**

```php
use Urisoft\PostMeta\PostType;

function book_rest_endpoint() {
    $bookType = new PostType('book', 'Book', 'Books');
    $bookType->addCustomRestEndpoint('/recommended', function ($request) {
        // Return some data from the request or a custom logic
        return [
            'recommendation' => 'Check out our bestsellers!',
        ];
    }, 'GET');
    $bookType->register();
}
add_action('init', 'book_rest_endpoint');
```

**Key Points:**
- `addCustomRestEndpoint($route, $callback, $methods)` registers a new REST route at `/{post_type}/v1/{route}` (e.g., `/book/v1/recommended`).
- Return arrays from the callback, which are automatically JSON-encoded.

---

## 8. **Simple Plugin-Style Integration**

Combine everything into a plugin file for easy distribution:

```php
/**
 * Plugin Name:       Book Manager
 * Description:       Manages custom post type "Book" with meta fields and a REST endpoint.
 */

use Urisoft\PostMeta\{PostType, MetaBox, Settings, Data};

class BookSettings extends Settings
{
    public function settings(): void
    {
        $this->input('Author');
        $this->textarea('Synopsis');
        $this->select('Genre', [
            'fiction'     => 'Fiction',
            'nonfiction'  => 'Non-Fiction',
            'selected'    => $this->getMeta('genre'),
        ]);
        $this->input('Pages', ['type' => 'number']);
    }
}

// Initialize post type & meta box on plugin activation or init
function book_manager_setup() {
    $bookPostType = new PostType('book', 'Book', 'Books', [
        'menu_icon' => 'dashicons-book',
        'supports'  => ['title', 'editor'],
    ]);
    $bookPostType->register();

    // Add a meta box
    $settings = new BookSettings('book');
    (new MetaBox($settings, ['name' => 'Book Details']))->register();
    
    // Add columns, taxonomy, or custom REST if needed...
}
add_action('init', 'book_manager_setup');
```

**Key Points:**
- Simple structure that ties in a `Settings` subclass, a `PostType`, and a `MetaBox`.
- Perfect for plugin-based development or local mu-plugins.

---

## Closing Thoughts

These **snippets** should help you quickly set up **custom post types**, define **fields** via **Settings**, attach **meta boxes**, and **retrieve** or **display** custom data. Each recipe shows the minimal code needed to accomplish typical tasks using the **cpt-meta** library. Adjust and expand these to fit your project’s requirements, whether you’re building a simple plugin or a large-scale custom solution. Happy coding!
