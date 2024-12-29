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

trait Data
{
    public function list(
        ?array $options = [],
        string $fieldname = 'name',
        ?string $js = null,
        bool $required = false
    ): string {
        $fieldname = strtolower($fieldname);

        // set selected option
        $selected = $this->selected($options);

        $selects = '';
        if (\is_array($options)) {
            foreach ($options as $optkey => $optvalue) {
                $selects .= '<option value="' . $optvalue . '">';
            }
        }

        return \sprintf(
            '<!-- input field %s input -->
				<tr class="input-%s"><th>
					<label for="%s">%s</label>
				</th>
					<td>
					<input style="padding: 8px 4px 8px 8px;" list="%s" id="%s" name="%s" />

					<datalist id="%s">
						%s
					</datalist>
						<p class="description" id="%s">%s %s</p>
					</td>
				</tr>',
            $fieldname,
            // <!-- comment.
            str_replace(' ', '-', $fieldname),
            // class.
            str_replace(' ', '_', $fieldname),
            // for label.
            ucwords(str_replace('_', ' ', $fieldname)),
            // label.
            str_replace(' ', '-', $fieldname),
            // list.
            str_replace(' ', '-', $fieldname . '-choice'),
            // input id.
            str_replace(' ', '-', $fieldname . '-choice'),
            // input name.
            str_replace(' ', '-', $fieldname),
            // datalist id.
            $selects,
            // options in list.
            str_replace(' ', '-', $fieldname),
            // <p> id
            strtolower(str_replace('_', ' ', $fieldname)),
            // <p> content.
            $this->isDescription($required)
            // <p> required text.
        );
    }

    /**
     * page_list building our own $pages array.
     *
     * @param array $arg [description]
     *
     * @see https://developer.wordpress.org/reference/functions/get_pages/
     *
     * @return array
     */
    public function getPages($arg = []): array
    {
        $arg = [
            'sort_column' => 'post_date',
            'sort_order'  => 'desc',
        ];
        // get the pages
        $pages     = get_pages($arg);
        $page_list = [];
        foreach ($pages as $pkey => $page) {
            $page_list[$page->ID] = $page->post_title;
        }

        return $page_list;
    }
    /**
     * Sanitizes a given field name to ensure its safety for use as a key or file name.
     *
     * This method employs two WordPress-sanitization functions. Firstly, `sanitize_file_name`
     * is applied to the field name to remove special characters and ensure valid file name characters.
     * Secondly, `sanitize_key` is used to sanitize the string for use as a key, which involves
     * lowercasing and removing characters that are not alphanumeric or dashes.s
     *
     * @param string $fieldname The field name to be sanitized.
     *
     * @return string Returns the sanitized version of the field name suitable for use as a key or file name.
     */
    protected static function sanitize(string $fieldname, bool $use_dashes = false): string
    {
        $field_id = sanitize_key(
            sanitize_file_name($fieldname)
        );

        if ($use_dashes) {
            return $field_id;
        }

        return str_replace('-', '_', $field_id);
    }
}
