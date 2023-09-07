<?php

namespace DevUri\PostTypeMeta\Traits;

trait MetaTrait
{
    /**
     * Info for the current item.
     *
     * @param string $th   heading
     * @param string $text the text description.
     * @param bool   $hr   whether to add bottom border.
     *
     * @return string table row.
     */
    public static function info( $th = null, $text = null, $hr = false ): string
    {
        $border = $hr ? 'border-bottom: solid thin #ccd0d4;' : '';

        return sprintf(
            '<tr style="%1$s">
				<th style="color: darkgrey;">%2$s</th>
				<td>%3$s</td>
			</tr>',
            $border,
            $th,
            $text
        );
    }

    /**
     * Get the ID.
     *
     * @return string
     */
    public function the_id()
    {
        if ( ! isset( $this->post_object->ID ) ) {
            return;
        }
        $id = '<input type="text" value="' . $this->post_object->ID . '" disabled>';

        return self::info( 'ID', $id );
    }

    /**
     * Get current item thumbnail.
     *
     * @return string
     */
    public function thumbnail()
    {
        if ( ! isset( $this->post_object->ID ) ) {
            return;
        }
        if ( ! has_post_thumbnail( $this->post_object->ID ) ) {
            return;
        }
        $id    = get_post_meta( $this->post_object->ID, '_thumbnail_id', true );
        $image = '<img width="400" src="' . wp_get_attachment_url( $id ) . '" loading="lazy">';

        return self::info( '', $image, true );
    }

    /**
     * Integer list.
     *
     * @param string $list .
     *
     * @return array $list.
     */
    public static function sanitize_intlist( $list ): array
    {
        $list = wp_strip_all_tags( $list );
        $list = preg_replace( '/[^0-9,]/', '', $list );

        return explode( ',', $list );
    }

    /**
     * Clean Price.
     *
     * @param $price
     *
     * @return mixed
     */
    public static function sanitize_price( $price ): int
    {
        $price = sanitize_title( $price );
        $price = str_replace( '-', '', $price );

        return absint( $price );
    }

    /**
     * input_val.
     *
     * Get the input field $_POST data
     *
     * @param string $input_field input name
     *
     * @return string
     */
    public static function input_val( $input_field = null )
    {
        $input = sanitize_text_field( $input_field );
        if ( ! empty( $input ) ) {
            return $input;
        }

        return false;
    }

    /**
     * Use to get meta key.
     *
     * Solves Undefined index notice.
     *
     * @param string $key  the meta key.
     * @param array  $meta the meta array.
     *
     * @return mixed
     */
    public static function meta( string $key, array $meta ): string
    {
        if ( isset( $meta[ $key ] ) ) {
            return $meta[ $key ];
        }

        return '';
    }

    /**
     * Use to get meta data.
     *
     * @param string $key the meta key.
     *
     * @return mixed
     */
    public function get_meta( string $key ): string
    {
        if ( isset( $this->meta_data[ $key ] ) ) {
            return $this->meta_data[ $key ];
        }

        return '';
    }
}
