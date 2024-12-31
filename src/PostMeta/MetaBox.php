<?php

namespace Urisoft\PostMeta;

use Exception;
use ReflectionClass;
use Urisoft\PostMeta\Form\Form;
use Urisoft\PostMeta\Interfaces\SettingsInterface;
use WP_Post;
use WP_Post_Type;

class MetaBox
{
    protected ?string $postType;
    protected ?SettingsInterface $settings;
    protected ?array $metaData = [];
    protected ?string $metabox;
    protected ?string $metaId;
    protected ?array $args;
    protected ?array $fields = [];
    protected ?string $metaLabel;
    protected ?string $metaField;
    protected ?string $groupKey;
    protected ?array $metaContext;
    protected ?Form $formContext;

    /**
     * Constructor to initialize MetaBox.
     *
     * @param SettingsInterface $settings Settings instance.
     * @param null|array        $args     Additional arguments.
     */
    public function __construct(SettingsInterface $settings, ?array $args = [])
    {
        $this->args = $this->setArgs($args);
        $this->settings = $settings->init();
        $this->postType = sanitize_title($settings->getPostType());

        // Define meta name and attributes.
        $this->metabox = $this->setName($this->args);
        $this->groupKey = '_' . hash('fnv1a32', $this->metabox);
        $this->metaId = 'cpm-group-' . $this->metabox . $this->groupKey;
        // $this->metaField = $this->metabox . '_cpm';
        $this->metaField = $this->metabox . $this->groupKey;
        $this->metaLabel = ucfirst(str_replace('-', ' ', $this->metabox));

        $this->metaContext = [
            'args' => $this->args,
            'settings' => $this->settings,
            'postType' => $this->postType,
            'metabox' => $this->metabox,
            'groupKey' => $this->groupKey,
            'metaId' => $this->metaId,
            'metaField' => $this->metaField,
            'metaLabel' => $this->metaLabel,
        ];

        // set form context
        $this->formContext = $this->settings->getForm();
    }

    public function register(?PostType $postType = null, bool $withSavePost = true): self
    {
        if ($postType) {
            $postType->register();
        }

        add_action('add_meta_boxes', [$this, 'createMetaBox']);

        if ($withSavePost) {
            add_action('save_post_' . $this->postType, [$this, 'saveMeta']);
        }

        return $this;
    }

    public function context(): ?array
    {
        return $this->metaContext;
    }

    /**
     * Builds the metabox settings.
     *
     * @param null|WP_Post $postObject Current post object.
     *
     * @return SettingsInterface
     */
    public function build(?WP_Post $postObject = null): SettingsInterface
    {
        return $this->settings->create($postObject, $this->metaField);
    }

    /**
     * Retrieves the post type data object.
     *
     * @return null|WP_Post_Type Post type object or null.
     */
    public function postTypeData(): ?WP_Post_Type
    {
        return get_post_type_object($this->postType);
    }

    /**
     * Registers the meta box.
     */
    public function createMetaBox(): void
    {
        add_meta_box(
            $this->metaId,
            $this->metaLabel,
            [$this, 'render'],
            $this->postType
        );
    }

    /**
     * Renders the meta box content.
     *
     * @param WP_Post $post Current post object.
     */
    public function render(WP_Post $post): void
    {
        $this->addTableStyle($this->args['zebra']); ?>
        <div id="cpm-post-meta-form" style="margin: -12px;">
            <?php
            echo $this->form()->table('open');

        try {
            $this->build($post)->withContext($this);
        } catch (Exception $e) {
            echo 'Exception: ' . esc_html($e->getMessage());
        }

        echo $this->form()->table('close');
        $this->form()->nonce($this->metaId);
        ?>
        </div>
        <?php
    }

