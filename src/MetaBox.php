<?php

namespace DevUri\Meta;

use DevUri\Meta\Traits\Form;
use DevUri\Meta\Traits\StyleTrait;
use Exception;
use ReflectionClass;

class MetaBox
{
    use Form;
    use StyleTrait;

    protected $post_type;
    protected $settings;
    protected $data = [];
    protected $metabox;
    protected $meta_id;
    protected $args;
    protected $fields;
    protected $meta_label;
    protected $meta_field;
    protected $group_key;

    /**
     * Setup.
     *
     * @param Settings $settings
     * @param bool     $args
     */
    public function __construct( Settings $settings, $args = true )
    {
        $this->args      = $this->set_args( $args );
        $this->settings  = $settings;
        $this->post_type = sanitize_title( $settings->post_type );

        // define meta name.
        $this->metabox    = $this->set_name( $this->args );
        $this->group_key  = '_' . hash( 'fnv1a32', $this->metabox );
        $this->meta_id    = 'cpm-group-' . $this->metabox . $this->group_key;
        $this->meta_field = $this->metabox . '_cpm';
        $this->meta_label = ucfirst( str_replace( '-', ' ', $this->metabox ) );

        // build the meta box.
        add_action( 'add_meta_boxes', [ $this, 'create_meta_box' ] );
        add_action( 'save_post', [ $this, 'save_meta_data' ] );
    }

    /**
     * Metabox build.
     *
     * @return Settings settings
     */
    public function build(): Settings
    {
        return $this->settings;
    }

    /**
     * Retrieves a post type object by name.
     *
     * @return WP_Post_Type object if it exists, null otherwise.
     *
     * @see https://developer.wordpress.org/reference/functions/get_post_type_object/
     */
    public function post_type_data()
    {
        return get_post_type_object( $this->post_type );
    }

    /**
     * Register meta.
     *
     * @see https://developer.wordpress.org/reference/functions/add_meta_box/
     */
    public function create_meta_box(): void
    {
        add_meta_box(
            $this->meta_id,
            $this->meta_label,
            [ $this, 'render' ],
            $this->post_type
        );
    }

    /**
     * Meta box display callback.
     *
     * @param WP_Post $post Current post object.
     */
    public function render( $post ): void
    {
        $this->table_style( $this->args['striped'] ); ?>
		<div id="cpm-post-meta-form" style="margin: -12px;">
			<?php
                echo self::form()->table( 'open' );

			/**
			 * Get meta data.
			 */
			$get_meta = $this->get_meta_data( $post->ID );

			/**
			 * Settings.
			 */
			try {
				$this->build()->settings( $get_meta );
				// echo self::show_field_id( $this->meta_field );
			} catch ( Exception $e ) {
				print 'Exception: ' . $e->getMessage();
			}

			echo self::form()->table( 'close' );
			self::form()->nonce();
			?>
	   </div>
		<?php
    }

    /**
     * Save Data.
     *
     * @param int $post_id .
     *
     * @return bool
     */
    public function save_meta_data( int $post_id ): bool
    {
        if ( \defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return false;
        }

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return false;
        }

        global $post;

        if ( ! \is_object( $post ) ) {
            return false;
        }

        if ( $post->post_type != $this->post_type ) {
            return false;
        }

        if ( ! self::form()->verify_nonce() ) {
            return false;
        }

        // data to save.
        $this->data = $this->build()->data( $_POST );

        /**
         * Filters the post meta data before save.
         *
         * @param string $this->meta_field meta name example 'movie_cpm'.
         * @param array  $this->data       the data being saved.
         * @param int    $post_id          The post ID.
         *
         * @phpstan-ignore-next-line
         */
        apply_filters( $this->meta_field, $this->data, $post_id );

        /**
         * Before meta update.
         *
         * @param array  $this->data the data being saved.
         * @param int    $post_id    The post ID.
         * @param object $post       The global $post object.
         */
        do_action( 'cpm_before_meta_update', $this->data, $post_id, $post );

        /**
         * Updates the post meta field.
         *
         * $data is saved as a single array of key val pairs.
         * example movies_meta[]
         *
         * @var
         */
        update_post_meta( $post_id, $this->meta_field, $this->data );

        /**
         * After meta update.
         *
         * @param array  $this->data the data being saved.
         * @param int    $post_id    The post ID.
         * @param object $post       The global $post object.
         */
        do_action( 'cpm_after_meta_update', $this->data, $post_id, $post );

        return true;
    }

    /**
     * Set the meta box group name.
     *
     * @param array $args
     *
     * @return string
     */
    protected function set_name( array $args ): string
    {
        $label = $args['name'] ?? $this->get_class_name();

        return sanitize_title( $label );
    }

    /**
     * Get the meta data.
     *
     * @param mixed $post_id
     *
     * @return array
     */
    protected function get_meta_data( $post_id ): array
    {
        $_meta = get_post_meta( $post_id, $this->meta_field, true );

        if ( empty( $_meta ) ) {
            return [];
        }

        return $_meta;
    }

    /**
     * Set args.
     *
     * @param mixed $args
     *
     * @return array .
     */
    protected function set_args( $args ): array
    {
        if ( ! \is_array( $args ) ) {
            return [ 'zebra' => true ];
        }
        if ( \is_array( $args ) ) {
            return array_merge(
                [
                    'zebra' => true,
                ],
                $args
            );
        }

        return [];
    }

    /**
     * Show field ID.
     *
     * @param string $field
     *
     * @return string
     */
    protected static function show_field_id( string $field ): string
    {
        return wp_kses_post( '<th style="color: darkgrey; font-weight: normal;"><small> ' . $field . '</small></th>' );
    }

    /**
     * Set Meta Name based on class name.
     *
     * @return string the class name.
     */
    private function get_class_name(): string
    {
        $class = null;

        try {
            $class = new ReflectionClass( $this->settings );
        } catch ( Exception $e ) {
            print $e;
        }

        return $class->getShortName();
    }
}
