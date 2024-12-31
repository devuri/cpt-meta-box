# PostType Class

The `PostType` class in the **cptMeta** package provides a streamlined interface for registering and managing custom post types in WordPress. It also includes features for adding taxonomies, capabilities, rewrite rules, and custom REST endpoints. This class makes it easy to encapsulate the logic required to handle custom post types and their extended functionality.

---

## Namespace

```php
namespace Urisoft\PostMeta;
```

---

## Class Overview

- **Register** custom post types with WordPress.
- **Manage** labels, taxonomies, capabilities, and rewrite rules.
- **Extend** functionality via custom endpoints, admin columns, and default meta fields.
- **Supports** both single registrations and a static bulk registration of multiple post types.

---

## Properties

| Property         | Type      | Description                                                                                                                                     |
|------------------|-----------|-------------------------------------------------------------------------------------------------------------------------------------------------|
| `$postType`      | `string`  | The slug of the custom post type (e.g., `book`, `event`, `product`).                                                                           |
| `$args`          | `array`   | An associative array of arguments passed to `register_post_type()`, merged with default arguments.                                             |
| `$labels`        | `array`   | An array of labels to display in the WordPress admin (e.g., *Add New*, *Edit*, *Search*).                                                       |
| `$taxonomies`    | `array`   | Holds the taxonomy definitions (slug, singular name, plural name, and arguments).                                                               |
| `$singularName`  | `string`  | The human-readable singular name of the post type (e.g., *Book*).                                                                              |
| `$pluralName`    | `string`  | The human-readable plural name of the post type (e.g., *Books*).                                                                               |

---

## Constructor

```php
public function __construct(
    string $postType,
    ?string $singularName = null,
    ?string $pluralName   = null,
    array $args           = []
)
```

**Description**  
Initializes a new `PostType` instance. Defines the post type slug, generates labels (if none provided, it attempts to auto-pluralize the slug), and merges any supplied arguments with the class defaults.

**Parameters**  
- **`$postType`** (`string`):  
  The slug for the custom post type (e.g., `'movie'`, `'product'`).

- **`$singularName`** (`?string`, optional):  
  A human-readable name for a single instance of the post type (e.g., `'Movie'`). If `null`, this may default to a capitalized version of `$postType`.

- **`$pluralName`** (`?string`, optional):  
  A human-readable plural name for the post type (e.g., `'Movies'`). If `null`, the class will attempt to automatically pluralize `$postType`.

- **`$args`** (`array`, optional):  
  An associative array of arguments used to customize the behavior and display of the post type.

---

## Public Methods

### 1. `exists(): bool`

```php
public function exists(): bool
```
**Description**  
Checks if the post type is already registered in WordPress. Useful when you need to confirm registration status before re-registering or making modifications.

**Returns**  
- (`bool`): `true` if the post type is registered, otherwise `false`.

---

### 2. `register(): void`

```php
public function register(): void
```
**Description**  
Sets up registration hooks for the post type and its taxonomies. If the post type already exists, the method exits early. Otherwise, it hooks `registerPostType()` and `registerTaxonomies()` into WordPress’s `init` action.

**Usage Example**  
```php
$postType = new PostType('book', 'Book', 'Books');
$postType->register();
```

---

### 3. `registerPostType(): void`

```php
public function registerPostType(): void
```
**Description**  
Invoked by WordPress’s `init` action to call `register_post_type($this->postType, $this->args)`. You typically won’t call this method directly; it’s triggered by `register()`.

---

### 4. `addTaxonomy(string $taxonomy, string $singularName, string $pluralName, array $args = []): void`

```php
public function addTaxonomy(
    string $taxonomy,
    string $singularName,
    string $pluralName,
    array $args = []
): void
```

**Description**  
Adds a taxonomy definition to the internal `$taxonomies` array, which is later registered when `registerTaxonomies()` is called.  

**Parameters**  
- **`$taxonomy`** (`string`):  
  The slug for the taxonomy (e.g., `genre`, `topic`).
- **`$singularName`** (`string`):  
  A human-readable singular label (e.g., *Genre*).
- **`$pluralName`** (`string`):  
  A human-readable plural label (e.g., *Genres*).
