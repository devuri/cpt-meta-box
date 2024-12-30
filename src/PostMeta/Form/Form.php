<?php

/*
 * This file is part of the cptMeta package.
 *
 * (c) Uriel Wilson
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Urisoft\PostMeta\Form;

class Form
{
    /**
     * @var string
     */
    public static $version = '1.5.2';

    /**
     * @var bool
     */
    protected bool $processing = false;

    /**
     * @var array
     */
    protected array $context;

    /**
     * @var array
     */
    protected array $fields;

    /**
     * @var string
     */
    protected string $wpnonce;

    /**
     * Constructor to initialize the fields and context properties.
     *
     * @param null|array $context Optional. An associative array of context data. Default is an empty array.
     */
    public function __construct(?array $context = [])
    {
        $this->fields = [];
        $this->context = $context;
    }

    /**
     * Retrieves the fields property.
     *
     * @return null|array The array of fields, or null if no fields are set.
     */
    public function getFields(): ?array
    {
        return $this->fields;
    }

    /**
     * Generates an HTML table row containing a textarea field.
     *
     * @param string $fieldname Optional. The name of the textarea field. Default is 'name'.
     * @param string $val       Optional. The initial content for the textarea. Default is an empty string.
     * @param bool   $required  Optional. Whether the field is required. Default is false.
     *
     * @return string The generated HTML for the textarea field.
     */
    public function textarea($fieldname = 'name', $val = '', $required = false): string
    {
        $fieldname = strtolower($fieldname);

        // lets build out the textarea
        $textarea  = '<!-- ' . $fieldname . '_textarea -->';
        $textarea .= '<tr class="textarea">';
        $textarea .= '<th>';
        $textarea .= '<label for="' . str_replace(' ', '_', $fieldname) . '">';
        $textarea .= ucwords(str_replace('_', ' ', $fieldname));
        // $textarea .= $required;
        $textarea .= '</label>';
        $textarea .= '</th>';
        $textarea .= '<td>';
        $textarea .= '<textarea class="uk-textarea" name="' . str_replace(' ', '_', $fieldname) . '_textarea" rows="8" cols="50">';
        $textarea .= wp_kses_post($val);
        $textarea .= '</textarea>';
        $textarea .= '<p class="description" id="' . str_replace(' ', '-', $fieldname) . '-description">';
        $textarea .= strtolower(str_replace('_', ' ', $fieldname));
        // $textarea .= $this->isDescription($required);
        $textarea .= '</p>';
        $textarea .= '</td>';
        $textarea .= '</tr>';
        $textarea .= '<!-- ' . $fieldname . '_textarea -->';

        return $textarea;
    }

    /**
     * Alias for the `textarea` method, generating a text area field.
     *
     * @param string $fieldname Optional. The name of the text area field. Default is 'name'.
     *  @param string $val      Optional. The initial content for the textarea. Default is an empty string.
     * @param bool   $required  Optional. Whether the field is required. Default is false.
     *
     * @return string The generated HTML for the text area field.
     */
    public function text_area($fieldname = 'name', $val = '', $required = false): string
    {
        return $this->textarea($fieldname, $val, $required);
    }

    /**
     * Generates a table row containing a WordPress editor field.
     *
     * @param string      $fieldname The name of the field, used for the editor's label and attributes.
     * @param string      $content   Optional. The initial content for the editor. Default is an empty string.
     * @param null|string $editor_id Optional. The ID for the editor instance. Defaults to the sanitized fieldname.
     * @param array       $options   Optional. Additional settings for the editor. Default is an empty array.
     *
     * @return string The generated HTML table row containing the WordPress editor.
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
     * Renders a basic version of the WordPress editor.
     *
     * @param string $content   Optional. The initial content for the editor. Default is an empty string.
     * @param string $editor_id Optional. The ID for the editor instance. Default is 'new_editor'.
     * @param array  $options   Optional. Additional settings for the editor. Default includes:
     *                          - 'media_buttons' => false
     *                          - 'quicktags'     => false
     *                          - 'tinymce'       => Custom toolbar configuration.
     *
     * @return false|string The rendered HTML for the editor, or false on failure.
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

    /**
     * Generates a styled user feedback message for display in the admin area.
     *
     * @param string $message    Optional. The feedback message to display. Default is 'Options updated'.
     * @param string $class      Optional. The CSS class for the notice type (e.g., 'success', 'error'). Default is 'success'.
     * @param string $element_id Optional. The HTML ID for the feedback element. Default is 'user-feedback'.
     *
     * @return string The generated HTML for the user feedback message.
     */
    public function userFeedback(
        $message = 'Options updated',
        $class = 'success',
        $element_id = 'user-feedback'
    ): string {
        return \sprintf(
            __(
                '<div style="font-size: small; margin: 20px; text-transform: capitalize; "
			id="%1$s" class="notice notice-%3$s is-dismissible">
			<p>%2$s</p></div>'
            ),
            $element_id,
            $message,
            $class,
        );
    }

    /**
     * Generates a ThickBox link with the specified text and target ID.
     *
     * @param string $linktext Optional. The text to display for the link. Default is 'click here'.
     * @param string $id       Optional. The ID of the ThickBox inline content. Default is an empty string.
     *
     * @return string The generated HTML for the ThickBox link.
     */
    public function thickboxLink($linktext = 'click here', $id = ''): string
    {
        return \sprintf(
            '<a href="#TB_inline?width=auto&inlineId=%s" class="thickbox">%s</a>',
            $id,
            $linktext,
        );
    }

    /**
     * Retrieves and escapes an option value for HTML attribute use.
     *
     * Fetches an option from the WordPress database using `get_option` and
     * sanitizes it with `esc_attr` for safe HTML attribute inclusion.
     *
     * @param string $option Name of the option to retrieve.
     *
     * @return string Escaped option value, or an empty string if the option does not exist.
     */
    public function getOption($option)
    {
        return esc_attr(get_option($option));
    }

    /**
     * Generates a description or required indicator for a field.
     *
     * @param bool|string $descriptionInfo Optional. The description text or flag indicating the field is required. Default is false.
     *
     * @return null|string The HTML for the description span if provided, or null if not.
     */
    public function isDescription($descriptionInfo = false): ?string
    {
        if ($descriptionInfo) {
            return ' <span style="font-size: unset; color: #939698;" class="description">' . esc_html($descriptionInfo) . '</span>';
        }

        return null;
    }

    /**
     * Generates an HTML table row with optional content and a bottom border.
     *
     * @param null|string $html Optional. The HTML content to include inside the table row. Default is null.
     * @param string      $hr   Optional. Unused parameter for horizontal rule content. Default is '<hr>'.
     *
     * @return string The generated HTML for the table row.
     */
    public function tr($html = null, $hr = '<hr>'): string
    {
        return '<tr style="border-bottom: solid thin #e4e5e6;">' . $html . '</tr>';
    }

    /**
     * Generates the opening or closing HTML tags for a table element used in a form.
     *
     * @param string $tag     Optional. Specifies whether to open or close the table. Accepts 'open' or 'close'. Default is 'close'.
     * @param string $tbclass Optional. Additional CSS classes to add to the table when opening. Default is an empty string.
     *
     * @return null|string The generated HTML for the table tag, or null if an invalid tag is provided.
     */
    public function table($tag = 'close', $tbclass = ''): ?string
    {
        if ('open' === $tag) {
            return '<table class="form-table ' . $tbclass . '" role="presentation"><tbody>';
        }

        if ('close' === $tag) {
            return '</tbody></table>';
        }

        return null;
    }

    /**
     * Generates and returns the HTML for a submit button.
     *
     * @param string      $text Optional. The text of the button. Default is 'Save Changes'.
     * @param string      $type Optional. The type and CSS class(es) of the button. Core values include 'primary', 'small', and 'large'. Default is 'primary large'.
     * @param string      $name Optional. The name attribute of the submit button. Default is 'submit'.
     * @param bool|string $wrap Optional. Whether to wrap the button in a paragraph tag. Default is an empty string.
     *
     * @return string The generated HTML for the submit button.
     *
     * @see https://developer.wordpress.org/reference/functions/get_submit_button/
     */
    public function submitButton($text = 'Save Changes', $type = 'primary large', $name = 'submit', $wrap = ''): string
    {
        return get_submit_button($text, $type, $name, $wrap);
    }

    /**
     * Retrieves the required capability for a given user role to access and use the admin form.
     *
     * @param string $role Optional. The user role for which to get the capability. Default is 'admin'.
     *
     * @return string The WordPress capability associated with the specified role.
     */
    public function access($role = 'admin'): string
    {
        $access = [
            'admin'       => 'manage_options',
            'editor'      => 'delete_others_pages',
            'author'      => 'publish_posts',
            'contributor' => 'edit_posts',
            'subscriber'  => 'read',
        ];

        return $access[$role];
    }

    /**
     * Outputs the spinner's inline CSS styles.
     *
     * @param array $css Optional. Array of CSS properties to customize the spinner's appearance. Default is an empty array.
     *
     * @return void
     */
    public function getSpinner($css = []): void
    {
        $this->spinnerStyle($css);
    }

    /**
     * Outputs an HTML spinner with accompanying CSS for a rotating animation.
     *
     * @param array $size An optional array to customize the spinner's size.
     *                    The first element is used for both width and height if only one value is provided.
     *                    If two values are provided, the first is the width and the second is the height.
     *
     * @return void
     */
    public function spinner(array $size = []): void
    {
        // Assign default size values
        $width  = $size[0] ?? '60px';
        $height = $size[1] ?? $width;
        // Use the same value for height if only one size is provided

        ?>
	    <div class="spinner"></div>

	    <style>
	        .spinner {
	            width: <?php echo htmlspecialchars($width); ?>;
	            height: <?php echo htmlspecialchars($height); ?>;
	            border: 6px solid #000; /* Black border */
	            border-top-color: #ff0000; /* Red top border */
	            border-radius: 50%;
	            animation: spin 0.8s linear infinite;
	            margin: 100px auto;
	        }

	        @keyframes spin {
	            to {
	                transform: rotate(360deg);
	            }
	        }
	    </style>
	    <?php
    }

    /**
     * Generates an HTML select field with a label, options, and optional JavaScript onchange functionality.
     *
     * @param string $fieldname Optional. The name of the field, used for the select's name, ID, and label. Default is 'name'.
     * @param array  $options   Optional. An associative array of options for the select field. Keys are option values, values are labels. Default is an empty array.
     * @param string $js        Optional. JavaScript function to execute on the onchange event. Default is null.
     * @param bool   $required  Optional. Whether the field is required. Default is false.
     *
     * @return string The generated HTML markup for the select field.
     */
    public function select(string $fieldname = 'name', array $options = [], $js = null, $required = false): string
    {
        $fieldname = strtolower($fieldname);

        // Set selected option
        $selected = $this->selected($options);

        if (\array_key_exists('selected', $options)) {
            unset($options['selected']);
        }

        // Prepare JavaScript function
        $js_function = $js ? $js : '';
        $default_select = '<option selected="selected">Select an option</option>';

        // Build the select field using sprintf
        $select = \sprintf(
            '<!-- select field %s -->
	        <tr class="input-%s">
	            <th>
	                <label for="%s">%s</label>
	            </th>
	            <td>
	                <select onchange="%s" name="%s" id="%s" class="uk-select">
	                    %s',
            str_replace(' ', '-', $fieldname),
            str_replace(' ', '-', $fieldname),
            str_replace(' ', '_', $fieldname),
            ucwords(str_replace('_', ' ', $fieldname)),
            $js_function,
            strtolower(str_replace(' ', '_', $fieldname)),
            strtolower(str_replace(' ', '_', $fieldname)),
            $default_select
        );

        // Add options to the select field
        foreach ($options as $optkey => $optvalue) {
            $select .= \sprintf(
                '<option value="%s"%s>%s</option>',
                $optkey,
                $optkey == $selected ? ' selected="selected"' : '',
                ucfirst($optvalue)
            );
        }

        $select .= \sprintf(
            '</select>
	        <strong style="color: #fdc006;">%s</strong>
	        <p class="description" id="%s-description">%s%s</p>
	        </td>
	        </tr>
	        <!-- select field %s -->',
            ucwords(str_replace('_', ' ', $selected)),
            str_replace(' ', '-', $fieldname),
            strtolower(str_replace('_', ' ', $fieldname)),
            $this->isDescription($required),
            $fieldname
        );

        return $select;
    }

    /**
     * Generates a nonce field and stores its reference.
     *
     * This method generates a nonce field using `wp_nonce_field()` and stores
     * the nonce name in the object for later use. It also adds the nonce field
     * information to the internal field data.
     *
     * @param int|string $action  Optional. Action name or ID for the nonce. Default -1.
     * @param string     $name    Optional. Nonce name. Default '_cptmeta_wpnonce'.
     * @param bool       $referer Optional. Whether to include the referer field. Default true.
     * @param bool       $display Optional. Whether to echo the field. Default false.
     *
     * @return string The HTML markup for the nonce field.
     *
     * @see https://developer.wordpress.org/reference/functions/wp_nonce_field/
     */
    public function getNonce($action = -1, $name = '_cptmeta_wpnonce', $referer = true, $display = false): string
    {
        $nonceField = wp_nonce_field($action, $name, $referer, $display);

        // Store the nonce name.
        $this->wpnonce = $name;

        // Add the nonce field to internal fields.
        $this->addField(['id' => $name, 'nonce_field' => $this->wpnonce]);

        return $nonceField;
    }

    /**
     * Generates and outputs a nonce field for form validation.
     *
     * @param string $name Optional. The name of the nonce field. Default is '_cptmeta_wpnonce'.
     *
     * @return void
     */
    public function nonce($name = '_cptmeta_wpnonce'): void
    {
        echo $this->getNonce(-1, $name);
    }

    /**
     * Verifies a nonce value to ensure the request is valid and secure.
     *
     * @param null|string $noncefield Optional. The name of the nonce field to check. Default is '_cptmeta_wpnonce'.
     *
     * @return bool True if the nonce is valid, false otherwise.
     *
     * @see https://developer.wordpress.org/reference/functions/wp_verify_nonce/
     */
    public function verifyNonce(?string $noncefield = '_cptmeta_wpnonce'): bool
    {
        if ($noncefield && isset($_POST[$noncefield])) {
            return wp_verify_nonce($_POST[$noncefield]);
        }

        return false;
    }

    /**
     * Generates an HTML input field with optional parameters and a submit button.
     *
     * This static function creates an HTML input field, allowing customization through
     * various parameters. It supports adding a label, custom classes, different input types,
     * and an optional submit button. It also includes accessibility features like `aria-describedby`.
     * The function adheres to WordPress coding standards, ensuring compatibility within WordPress projects.
     *
     * @param string $fieldtitle The name of the field, defaulting to 'item name'. It is sanitized and
     *                           used for both the input's name and its label. Two versions of sanitization
     *                           are performed: one for the field name (hyphenated) and one for the field ID (underscored).
     * @param string $val        The default value for the input field.
     * @param array  $args       Optional. An array of additional parameters to customize the input field. Possible keys include:
     *                           - 'required' (bool)   : If true, marks the input as required.
     *                           - 'class' (string)    : Additional CSS classes for the input element.
     *                           - 'type' (string)     : The type of the input (e.g., 'text', 'email').
     *                           - 'button' (string)   : If set, adds a submit button with the given label.
     *                           - 'hidden' (bool)     : If true, hides the input field.
     *                           - 'disabled' (bool)   : If true, disables the input field.
     *                           - 'info' (bool|string): Additional information or instructions for the input field.
     *                           - 'width' (string): The <td> width for the input field.
     *
     * @return string The HTML markup for the input field and optional button.
     */
    public function input($fieldtitle = 'item name', $val = '', array $args = []): string
    {
        $params = array_merge(
            [
                'name' => null,
                'required' => false,
                'class'    => 'uk-input form-control',
                'type'     => 'text',
                'button'   => null,
                'hidden'   => false,
                'disabled' => false,
                'info'     => false,
                'width'    => '200',
                'icon'    => 'dashicons-arrow-right',
            ],
            $args,
        );

        // changed to item-name
        $field_title = $this->sanitize($fieldtitle, true);

        // dashicons
        $dashicon = $params['icon'] ?? null;

        // changed to item_name
        $field_id = $this->sanitize($fieldtitle);

        // field name ID.
        $field_name = $params['name'] ?? $field_id;

        // return built out the input
        $output = \sprintf(
            '<!-- input field %s input -->
            <tr class="input-%s"><th>
                <span class="dashicons %s"></span>
                <label for="%s">%s</label>
            </th>
                <td width="%s">
                    <input type="%s" name="%s"
                    id="%s" aria-describedby="%s"
                    value="%s" class="%s" %s>
                    <p class="description" id="%s">%s %s</p>
                </td>
                <td>%s<p class="description" style="visibility: hidden;">...</p></td>
            </tr>',
            // <!-- comment
            $field_title,
            // class
            $field_title,
            // dashicon
            $dashicon,
            // for label
            $field_id,
            // label
            ucwords(str_replace('_', ' ', $field_id)),
            // width
            $params['width'],
            // type
            $params['type'],
            // name
            $field_name,
            // id
            $field_id,
            // describedby
            $field_title,
            // value
            $val,
            // input class
            esc_attr($params['class']),
            // disabled
            $this->getParam('disabled', $params),
            // <p> id
            $field_title,
            // <p> content
            str_replace('_', ' ', $field_id),
            // <p> required text
            $this->isDescription($params['info']),
            // submit button
            $this->inputButton($params['button'])
        );

        // save field in inputs array
        $this->addField([
            'id' => $field_id,
            'name' => $field_name,
            'params' => $params,
            'title' => $field_title,
            'dashicon' => $dashicon,
            'output' => $output,
        ]);

        return $output;
    }

    /**
     * Retrieves and sanitizes the value of a specified input field from the $_POST array.
     *
     * @param null|string $input_field The name of the input field to retrieve. Default is null.
     *
     * @return null|string The sanitized value of the input field, or null if it is empty or not set.
     */
    public function inputVal($input_field = null): ?string
    {
        $input = sanitize_text_field($_POST[$input_field]);
        if ( ! empty($input)) {
            return $input;
        }

        return null;
    }

    /**
     * Generates an HTML hidden input field within a table row.
     *
     * @param string $fieldtitle Optional. The name attribute for the hidden input field. Default is 'name'.
     * @param string $val        Optional. The value attribute for the hidden input field. Default is '...'.
     *
     * @return string The generated HTML for the hidden input field.
     */
    public function inputHidden($fieldtitle = 'name', $val = '...'): string
    {
        $fieldtitle = strtolower($fieldtitle);

        // lets build out the input
        $input_hidden  = '<!-- input field ' . $fieldtitle . '_input -->';
        $input_hidden .= '<tr class="input">';
        $input_hidden .= '<th>';
        $input_hidden .= '</th>';
        $input_hidden .= '<td>';
        $input_hidden .= '<input type="hidden" name="' . $this->sanitize($fieldtitle) . '" id="' . $this->sanitize($fieldtitle) . '" value="' . $val . '" class="uk-input">';
        $input_hidden .= '</td>';
        $input_hidden .= '</tr>';
        $input_hidden .= '<!-- input field ' . $fieldtitle . '_input -->';

        return $input_hidden;
    }

    /**
     * Generates an HTML button wrapped in an information table row.
     *
     * @param string      $name        Optional. The text to display on the button. Default is 'Send'.
     * @param null|string $label       Optional. The label for the information row. Default is null.
     * @param string      $description Optional. A description to display below the button. Default is an empty string.
     * @param null|string $bg_color    Optional. Background color for the information row. Default is null.
     *
     * @return string The generated HTML for the button within an information row.
     */
    public function button(string $name = 'Send', $label = null, $description = '', ?string $bg_color = null): string
    {
        $button_id = $this->sanitize($name);

        $button = '<a name="' . $button_id . '" class="button button-secondary button-large" id="' . $button_id . '" href="#">' . $name . '</a>';

        return $this->info($label, $button, $description, false, $bg_color);
    }

    /**
     * Generates an HTML table row displaying information about the current item.
     *
     * @param null|string $th_label    Optional. The heading label text. Default is null.
     * @param null|string $text        Optional. The main text description. Default is null.
     * @param null|string $description Optional. Additional description text. Default is null.
     * @param bool        $hr          Optional. Whether to add a bottom border. Default is false.
     * @param null|string $bg_color    Optional. Background color for the row. Default is null.
     *
     * @return string The generated HTML table row.
     */
    public function info(?string $th_label = null, $text = null, $description = null, $hr = false, $bg_color = null): string
    {
        $border = $hr ? 'border-bottom: solid thin #ccd0d4;' : '';

        if ($bg_color) {
            $background = "background-color: $bg_color;";
        } else {
            $background = '';
        }

        return \sprintf(
            '<tr style="%s %s">
                <th>
                <span style="font-size: large; font-weight: 400;color: #646970;">%s<span>
                </th>
                <td>%s <p id="desc_%s" class="description">%s</p></td>
            </tr>',
            $border,
            $background,
            $th_label,
            $text,
            $this->sanitize($th_label),
            $description,
        );
    }

    /**
     * Renders a table row containing an input field within an HTML form.
     *
     * This method generates a table row (`<tr>`) with a title (`<th>`) and a field (`<td>`).
     * The field can be a text input or a checkbox depending on the `$type` parameter.
     * An optional description can be displayed below the input field.
     *
     * @param string $title         The label displayed in the `<th>` element.
     * @param string $field_title   The name attribute for the input field.
     * @param string $value         Optional. The current value of the input field. Default empty string.
     * @param string $description   Optional. A description displayed below the input field. Default empty string.
     * @param string $type          Optional. The type of the input field (e.g., 'text', 'checkbox'). Default 'text'.
     * @param string $checked_value Optional. The value used to determine if the checkbox should be checked.
     *                              Only used if `$type` is 'checkbox'. Default '1'.
     */
    public function inputRow(string $title, string $field_title, string $value = '', string $description = '', string $type = 'text', string $checked_value = '1'): void
    {
        ?>
        <tr valign="top">
            <th scope="row">
                <?php echo esc_html($title); ?>
            </th>
            <td>
                <?php if ('checkbox' === $type) { ?>
                    <input type="checkbox" name="<?php echo esc_attr($field_title); ?>" value="<?php echo esc_attr($checked_value); ?>"
                        <?php checked($value, $checked_value); ?> />
                <?php } else { ?>
                    <input type="<?php echo esc_attr($type); ?>"
                           name="<?php echo esc_attr($field_title); ?>"
                           value="<?php echo esc_attr($value); ?>" />
                <?php } ?>
                <?php if ( ! empty($description)) { ?>
                    <p class="description">
                        <?php echo esc_html($description); ?>
                    </p>
                <?php } ?>
            </td>
        </tr>
        <?php
    }

    /**
     * Generates an HTML upload button field with a label and description.
     *
     * @param string $fieldname Optional. The name of the upload field. Default is 'upload_image_button'.
     * @param string $val       Optional. The label for the button. Default is 'Upload Image'.
     * @param bool   $required  Optional. Whether the field is required. Default is false.
     * @param string $type      Optional. The input type for the button. Default is 'button'.
     *
     * @return string The generated HTML for the upload button field.
     */
    public function upload($fieldname = 'upload_image_button', $val = 'Upload Image', $required = false, $type = 'button'): string
    {
        $fieldname      = strtolower($fieldname);
        $upload_button  = '<tr class="input">';
        $upload_button .= '<th>';
        $upload_button .= '<label for="' . str_replace(' ', '_', $fieldname) . '">';
        $upload_button .= ucwords(str_replace('_', ' ', $fieldname));
        $upload_button .= '</label>';
        $upload_button .= '</th>';
        $upload_button .= '<td>';
        $upload_button .= '<!-- upload field ' . $fieldname . '_input -->';
        $upload_button .= '<input id="' . str_replace(' ', '_', $fieldname) . '"';
        $upload_button .= 'type="' . $type . '" class="button"';
        $upload_button .= 'value="' . $val . '" />';
        $upload_button .= '<p class="description" id="' . str_replace(' ', '-', $fieldname) . '-description">';
        $upload_button .= strtolower(str_replace('_', ' ', $fieldname));
        $upload_button .= '</p>';
        $upload_button .= '</td>';
        $upload_button .= '</tr>';
        $upload_button .= '<!-- input field ' . $fieldname . '_input -->';

        return $upload_button;
    }

    /**
     * Generates an HTML grid of images with a hidden input field for tracking selected images.
     * Draggable grid list of images. requires `draggable.js`.
     *
     * @param array $images  Optional. Array of image IDs to include in the grid. Default is an empty array.
     * @param array $element Optional. Custom attributes for the grid, including:
     *                       'img_class' => CSS class for each image item. Default is 'image-grid-item'.
     *                       'div_id'    => ID for the container div. Default is 'image-list'.
     *                       'input_id'  => ID for the hidden input field. Default is 'grid_images'.
     *
     * @return null|string The generated HTML for the image grid, or null if no images are provided.
     *
     * @see https://codepen.io/devuri/pen/JjmYYjR
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

    /**
     * Generates an HTML image tag using the provided item ID and class.
     *
     * @param int    $itm_id    The ID of the image attachment.
     * @param string $itm_class The CSS class to apply to the image element.
     *
     * @return string The generated HTML <img> tag.
     */
    public function image($itm_id, string $itm_class): string
    {
        return $this->img($itm_id, $itm_class);
    }

    /**
     * Retrieves the thumbnail image HTML for the current post object, if available.
     *
     * @return null|string The HTML for the thumbnail image, or null if the post does not have a thumbnail.
     */
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

        return $this->info('', $image, '', true);
    }

    /**
     * Generates the HTML for a datalist input field with a dropdown of options.
     *
     * @param null|array  $options   Optional. The array of options to include in the datalist. Default is an empty array.
     * @param string      $fieldname Optional. The name of the field for the input. Default is 'name'.
     * @param null|string $js        Optional. JavaScript to include with the input field. Default is null.
     * @param bool        $required  Optional. Whether the field is required. Default is false.
     *
     * @return string The generated HTML for the datalist input field.
     */
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
     * Retrieves a list of pages with their IDs and titles.
     *
     * @param array $arg Optional. Arguments for retrieving pages. Default includes:
     *                   'sort_column' => 'post_date',
     *                   'sort_order'  => 'desc'.
     *
     * @see https://developer.wordpress.org/reference/functions/get_pages/
     *
     * @return array An associative array where keys are page IDs and values are page titles.
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
    public function sanitize(string $fieldname, bool $use_dashes = false): string
    {
        $field_id = sanitize_key(
            sanitize_file_name($fieldname)
        );

        if ($use_dashes) {
            return $field_id;
        }

        return str_replace('-', '_', $field_id);
    }

    /**
     * Generates the HTML for a category selection dropdown within a table row.
     *
     * @param null|string $fieldname Optional. The name of the field to use in the dropdown. Default is null.
     * @param array       $args      Optional. Additional arguments to customize the dropdown. Default is an empty array.
     *
     * @return string The generated HTML for the category dropdown field.
     */
    public function categorylist($fieldname = null, $args = []): string
    {
        return \sprintf(
            '<!-- input select field %s -->
			<tr class="input-select">
			<th><label for="select_dropdown">Select a Category</label></th>
			<td> %s </td>
			</tr>',
            $fieldname,
            wp_dropdown_categories($this->categoryOptions($fieldname, $args))
        );
    }

    /**
     * Outputs the HTML for a form row to create a new category.
     *
     * @param string $class Optional. Additional CSS classes to apply to the table row. Default is an empty string.
     *
     * @return void
     */
    public function createCategory(string $class = ''): void
    {
        ?><tr class="input-create-category <?php echo esc_attr($class); ?>"><th>
				<label for="create_category">Create Category</label>
			</th>
				<td>
					<input type="text" name="create_category" id="create_category" aria-describedby="create-category" value=" " class="hidden-cls">
				<p class="description" id="create-category">create category</p>
			</td>
		</tr>
		<?php
    }

    /**
     * Generates a unique hash for the given input parameters.
     *
     * @param array $inputParams The input parameters to be serialized and hashed.
     *
     * @return string The generated hash string using the 'fnv1a64' algorithm.
     */
    protected static function hashId(array $inputParams): string
    {
        return hash('fnv1a64', serialize($inputParams));
    }

    /**
     * Registers a new input field with unique parameters.
     *
     * @param array $inputParams The parameters for the input field, including:
     *                           'id'   => The unique identifier for the field.
     *                           'uuid' => A generated unique hash for the field (added automatically).
     *
     * @throws \ErrorException If the field ID is already registered.
     *
     * @return void
     */
    protected function addField(array $inputParams): void
    {
        $inputID = $inputParams['id'];

        if (\array_key_exists($inputID, $this->fields)) {
            trigger_error("$inputID already registered");

            return;
        }

        $inputParams['uuid'] = self::hashId($inputParams);

        $this->fields[$inputID] = $inputParams;
    }

    /**
     * Retrieves a parameter value by its key from the provided array.
     *
     * @param string $key    The key to look for in the parameters array.
     * @param array  $params Optional. The array of parameters to search. Default is an empty array.
     *
     * @return mixed The value of the specified key, or null if the key does not exist.
     */
    protected function getParam(string $key, $params = [])
    {
        return $params[$key] ?? null;
    }

    /**
     * Generates a submit button if a button label is provided.
     *
     * @param null|string $button The label for the button, or null if no button is needed.
     *
     * @return null|string The generated HTML for the submit button, or null if no label is provided.
     */
    protected function inputButton(?string $button): ?string
    {
        if ( ! \is_null($button)) {
            $button_id = 'submit_' . $this->sanitize($button);

            return $this->submitButton(ucwords($button), 'primary large', $button_id);
        }

        return null;
    }

    /**
     * Generates an HTML image tag with specified attributes.
     *
     * @param int    $itm_id    The ID of the image attachment.
     * @param string $itm_class The CSS class to apply to the image element.
     *
     * @return string The generated HTML <img> tag.
     */
    protected function img($itm_id, string $itm_class): string
    {
        return \sprintf(
            '<img class="%s" id="%s" style="padding-right: 4px; cursor: move;" width="190" src="%s">',
            $itm_class,
            $itm_id,
            wp_get_attachment_url($itm_id),
        );
    }

    /**
     * Generates the default options for a category dropdown field.
     *
     * @param string $fieldname The name of the field to be used in the dropdown attributes.
     * @param array  $args      Additional arguments to customize the options (not currently used).
     *
     * @return array An array of default options for rendering a category dropdown.
     */
    protected function categoryOptions(string $fieldname, array $args): array
    {
        return [
            'show_option_all'   => '',
            'show_option_none'  => '',
            'option_none_value' => '-1',
            'orderby'           => 'ID',
            'order'             => 'ASC',
            'show_count'        => 0,
            'hide_empty'        => 1,
            'child_of'          => 0,
            'exclude'           => '',
            'echo'              => 0,
            'selected'          => 0,
            'hierarchical'      => 0,
            'name'              => strtolower(str_replace(' ', '_', $fieldname)) . 'set_category',
            'id'                => '',
            'class'             => 'uk-select',
            'depth'             => 0,
            'tab_index'         => 0,
            'taxonomy'          => 'category',
            'hide_if_empty'     => false,
            'value_field'       => 'term_id',
        ];
    }

    /**
     * Outputs the inline CSS for a spinner loader with customizable styles.
     *
     * @param array $css Optional. Array of CSS styles with keys:
     *                   'padding'        => Padding value for the spinner container.
     *                   'padding-bottom' => Bottom padding value for the spinner container.
     *                   'size'           => Size of the loader (width and height).
     *
     * @return void
     */
    private function spinnerStyle($css = []): void
    {
        ?>
		<style media="screen">
		.loading {
			padding: <?php echo $css['padding']; ?>;
			padding-bottom: <?php echo $css['padding-bottom']; ?>;
		}
		.loader {
		width:<?php echo $css['size']; ?>;
		height: <?php echo $css['size']; ?>;
		border-radius: 200px;
		position: relative;
		animation: rotate 0.8s steps(12, end) infinite;
		}
		.loader .prong {
		position: absolute;
		height: 50%;
		width: 16px;
		left: calc(50% - 8px);
		transform-origin: bottom;
		}
		.loader .prong .inner {
		background: #34657f;
		border-radius: 12px;
		position: absolute;
		width: 100%;
		top: 0;
		height: 50%;
		}
		.loader .prong:nth-of-type(1) {
		opacity: 0.08;
		transform: rotate(30deg);
		}
		.loader .prong:nth-of-type(2) {
		opacity: 0.16;
		transform: rotate(60deg);
		}
		.loader .prong:nth-of-type(3) {
		opacity: 0.24;
		transform: rotate(90deg);
		}
		.loader .prong:nth-of-type(4) {
		opacity: 0.32;
		transform: rotate(120deg);
		}
		.loader .prong:nth-of-type(5) {
		opacity: 0.4;
		transform: rotate(150deg);
		}
		.loader .prong:nth-of-type(6) {
		opacity: 0.48;
		transform: rotate(180deg);
		}
		.loader .prong:nth-of-type(7) {
		opacity: 0.56;
		transform: rotate(210deg);
		}
		.loader .prong:nth-of-type(8) {
		opacity: 0.64;
		transform: rotate(240deg);
		}
		.loader .prong:nth-of-type(9) {
		opacity: 0.72;
		transform: rotate(270deg);
		}
		.loader .prong:nth-of-type(10) {
		opacity: 0.8;
		transform: rotate(300deg);
		}
		.loader .prong:nth-of-type(11) {
		opacity: 0.88;
		transform: rotate(330deg);
		}
		.loader .prong:nth-of-type(12) {
		opacity: 0.96;
		transform: rotate(360deg);
		}

		@keyframes rotate {
		from {
			transform: rotate(0deg);
		}
		to {
			transform: rotate(360deg);
		}
		}
		</style>
		<?php
    }

    /**
     * Retrieves the selected value from the provided options.
     *
     * @param array $options Array of options where the 'selected' key may exist.
     *
     * @return string The value of the 'selected' key if it exists, otherwise an empty string.
     */
    private static function selected(array $options): string
    {
        if (\array_key_exists('selected', $options)) {
            return $options['selected'];
        }

        return '';
    }
}
