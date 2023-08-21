<?php

namespace DevUri\Meta;

use DevUri\Meta\Contracts\SettingsInterface;
use DevUri\Meta\Traits\Form;
use DevUri\Meta\Traits\MetaTrait;
use Exception;

abstract class Settings implements SettingsInterface
{
    use Form;
    use MetaTrait;

    /**
     * Get the Post Types.
     *
     * @var string
     */
    public $post_type;

    /**
     * List of input fields.
     *
     * @var array .
     */
    protected $fields = null;

    /**
     * List of input fields.
     *
     * @var array
     */
    protected $meta_data = null;

    /**
     * Current post object.
     *
     * @var WP_Post Current post object.
     */
    protected $post_object = null;

    /**
     * Setup.
     *
     * @param null|string $post_type .
     *
     * @throws Exception
     */
    public function __construct( string $post_type )
    {
        if ( \is_null( $post_type ) || empty( $post_type ) ) {
            throw new Exception( 'Please check post type param: ' . $post_type );
        }
        $this->post_type = $post_type;
    }

    /**
     * Lets build out the metabox settings.
     *
     * @param WP_Post $post_object Current post object.
     * @param string  $meta_field  the meta field name.
     */
    public function create( $post_object, $meta_field ): SettingsInterface
    {
        $this->post_object = $post_object;
        $this->meta_data   = get_post_meta( $post_object->ID, $meta_field, true );

        if ( empty( $this->meta_data ) ) {
            $this->meta_data = [];
        }

        return $this;
    }

    /**
     * Lets build out the metabox settings.
     */
    abstract public function settings();

    /**
     * Lets build out the data settings.
     *
     * @param array $post_data $_POST variable items should be cleaned.
     *
     * @return array
     */
    public function data( $post_data ): array
    {
        if ( $this->fields ) {
            // TODO only process field in $this->fields items.
            return array_map( 'sanitize_text_field', $post_data );
        }

        return [];
    }
}
