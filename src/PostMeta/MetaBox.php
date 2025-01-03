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
    protected ?MetaRegistry $metaRegistry;

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

		// hooks and filters
		$this->beforeMetaUpdateHook = "cpm_before_meta_update_{$this->postType}";
		$this->afterMetaUpdateHook = "cpm_after_meta_update_{$this->postType}";

        // setup registry.
        $this->metaRegistry = new MetaRegistry(
            $this->metaId,
            $this->metaLabel,
            [$this, 'render'],
            $this->postType,
        );

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

        // Registers the meta box.
        add_action('add_meta_boxes', [$this->metaRegistry, 'registerMetaBox']);

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

        do_action($this->beforeMetaUpdateHook, $this->metaData, $post_id, $post, $this->metaContext);

        // This will save array to a single field `metaField`
        update_post_meta($post_id, $this->metaField, $this->metaData);

        do_action($this->afterMetaUpdateHook, $this->metaData, $post_id, $post, $this->metaContext);

		/**
		 * Generates a bypass view key for the specified post.
		 *
		 * This method is used to generate a bypass view key that can be shared with users to allow them to
		 * view the post item without making it publicly accessible. The generated key is stored as a
		 * WordPress transient, which is temporary and expires after a predefined time (1 day by default).
		 *
		 * The bypass view key can be included as a query parameter in the link sent to users, enabling
		 * preview access. This functionality is particularly useful in multi-review workflows where
		 * multiple stakeholders need to review the content before it is published. Stakeholders can use
		 * the bypass view link to review the content without making it publicly visible.
		 *
		 * Note: This requires the `'viewkey'` parameter to be set to `true` during class initialization.
		 *
		 * Example of a transient key that might be created:
		 * - `cpm_vehicle_viewkey_123` (for a "vehicle" post with ID 123)
		 *
		 * The transient contains a randomly generated hexadecimal string and expires after 1 day.
		 *
		 * @param int $post_id The ID of the post for which the bypass view key is being generated.
		 *
		 * @return void
		 */
		$this->setBypassViewKey($post_id);
    }

    public function getFormContext(): ?Form
    {
        return $this->formContext;
    }

	/**
	 * Sets a bypass view key for a specific post if none exists.
	 *
	 * This method generates a new bypass view key for the given post if it does not already exist.
	 * The key is stored as a transient with a lifespan of one day. The view key is only set if
	 * the required 'viewkey' argument is available.
	 *
	 * @param int $postId The ID of the post for which the bypass key is being set.
	 * @param int $bytes  Optional. The length of the token to generate, in bytes. Default is 32.
	 *
	 * @return void
	 */
	protected function setBypassViewKey( $postId, int $bytes = 32 ): void
	{
	    if ( ! $this->arg( 'viewkey' ) ) {
	        return;
	    }
	    $bypassKeyTransient = "cpm_{$this->postType}_viewkey_{$postId}";
	    if ( ! get_transient( $bypassKeyTransient ) ) {
	        set_transient( $bypassKeyTransient, bin2hex( random_bytes( $bytes ) ), DAY_IN_SECONDS );
	    }
	}

	private function arg(string $argKey)
	{
		return $this->args[$argKey] ?? null;
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

        return array_merge(
			[
				'zebra' => true,
				'viewkey' => false,
			],
			$args
		);
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
