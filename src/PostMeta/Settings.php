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
            $this->form = self::setForm($context);
        } else {
            $this->form = self::setForm(
                [
                    'fields' => $this->fields,
                    'post_type' => $this->postType,
                ]
            );
        }
    }

    public function init(): SettingsInterface
    {
        $this->settings();

        return $this;
    }

    public function getPostType(): string
    {
        return $this->postType;
    }

    public function getForm(): Form
    {
        return $this->form;
    }

    /**
     * Set up the settings and metadata for a specific post.
     *
     * @param null|WP_Post $postObject Current post object or null.
     * @param string       $metaField  Meta field name to retrieve data.
     *
     * @return SettingsInterface
     */
    public function create(?WP_Post $postObject, string $metaField): SettingsInterface
    {
        $this->postObject = $postObject;
        $this->metaData = get_post_meta($postObject ? $postObject->ID : 0, $metaField, true) ?: [];
        $fields = $this->form->getFields();

        // Process each field.
        foreach ($fields as $field) {
            if ( ! $field['output']) {
                continue;
            }
            $fieldName = $field['name'];
            $value = $this->metaData[$fieldName] ?? null;
            if ('thumbnail' === $field['field']) {
                $id    = get_post_meta($this->postObject->ID, '_thumbnail_id', true);
                $value = wp_get_attachment_url($id);
            }

            if ('select' === $field['field']) {

                if (empty($value)) {
                    $value = 'Select an option';
                } else {
                    $value = ucfirst(str_replace("-", ' ', $value));
                }
            }

            // Replace placeholder in the output.
            $output = str_replace("{{value}}", $value, $field['output']);
            static::output($output);
        }

        return $this;
    }

    /**
     * Define the settings for the metabox (to be implemented in subclasses).
     */
    public function settings(): void
    {
        // $this->input('Vehicle Name', [
        //     'placeholder' => 'Enter the vehicle name',
        // ]);
    }

    public function withContext(MetaBox $metaBox): Form
    {
        return $this->form->setContext(
            [
                'fields' => $this->form->getFields(),
                'post_type' => $this->postType,
                'meta' => $metaBox,
            ]
        );
    }

    /**
     * Process and sanitize POST data for settings.
     *
     * @return array Sanitized data limited to defined fields.
     */
    public function data(): array
    {
        if ( ! empty($this->fields)) {
            return array_filter(
                array_map('sanitize_text_field', $_POST),
                function ($key) {
                    return \in_array($key, $this->fields, true);
                },
                ARRAY_FILTER_USE_KEY
            );
        }

        return [];
    }

    public function getMeta(?string $key = null)
    {
        if (\is_null($key)) {
            return $this->metaData;
        }
        if (isset($this->metaData[$key])) {
            return $this->metaData[$key];
        }

        return '';
    }

    /**
     * Outputs the given field item.
     *
     * This method takes a string input and echoes it. Useful for displaying
     * content directly.
     *
     * @param string $fieldOutput The string to be outputted.
     *
     * @return void This method does not return a value.
     */
    protected static function output(string $fieldOutput): void
    {
        echo $fieldOutput;
    }

    protected function textarea(string $fieldtitle, array $params = [])
    {
        $fieldId = $this->form->sanitize($fieldtitle);
        $fieldMeta = $this->getMeta($fieldId);

        return $this->form->textarea($fieldtitle, $fieldMeta, $params);
    }

    protected function editor(string $fieldtitle)
    {
        $fieldId = $this->form->sanitize($fieldtitle);
        $fieldMeta = $this->getMeta($fieldId);

        return $this->form->editor($fieldtitle, $fieldMeta);
    }

    protected function input(string $fieldtitle, array $params = [])
    {
        $fieldId = $this->form->sanitize($fieldtitle);
        $fieldMeta = $this->getMeta($fieldId);

        return $this->form->input($fieldtitle, $fieldMeta, $params);
    }

    protected function select(string $fieldtitle, array $opts = [], array $params = [])
    {
        $fieldId = $this->form->sanitize($fieldtitle);
        $options = array_merge(
            ['selected' => $this->getMeta($fieldId)],
            $opts,
        );

        return $this->form->select($fieldtitle, $options, $params);
    }

    private static function setForm(?array $context = []): Form
    {
        return new Form($context);
    }
}
