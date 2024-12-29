<?php

/*
 * This file is part of the cptMeta package.
 *
 * (c) Uriel Wilson
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Urisoft\PostMeta\Form\Element;

trait Image
{
    /**
     * Draggable grid list of images.
     * requires `draggable.js`.
     *
     * @see https://codepen.io/devuri/pen/JjmYYjR
     *
     * @param array $images
     * @param array $element
     */
    public function imageGrid(array $images = [], array $element = []): ?string
    {
        if (empty($images)) {
            return null;
        }

        $elem = array_merge(
            [
                'img_class' => 'image-grid-item',
                'div_id'    => 'image-list',
                'input_id'  => 'grid_images',
            ],
            $element
        );

        $imagelist = '';
        foreach ($images as $key => $img) {
            $imagelist .= $this->img($img, $elem['img_class']);
        }

        return \sprintf(
            '<tr><!-- grid element %s -->
				<th></th>
					<td>
					    <div id="%s">%s</div>
						<small style="color:#8c8f94;">double click on any item to delete</small>
						<input type="hidden" name="%s" id="%s">
					</td>
			</tr>',
            $elem['div_id'],
            $elem['div_id'],
            $imagelist,
            $elem['input_id'],
            $elem['input_id'],
        );
    }

    public function image($itm_id, string $itm_class): string
    {
        return $this->img($itm_id, $itm_class);
    }

    public function thumbnail(): ?string
    {
        if ( ! isset($this->postObject->ID)) {
            return null;
        }
        if ( ! has_post_thumbnail($this->postObject->ID)) {
            return null;
        }
        $id    = get_post_meta($this->postObject->ID, '_thumbnail_id', true);
        $image = '<img width="400" src="' . wp_get_attachment_url($id) . '" loading="lazy">';

        return $this->info('', $image, true);
    }

    protected static function img($itm_id, string $itm_class): string
    {
        return \sprintf(
            '<img class="%s" id="%s" style="padding-right: 4px; cursor: move;" width="190" src="%s">',
            $itm_class,
            $itm_id,
            wp_get_attachment_url($itm_id),
        );
    }
}
