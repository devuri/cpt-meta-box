<?php

/*
 * This file is part of the cptMeta package.
 *
 * (c) Uriel Wilson
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Urisoft\PostMeta\Traits;

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
    public function info($th = null, $text = null, $hr = false): string
    {
        $border = $hr ? 'border-bottom: solid thin #ccd0d4;' : '';

        return \sprintf(
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
     * @return null|string
     */
    public function theId()
    {
        if ( ! isset($this->postObject->ID)) {
            return null;
        }
        $id = '<input type="text" value="' . $this->postObject->ID . '" disabled>';

        return $this->info('ID', $id);
    }

    /**
     * Integer list.
     *
     * @param string $list .
     *
     * @return array $list.
     */
    public function sanitizeIntlist($list): array
    {
        $list = wp_strip_all_tags($list);
        $list = preg_replace('/[^0-9,]/', '', $list);

        return explode(',', $list);
    }

    /**
     * Clean Price.
     *
     * @param $price
     *
     * @return int
     */
    public function sanitizePrice($price): int
    {
        $price = sanitize_title($price);
        $price = str_replace('-', '', $price);

        return absint($price);
    }

    /**
     * input_val.
     *
     * Get the input field $_POST data
     *
     * @param string $input_field input name
     *
     * @return null|string
     */
    public function inputVal($input_field = null)
    {
        $input = sanitize_text_field($input_field);
        if ( ! empty($input)) {
            return $input;
        }

        return null;
    }

    /**
     * Use to get meta key.
     *
     * Solves Undefined index notice.
     *
     * @param string $key  the meta key.
     * @param array  $meta the meta array.
     *
     * @return string
     */
    public function meta(string $key, array $meta): string
    {
        if (isset($meta[$key])) {
            return $meta[$key];
        }

        return '';
    }

    /**
     * Use to get meta data.
     *
     * @param string $key the meta key.
     *
     * @return string
     */
    public function getMeta(string $key): string
    {
        if (isset($this->meta_data[$key])) {
            return $this->meta_data[$key];
        }

        return '';
    }
}