- **`$args`** (`array`):  
  Additional arguments to customize the taxonomy (e.g., `hierarchical`, `rewrite`, etc.).

---

### 5. `updateTaxonomy(string $taxonomy, array $args): void`

```php
public function updateTaxonomy(string $taxonomy, array $args): void
```
**Description**  
Updates the arguments for a previously added taxonomy, merging the new arguments with the existing ones.

**Parameters**  
- **`$taxonomy`** (`string`): The taxonomy slug.
- **`$args`** (`array`): Arguments to merge into the existing taxonomy configuration.

---

### 6. `registerTaxonomies(): void`

```php
public function registerTaxonomies(): void
```
**Description**  
Called by the `register()` method (via `init`) to register all the taxonomies stored in `$this->taxonomies`. For each taxonomy, it generates labels, merges arguments, and calls `register_taxonomy()`.

---

### 7. `setCapabilities(array $capabilities): void`

```php
public function setCapabilities(array $capabilities): void
```
**Description**  
Allows you to explicitly define the capabilities associated with this post type. These are merged into `$this->args` under the `capabilities` key.

**Parameters**  
- **`$capabilities`** (`array`): An associative array of capability mappings (e.g., `['edit_posts' => 'edit_books']`).

---

### 8. `setRewriteRules(array $rewrite): void`

```php
public function setRewriteRules(array $rewrite): void
```
**Description**  
Defines or overrides rewrite rules for this post type, added to `$this->args['rewrite']`. Useful for customizing the URL slug structure.

**Parameters**  
- **`$rewrite`** (`array`): Rewrite configuration (e.g., `['slug' => 'books']`).

---

### 9. `addAdminColumns(callable $columnsCallback, callable $contentCallback): void`

```php
public function addAdminColumns(
    callable $columnsCallback,
    callable $contentCallback
): void
```
**Description**  
Registers callbacks for customizing the admin list table columns for this post type.  
- `manage_{$this->postType}_posts_columns` filter modifies the columns themselves.  
- `manage_{$this->postType}_posts_custom_column` action defines how content is displayed in each column.

**Parameters**  
- **`$columnsCallback`** (`callable`): Callback to define/rename columns.  
- **`$contentCallback`** (`callable`): Callback to output content within each column.

---

### 10. `addSortableColumns(array $sortableColumns): void`

```php
public function addSortableColumns(array $sortableColumns): void
```
**Description**  
Marks specific admin columns as sortable. Merges user-defined columns into the existing columns via the `manage_edit-{$this->postType}_sortable_columns` filter.

**Parameters**  
- **`$sortableColumns`** (`array`): An associative array mapping column keys to the meta_key or other identifier used for sorting.

---

### 11. `addCustomRestEndpoint(string $route, callable $callback, string $methods = 'GET'): void`

```php
public function addCustomRestEndpoint(
    string $route,
    callable $callback,
    string $methods = 'GET'
): void
```
**Description**  
Registers a custom REST API route under the namespace `{$this->postType}/v1`. This allows you to define additional REST endpoints specifically for the custom post type.

**Parameters**  
- **`$route`** (`string`): The REST route path (e.g., `'/featured'`).  
- **`$callback`** (`callable`): Function to handle the REST request.  
- **`$methods`** (`string`): Allowed HTTP methods (default is `'GET'`).

---

### 12. `addDefaultMetaFields(array $fields): void`

```php
public function addDefaultMetaFields(array $fields): void
```
**Description**  
Automatically populates meta fields with default values upon the initial save of a post, if those meta fields do not exist yet.

**Parameters**  
- **`$fields`** (`array`): An associative array of meta keys and default values, e.g.:
  ```php
  [
      '_featured' => false,
      '_rating'   => 0,
  ]
  ```
- **Implementation**  
  Hooks into `save_post_{$this->postType}` to add or update meta for each field if it’s missing.

---

### 13. `bulkRegister(array $postTypes): void`

```php
public static function bulkRegister(array $postTypes): void
```
**Description**  
A static helper method to instantiate and register multiple post types at once. Useful if you have a large configuration array of multiple post types.

