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

trait Select
{
    /**
     * select field.
     *
     * @param array  $options   [description]
     * @param string $fieldname
     * @param string $js
     * @param bool   $required
     *
     * @return string .
     */
    public function select($options = [], $fieldname = 'name', $js = null, $required = false): string
    {
        $fieldname = strtolower($fieldname);

        // set selected option
        $selected = $this->selected($options);

        if (\array_key_exists('selected', $options)) {
            unset($options['selected']);
        }

        // set
        $js_function    = ($js) ? $js : '';
        $defualt_select = '<option selected="selected">Select an option</option>';

        // lets build out the select field
        $select  = '';
        $select .= '<tr class="input-' . str_replace(' ', '-', $fieldname) . '">';
        $select .= '<th>';
        $select .= '<label for="' . str_replace(' ', '_', $fieldname) . '">';
        $select .= ucwords(str_replace('_', ' ', $fieldname));
        $select .= '</label>';
        $select .= '</th>';
        $select .= '<td>';
        $select .= '<select onchange="' . $js_function . '" name="' . strtolower(str_replace(' ', '_', $fieldname)) . '" id="' . strtolower(str_replace(' ', '_', $fieldname)) . '" class="uk-select" >';
        // Options list Output.
        if (\is_array($options)) {
            foreach ($options as $optkey => $optvalue) {
                $select .= '<option value="' . $optkey . '">' . ucfirst($optvalue) . '</option>';
            }
        }
        $select .= '</select>';
        $select .= ' <strong style="color: #fdc006;">' . ucwords(str_replace('_', ' ', $selected)) . '</strong>';
        $select .= '<p class="description" id="' . str_replace(' ', '-', $fieldname) . '-description">';
        $select .= strtolower(str_replace('_', ' ', $fieldname));
        $select .= $this->isDescription($required);
        $select .= '</p>';
        $select .= '</td>';
        $select .= '</tr>';
        $select .= '<!-- select field ' . $fieldname . '_input -->';

        return $select;
    }


    /**
     * Set the Selected value.
     *
     * @param array $options the options list
     *
     * @return string
     */
    private static function selected($options = null): string
    {
        if (\array_key_exists('selected', $options)) {
            $selected = $options['selected'];
        } else {
            $selected = '';
        }

        return $selected;
    }
}