    /**
     * Saves the meta data.
     *
     * @param int $post_id Post ID.
     */
    public function saveMeta(int $post_id): void
    {
        if (\defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if ( ! current_user_can('edit_post', $post_id)) {
            return;
        }

        global $post;

        if ( ! \is_object($post) || $post->post_type !== $this->postType) {
            return;
        }

        if ( ! $this->form()->verifyNonce($this->metaId)) {
            return;
        }

        // set the meta data.
        if ( ! empty($this->settings->data())) {
            $this->metaData = $this->settings->data();
        } else {
            $this->metaData = $this->getPostFieldsData();
        }

        apply_filters($this->metaField, $this->metaData, $post_id, $this->metaContext);

        do_action('cpm_before_meta_update', $this->metaData, $post_id, $post, $this->metaContext);

        // This will save array to a single field `metaField`
        update_post_meta($post_id, $this->metaField, $this->metaData);

        do_action('cpm_after_meta_update', $this->metaData, $post_id, $post, $this->metaContext);
    }

    public function getFormContext(): ?Form
    {
        return $this->formContext;
    }

    protected function getPostFieldsData(): array
    {
        $fields = $this->formContext->getFields();

        if ( ! \is_array($fields)) {
            throw new \InvalidArgumentException('Fields must be an array.');
        }

        $data = [];
        foreach ($fields as $key => $field) {
            if ( ! isset($field['field'], $field['id'])) {
                throw new \UnexpectedValueException("Each field must have 'field' and 'id' keys.");
            }

            // Determine the field key
            $fieldKey = ('textarea' === $field['field'])
                ? $field['id'] . '_textarea'
                : $field['id'];

            // Retrieve the value from $_POST
            $fieldValue = $_POST[$fieldKey] ?? null;

            // Apply custom filter, or default to sanitize_text_field
            if (isset($field['filter']) && \is_callable($field['filter'])) {
                $data[$fieldKey] = $field['filter']($fieldValue);
            } else {
                $data[$fieldKey] = sanitize_text_field($fieldValue);
            }
        }

        return $data;
    }

    protected function form(): Form
    {
        return $this->settings->getForm();
    }

    /**
     * Sets the meta box group name.
     *
     * @param array $args Arguments.
     *
     * @return string Meta box name.
     */
    protected function setName(array $args): string
    {
        $label = $args['name'] ?? $this->getClassName();

        return sanitize_title($label);
    }

    /**
     * Sets the arguments.
     *
     * @param array $args Arguments.
     *
     * @return array Processed arguments.
     */
    protected function setArgs(?array $args = null): array
    {
        if (\is_null($args)) {
            return ['zebra' => true];
        }

        return array_merge(['zebra' => true], $args);
    }

    /**
     * Displays the field ID.
     *
     * @param string $field Field name.
     *
     * @return string HTML for the field ID.
     */
    protected static function show_field_id(string $field): string
    {
        return wp_kses_post('<th style="color: darkgrey; font-weight: normal;"><small>' . $field . '</small></th>');
    }

    protected function addTableStyle(bool $zebra): void
    {
        if ($zebra) {
            $this->zebraTable();

            return;
        }
        $this->tableCss();
    }

    protected function tableCss(): void
    {
        ?><style media="all">
            #cpm-post-meta-form table {
                border-collapse: collapse;
                width: 100%;
            }
            #cpm-post-meta-form th, td {
                text-align: left;
                padding: 12px;
            }
        </style>
        <?php
    }

    protected function zebraTable(): void
    {
        ?>
        <style media="all">
           #cpm-post-meta-form table {
               border-collapse: collapse;
               width: 100%;
           }
           #cpm-post-meta-form th, td {
               text-align: left;
               padding: 12px;
           }
           #cpm-post-meta-form tbody tr:nth-child(even) {
               background: #f6f7f7;
           }
           #cpm-post-meta-form tbody tr:nth-child(even) {
               border-top: solid thin #eaeaea;
               border-bottom: solid thin #eaeaea;
           }
       </style>
        <?php
    }

    /**
     * Gets the class name for the meta box.
     *
     * @return string Class name.
     */
    private function getClassName(): string
    {
        try {
            $reflection = new ReflectionClass($this->settings);

            return $reflection->getShortName();
        } catch (Exception $e) {
            return 'Unknown';
        }
    }
}
