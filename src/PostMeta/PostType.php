<?php

/*
 * This file is part of the cptMeta package.
 *
 * (c) Uriel Wilson
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Urisoft\PostMeta;

use Exception;

class PostType
{
    protected $postType;
    protected $args = [];
    protected $labels = [];
    protected $taxonomies = [];
    protected $singularName;
    protected $pluralName;

    public function __construct(string $postType, ?string $singularName = null, ?string $pluralName = null, array $args = [])
    {
        $this->postType = $postType;
        $this->labels = $this->setLabel($singularName, $pluralName);
        $this->args = array_merge($this->defaultArgs(), $args, ['labels' => $this->labels]);
    }

    public function exists(): bool
    {
        return post_type_exists($this->postType);
    }

    public function register(): void
    {
        if (post_type_exists($this->postType)) {
            return;
        }

        add_action('init', [$this, 'registerPostType']);
        add_action('init', [$this, 'registerTaxonomies']);
    }

    public function registerPostType(): void
    {
        register_post_type($this->postType, $this->args);
    }

    public function addTaxonomy(string $taxonomy, string $singularName, string $pluralName, array $args = []): void
    {
        $this->taxonomies[$taxonomy] = [
            'singularName' => $singularName,
            'pluralName' => $pluralName,
            'args' => $args,
        ];
    }

    public function updateTaxonomy(string $taxonomy, array $args): void
    {
        if (isset($this->taxonomies[$taxonomy])) {
            $this->taxonomies[$taxonomy]['args'] = array_merge(
                $this->taxonomies[$taxonomy]['args'],
                $args
            );
        }
    }

    public function registerTaxonomies(): void
    {
        foreach ($this->taxonomies as $taxonomy => $details) {
            $labels = $this->generateLabels($details['singularName'], $details['pluralName']);
            $args = array_merge(
                [
                    'labels' => $labels,
                    'hierarchical' => true,
                    'show_in_rest' => true,
                ],
                $details['args']
            );
            register_taxonomy($taxonomy, $this->postType, $args);
        }
    }

    public function setCapabilities(array $capabilities): void
    {
        $this->args['capabilities'] = $capabilities;
    }

    public function setRewriteRules(array $rewrite): void
    {
        $this->args['rewrite'] = $rewrite;
    }

    public function addAdminColumns(callable $columnsCallback, callable $contentCallback): void
    {
        add_filter("manage_{$this->postType}_posts_columns", $columnsCallback);
        add_action("manage_{$this->postType}_posts_custom_column", $contentCallback, 10, 2);
    }

    public function addSortableColumns(array $sortableColumns): void
    {
        add_filter("manage_edit-{$this->postType}_sortable_columns", function ($columns) use ($sortableColumns) {
            return array_merge($columns, $sortableColumns);
        });
    }

    public function addCustomRestEndpoint(string $route, callable $callback, string $methods = 'GET'): void
    {
        add_action('rest_api_init', function () use ($route, $callback, $methods): void {
            register_rest_route("{$this->postType}/v1", $route, [
                'methods' => $methods,
                'callback' => $callback,
            ]);
        });
    }

    public function addDefaultMetaFields(array $fields): void
    {
        foreach ($fields as $key => $default) {
            add_action('save_post_' . $this->postType, function ($post_id) use ($key, $default): void {
                if ( ! metadata_exists('post', $post_id, $key)) {
                    update_post_meta($post_id, $key, $default);
                }
            });
        }
    }

    public static function bulkRegister(array $postTypes): void
    {
        foreach ($postTypes as $postType => $config) {
            new self($postType, $config['singular'], $config['plural'], $config['args'] ?? []);
        }
    }

    public function flushRewriteRulesOnActivation(): void
    {
        add_action('init', function (): void {
            flush_rewrite_rules();
        });
    }

    protected function setLabel(?string $singularName, ?string $pluralName): array
    {
        $this->singularName = ! empty($singularName) ? $singularName : ucfirst($singularName);
        $this->pluralName = ! empty($pluralName) ? $pluralName : ucfirst(self::pluralize($this->postType));

        return $this->generateLabels($this->singularName, $this->pluralName);
    }

    protected function validateRegistration(bool $withException = false)
    {
        if ( ! $withException) {
            return post_type_exists($this->postType);
        }

        if (post_type_exists($this->postType)) {
            throw new Exception("Post type '{$this->postType}' already exists.");
        }
    }

    protected function generateLabels(string $singularName, string $pluralName): array
    {
        return [
            'name' => $pluralName,
            'singular_name' => $singularName,
            'menu_name' => $pluralName,
            'name_admin_bar' => $singularName,
            'add_new' => "Add New $singularName",
            'add_new_item' => "Add New $singularName",
            'edit_item' => "Edit $singularName",
            'new_item' => "New $singularName",
            'view_item' => "View $singularName",
            'view_items' => "View $pluralName",
            'search_items' => "Search $pluralName",
            'not_found' => "No $pluralName found",
            'not_found_in_trash' => "No $pluralName found in Trash",
            'parent_item_colon' => "Parent $singularName:",
            'all_items' => "All $pluralName",
            'archives' => "$singularName Archives",
            'attributes' => "$singularName Attributes",
            'insert_into_item' => "Insert into $singularName",
            'uploaded_to_this_item' => "Uploaded to this $singularName",
            'featured_image' => "$singularName Featured Image",
            'set_featured_image' => "Set featured image",
            'remove_featured_image' => "Remove featured image",
            'use_featured_image' => "Use as featured image",
            'filter_items_list' => "Filter $pluralName list",
            'items_list_navigation' => "$pluralName list navigation",
            'items_list' => "$pluralName list",
            'item_published' => "$singularName published.",
            'item_published_privately' => "$singularName published privately.",
            'item_reverted_to_draft' => "$singularName reverted to draft.",
            'item_scheduled' => "$singularName scheduled.",
            'item_updated' => "$singularName updated.",
        ];
    }

    protected function defaultArgs(): array
    {
        return [
            'public' => true,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_nav_menus' => true,
            'show_in_menu' => true,
            'show_in_admin_bar' => true,
            'menu_position' => null,
            'menu_icon' => null,
            'capability_type' => 'post',
            'hierarchical' => false,
            // 'supports' => ['title', 'editor', 'thumbnail'],
            'supports' => ['title', 'thumbnail'],
            'has_archive' => true,
            'rewrite' => ['slug' => $this->postType],
            'query_var' => true,
            'can_export' => true,
            'delete_with_user' => false,
            'show_in_rest' => true,
            'rest_base' => $this->postType,
            'rest_controller_class' => 'WP_REST_Posts_Controller',
        ];
    }

    /**
     * Pluralizes an English word.
     *
     * @param string $word The word to pluralize.
     *
     * @return string The pluralized word.
     */
    protected static function pluralize(string $word): string
    {
        $irregulars = [
            'child' => 'children',
            'man' => 'men',
            'woman' => 'women',
            'mouse' => 'mice',
            'person' => 'people',
            'tooth' => 'teeth',
            'foot' => 'feet',
        ];

        if (isset($irregulars[$word])) {
            return $irregulars[$word];
        }

        // Words ending in -y, but not preceded by a vowel
        if (preg_match('/[^aeiou]y$/i', $word)) {
            return preg_replace('/y$/', 'ies', $word);
        }

        // Words ending in -s, -x, -z, -ch, -sh
        if (preg_match('/(s|x|z|ch|sh)$/i', $word)) {
            return $word . 'es';
        }

        // Words ending in -f or -fe
        if (preg_match('/(?:f|fe)$/i', $word)) {
            return preg_replace('/(?:f|fe)$/', 'ves', $word);
        }

        return $word . 's';
    }
}
