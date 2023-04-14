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
    protected $fields = [];

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
     * @param $get_meta
     */
    abstract public function settings( $get_meta );

    /**
     * Lets build out the data settings.
     *
     * @param array $post_data $_POST variable items should be cleaned.
     *
     * @return array
     */
    public function data( $post_data ): array
    {
        if ( $this->auto_save ) {
            // TODO only process field in $this->field items.
            return array_map( 'sanitize_text_field', $post_data );
        }

        return [];
    }
}
