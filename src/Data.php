<?php

namespace DevUri\Meta;

/**
 * The Data class.
 */
class Data
{
	/**
	 * Get the items.
	 *
	 * @var array $list .
	 */
	public $list = array();

	/**
	 * Get the post type.
	 *
	 * @var string $post_type .
	 */
	public $post_type = 'post';

	/**
	 * __construct
	 *
	 * @param string $post_type .
	 */
	public function __construct( $post_type = null )
	{
		$this->post_type = $post_type;
		$this->list = $this->items();
	}

    /**
     * Theme Data
     *
     * @param $post_type
     * @return Data .
     */
	public static function init( $post_type ): Data
    {
		return new Data( $post_type );
	}

    /**
     * Custom Edit link
     *
     * @param integer $id the ID.
     * @param string $class css class items.
     * @return string.
     */
	public static function edit( int $id, $class = '' ): string
	{
		if ( ! current_user_can( 'edit_posts' ) ) {
		    return '';
		}
		$text = 'Edit';
		$url = get_edit_post_link( $id );
		return ' <a class="' . esc_attr( $class ) . '" href="' . esc_url( $url ) . '">' . $text . '</a>';
	}

	/**
	 * List of post type items key value pairs
	 *
	 * @return array $item
	 * @link https://developer.wordpress.org/reference/functions/get_posts/
	 */
	public function list(): array
    {
		$items = array();
		foreach ( $this->items() as $item ) {
			$items[ $item->ID ] = $item->post_title;
		}
		return $items;
	}

    /**
     * Get array key if it it set or return null.
     *
     * @param $key
     * @param $data
     * @return false|mixed|null
     */
	public static function getkey( $key, $data )
    {
		if ( ! is_array( $data ) ) {
			return false;
		}

		if ( isset( $data[$key] ) ) {
			return $data[$key];
		}
		return null;
	}

    /**
     * Post meta
     *
     * Retrieves a post meta field for the given post ID.
     *
     * @param $ID
     * @param $name the meta name to retreive.
     * @return array .
     * @link https://developer.wordpress.org/reference/functions/get_post_meta/
     */
	public function meta( $ID, $name = null )
    {
		if ( is_null( $name ) ) {
			$name = $this->post_type . '_meta';
		}
        $meta_data = get_post_meta( $ID, $name, true );
		$meta_data['thumbnail'] = get_post_thumbnail_id( $ID );
		$meta_data['ID'] = $ID;

        return $meta_data;
	}

	/**
	 * Retrieves an array of the latest posts,
	 * or posts matching the given criteria.
	 *
	 * @param  int $n number of posts to get.
	 * @return array Array of post objects or post IDs.
	 */
	public function items( $n = -1 ): array
    {
		$args = array(
			'numberposts' => $n,
			'post_type'   => $this->post_type,
		);
		return get_posts( $args );
	}

}
