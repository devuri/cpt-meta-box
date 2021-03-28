<?php

use DevUri\Meta\MetaBox;
use DevUri\Meta\Settings;

// implement meta fields
class Details extends Settings
{
    /**
     * the metabox settings
     * @param $get_meta
     */
	public function settings( $get_meta ): void
	{
		echo self::form()->textarea( 'Description', self::meta( 'description', $get_meta ) );
	}

    /**
     * the data
     * @param $post_data
     * @return array
     */
	public function data( $post_data ): array
	{
		$data['description'] = sanitize_textarea_field( $post_data['description_textarea'] );
		return $data;
	}

}


$details = new Details('vehicle'); // create metabox.

new MetaBox( $details ); // adds metabox no stripes.

new MetaBox( $details, true ); // adds metabox with zebra table.
