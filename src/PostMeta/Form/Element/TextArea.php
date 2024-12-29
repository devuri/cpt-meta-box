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

trait TextArea
{
    /**
     * Textarea.
     *
     * @param string $fieldname field name
     * @param bool   $required  set the filed to required
     *
     * @return string
     */
    public function textarea($fieldname = 'name', $required = false): string
    {
        $fieldname = strtolower($fieldname);

        // lets build out the textarea
        $textarea  = '<!-- ' . $fieldname . '_textarea -->';
        $textarea .= '<tr class="textarea">';
        $textarea .= '<th>';
        $textarea .= '<label for="' . str_replace(' ', '_', $fieldname) . '">';
        $textarea .= ucwords(str_replace('_', ' ', $fieldname));
        $textarea .= $required;
        $textarea .= '</label>';
        $textarea .= '</th>';
        $textarea .= '<td>';
        $textarea .= '<textarea class="uk-textarea" name="' . str_replace(' ', '_', $fieldname) . '_textarea" rows="8" cols="50">';
        $textarea .= '</textarea>';
        $textarea .= '<p class="description" id="' . str_replace(' ', '-', $fieldname) . '-description">';
        $textarea .= strtolower(str_replace('_', ' ', $fieldname));
        $textarea .= $this->isDescription($required);
        $textarea .= '</p>';
        $textarea .= '</td>';
        $textarea .= '</tr>';
        $textarea .= '<!-- ' . $fieldname . '_textarea -->';

        return $textarea;
    }

    /**
     * Alias for textarea method.
     *
     * @param string $fieldname
     * @param bool   $required
     *
     * @return string
     */
    public function text_area($fieldname = 'name', $required = false): string
    {
        return $this->textarea($fieldname, $required);
    }

    /**
     * The wp editor.
     *
     * @param string $content   .
     * @param string $editor_id .
     * @param array  $options   .
     *
     * @return string
     */
    public function editor(string $fieldname, $content = '', ?string $editor_id = null, $options = []): string
    {
        $fieldname    = strtolower($fieldname);
        $textfield_id = \is_null($editor_id) ? str_replace(' ', '_', $fieldname) : $editor_id;

        return '<tr class="input">
          <th><label for="' . str_replace(' ', '_', $fieldname) . '">
          ' . ucwords(str_replace('_', ' ', $fieldname)) . '
          </label></th>
        <td width="640">
          ' . $this->wpeditor($content, $textfield_id, $options = []) . '
          <p class="description" id="' . str_replace(' ', '_', $fieldname) . '">' . str_replace('_', ' ', $fieldname) . '.</p>
          </td>
        </tr>';
    }

    /**
     * A very basic version of the wp editor.
     *
     * @param string $content   .
     * @param string $editor_id .
     * @param array  $options   .
     *
     * @return false|string
     *
     * @see https://developer.wordpress.org/reference/functions/wp_editor/
     * @see https://developer.wordpress.org/reference/classes/_wp_editors/parse_settings/
     */
    public function wpeditor($content = '', $editor_id = 'new_editor', $options = [])
    {
        ob_start();
        $args = array_merge(
            [
                'media_buttons' => false,
                'quicktags'     => false,
                'tinymce'       => [
                    'toolbar1' => 'bold,italic,underline,separator,alignleft,aligncenter,alignright,separator,bullist,numlist,outdent,indent,blockquote,link,unlink,undo,redo',
                    'toolbar2' => '',
                    'toolbar3' => '',
                ],
            ],
            $options
        );
        wp_editor($content, $editor_id, $args);

        return ob_get_clean();
    }
}
