<?php

namespace DevUri\Meta;

use Exception;
use DevUri\Meta\Contracts\SettingsInterface;
use DevUri\Meta\Traits\Form;

abstract class Settings implements SettingsInterface
{

	use Form;

	/**
	 * Get the Post Types.
	 *
	 * @var string $post_type
	 */
	public $post_type;

    /**
     * Setup
     *
     * @param string|null $post_type .
     * @throws Exception
     */
	public function __construct( string $post_type = null ) {

		if ( is_null( $post_type ) || empty( $post_type ) ) {
			throw new Exception('Please check post type param: '.$post_type);
		}
		$this->post_type = $post_type;

	}

	/**
     * Use to get meta key.
     *
     * Solves Undefined index notice.
     *
     * @param string $key the meta key.
     * @param array $get_meta the meta array.
     * @return mixed
     */
	public static function meta( string $key, array $get_meta ): string
    {
		if ( isset( $get_meta[$key] ) ) {
			return $get_meta[$key];
		}
		return '';
	}

    /**
     * Lets build out the metabox settings
     * @param $get_meta
     */
	abstract function settings( $get_meta );

    /**
     * Lets get the build data.
     * @param $post_data
     */
	abstract function data( $post_data );

}
