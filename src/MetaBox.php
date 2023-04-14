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
	protected $meta;
    protected $meta_id;
    protected $args;
    protected $fields;

    /**
     * Setup.
     *
     * @param Settings $settings
     * @param bool     $args
     */
    public function __construct( Settings $settings, $args = true )
    {
        $this->args      = $this->setArgs( $args );
        $this->settings  = $settings;
        $this->post_type = sanitize_title( $settings->post_type );

        // define meta name.
        $this->meta       = $this->get_class_name();
        $this->group_key  = '_' . hash( 'fnv1a32', $this->meta );
        $this->meta_id    = 'cpm-group-' . $this->meta . $this->group_key;
        $this->meta_field = $this->meta . '_cpm';

        // build the metabox.
        add_action( 'add_meta_boxes', [ $this, 'create_metabox' ] );
        add_action( 'save_post', [ $this, 'save_data' ] );
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
    public function create_metabox(): void
    {
        $meta_label = ucfirst( $this->meta );

        add_meta_box(
            $this->meta_id,
            __( $meta_label . ' ', 'brisko' ),
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
		<div id="post-meta-form" style="margin: -12px;">
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
	 * Get the meta data.
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
     * Save Data.
     *
     * @param int $post_id .
     *
     * @return bool
     */
    public function save_data( int $post_id ): bool
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
        do_action( 'cptm_before_meta_update', $this->data, $post_id, $post );

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
        do_action( 'cptm_after_meta_update', $this->data, $post_id, $post );

        return true;
    }

    /**
     * Set args.
     *
     * @param mixed $args
     *
     * @return array .
     */
    protected function setArgs( $args ): array
    {
        if ( ! \is_array( $args ) ) {
            return [ 'striped' => true ];
        }
        if ( \is_array( $args ) ) {
            return array_merge(
                [
                    'striped' => true,
                ],
                $args
            );
        }

        return [];
    }

    /**
     * Set Meta Name based on class name.
     *
     * @return string the class name.
     */
    protected function get_class_name(): string
    {
        $class = null;

        try {
            $class = new ReflectionClass( $this->settings );
        } catch ( Exception $e ) {
            print $e;
        }

        return sanitize_title( $class->getShortName() );
    }

    protected static function show_field_id( string $field )
    {
        return wp_kses_post( '<th style="color: darkgrey; font-weight: normal;"><small> ' . $field . '</small></th>' );
    }
}