**Parameters**  
- **`$postTypes`** (`array`): An associative array where the key is the post type slug and the value is an array of config details:
  ```php
  [
      'movie' => [
          'singular' => 'Movie',
          'plural'   => 'Movies',
          'args'     => [/* ... */],
      ],
      'book' => [
          'singular' => 'Book',
          'plural'   => 'Books',
          'args'     => [/* ... */],
      ],
      // ...
  ]
  ```

---

### 14. `flushRewriteRulesOnActivation(): void`

```php
public function flushRewriteRulesOnActivation(): void
```
**Description**  
Hooks into `init` to call `flush_rewrite_rules()` on activation. This ensures that any newly registered post type or rewrite rule is reflected without requiring a manual “Permalink Settings” save in the admin.

**Usage**  
Typically called in a plugin’s activation hook or initialization process.

---

## Protected Methods

### 1. `setLabel(?string $singularName, ?string $pluralName): array`

Generates and returns an array of default labels for the post type. If `$singularName` or `$pluralName` is not supplied, attempts to capitalize or pluralize the `$postType` slug. Stores the final labels in `$this->labels`.

---

### 2. `validateRegistration(bool $withException = false)`

Checks if the post type is already registered. If `$withException` is `true`, throws an `Exception` if the post type already exists.

---

### 3. `generateLabels(string $singularName, string $pluralName): array`

Creates the label array following WordPress conventions (e.g., `'Add New Item'`, `'View Items'`, `'Not Found'`). Returns the label array.

---

### 4. `defaultArgs(): array`

Returns an array of default arguments for `register_post_type()`. Includes configuration for `public`, `supports`, `has_archive`, `rewrite`, `show_in_rest`, etc. Merged with `$args` in the constructor.

---

### 5. `pluralize(string $word): string`

**Static** helper method for basic English pluralization:
- Handles irregular nouns (e.g., `mouse -> mice`).
- Special cases for words ending in `y`, `s/x/z/ch/sh`, `f/fe`.
- Defaults to simply appending `'s'`.

---

## Example Usage

```php
use Urisoft\PostMeta\PostType;

/**
 * Example: Register a "Book" post type with custom taxonomy "Genre".
 */
function register_book_post_type() {
    // Create a post type instance for "Book".
    $bookType = new PostType('book', 'Book', 'Books', [
        'menu_icon' => 'dashicons-book',
        'supports'  => ['title', 'editor', 'thumbnail'],
    ]);

    // Add a custom taxonomy "genre".
    $bookType->addTaxonomy('genre', 'Genre', 'Genres', [
        'hierarchical' => true,
        'rewrite'      => ['slug' => 'genre'],
    ]);

    // Register the post type and its taxonomies.
    $bookType->register();

    // Optionally set default meta fields.
    $bookType->addDefaultMetaFields([
        '_featured' => false,
        '_rating'   => 0,
    ]);
}
add_action('init', 'register_book_post_type');
```

---

## Notes

1. **Check Existence**  
   If you call `exists()` and get `true`, it means the post type is already registered somewhere else.

2. **Taxonomies**  
   For more complex taxonomy arguments (e.g., hierarchical vs. non-hierarchical, custom capabilities, etc.), supply them via the `$args` parameter in `addTaxonomy()` or `updateTaxonomy()`.

3. **Bulk Registration**  
   Use `PostType::bulkRegister()` to quickly instantiate and set up multiple custom post types in one go.

4. **Rewrite Rules**  
   If you change rewrite rules after registration, remember to flush rewrite rules (either manually by visiting Permalink Settings or using `flushRewriteRulesOnActivation()`).

5. **Callbacks & Hooks**  
   Admin columns, custom REST endpoints, and other features hook into WordPress actions and filters. Make sure these hooks run at the appropriate time (usually after `init`).

6. **Error Handling**  
   In rare cases (e.g., naming collisions), the class may throw exceptions (for example, in `validateRegistration(true)`). Be prepared to catch errors if you enforce these checks.

---

## Related Documentation

- [register_post_type()](https://developer.wordpress.org/reference/functions/register_post_type/)
- [register_taxonomy()](https://developer.wordpress.org/reference/functions/register_taxonomy/)
- [WordPress Capabilities](https://wordpress.org/support/article/roles-and-capabilities/)
- [WP REST API](https://developer.wordpress.org/rest-api/)
