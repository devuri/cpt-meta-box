<?php

use DevUri\Meta\MetaBox;
use DevUri\Meta\Settings;

// implement meta fields
class Details extends Settings
{
	/**
	 * the metabox settings
	 */
	public function settings( $get_meta ): void
	{
		echo self::form()->textarea( 'Description', self::meta( 'description', $get_meta ) );
	}

	/**
     * the data
     */
	public function data( $post_data ): array
	{
		$data['description'] = sanitize_textarea_field( $post_data['description_textarea'] );
		return $data;
	}

}

// adds metabox to the "vehicle" post type.
$details = new Details('vehicle');
new MetaBox( $details );
