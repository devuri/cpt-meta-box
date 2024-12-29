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
use Urisoft\PostMeta\Form\Form;
use Urisoft\PostMeta\Interfaces\SettingsInterface;
use Urisoft\PostMeta\Traits\MetaTrait;
use WP_Post;

abstract class Settings implements SettingsInterface
{
    use MetaTrait;

    /**
     * Post type associated with this setting.
     *
     * @var string
     */
    public $postType;

    /**
     * List of input fields for the settings.
     *
     * @var array
     */
    protected $fields = [];

    /**
     * Metadata for the post.
     *
     * @var null|array
     */
    protected $metaData = null;

    /**
     * Current post object.
     *
     * @var null|WP_Post
     */
    protected $postObject = null;

    /**
     * @var null|Form
     */
    protected $form;

    /**
     * Initialize the settings with a specific post type.
     *
     * @param string $postType Post type for the settings.
     *
     * @throws Exception If the post type is null or empty.
     */
    public function __construct(string $postType, ?array $context = null)
    {
        if (empty($postType)) {
            throw new Exception('Invalid post type provided: ' . $postType);
        }

        $this->postType = $postType;

        if ( ! \is_null($context)) {
            $this->form = self::form($context);
        } else {
            $this->form = self::form(
                [
                    'fields' => $this->fields,
                    'post_type' => $this->postType,
                ]
            );
        }
    }

    public function getPostType(): string
    {
        return $this->postType;
    }

    public static function form(?array $context = []): Form
    {
        return new Form($context);
    }

    /**
     * Set up the settings and metadata for a specific post.
     *
     * @param WP_Post $postObject Current post object.
     * @param string  $metaField  Meta field name to retrieve data.
     *
     * @return SettingsInterface
     */
    public function create(WP_Post $postObject, string $metaField): SettingsInterface
    {
        $this->postObject = $postObject;
        $this->metaData = get_post_meta($postObject->ID, $metaField, true) ?: [];

        return $this;
    }

    /**
     * Define the settings for the metabox (to be implemented in subclasses).
     */
    abstract public function settings(): void;

    /**
     * Process and sanitize POST data for settings.
     *
     * @param array $postData POST data, expected to be sanitized.
     *
     * @return array Sanitized data limited to defined fields.
     */
    public function data(array $postData): array
    {
        if ( ! empty($this->fields)) {
            return array_filter(
                array_map('sanitize_text_field', $postData),
                function ($key) {
                    return \in_array($key, $this->fields, true);
                },
                ARRAY_FILTER_USE_KEY
            );
        }

        return [];
    }
}
