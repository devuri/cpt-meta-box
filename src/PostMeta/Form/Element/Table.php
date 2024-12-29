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

// @codingStandardsIgnoreFile.

trait Table
{
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
     * thickbox builder.
     *
     * @param string $linktext
     * @param string $id
     *
     * @return string .
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
     * isDescription.
     *
     * set field as required, defaults to false.
     * Also used for input description since we pass back the value as output.
     *
     * @param bool        $descriptionInfo
     * @param bool|string $descriptionInfo
     *
     * @return null|string
     */
    public function isDescription($descriptionInfo = false): ?string
    {
        if ($descriptionInfo) {
            return ' <span style="font-size: unset; color: #939698;" class="description">' . esc_html($descriptionInfo) . '</span>';
        }

        return null;
    }





    public function tr($html = null, $hr = '<hr>'): string
    {
        return '<tr style="border-bottom: solid thin #e4e5e6;">' . $html . '</tr>';
    }

    /**
     * Make Table.
     *
     * Use this to create a table for the form
     *
     * @param string $tag     decide to open or close table
     * @param string $tbclass ad css class
     *
     * @return null|string
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
     * [submit_button description].
     *
     * @param string      $text The text of the button. Default 'Save Changes'.
     * @param string      $type The type and CSS class(es) of the button. Core values include 'primary', 'small', and 'large'.
     * @param string      $name name of the submit button
     * @param bool|string $wrap True if the output button should be wrapped in a paragraph tag, false otherwise.
     *
     * @return string the button html.
     *
     * @see https://developer.wordpress.org/reference/functions/get_submit_button/
     */
    public function submitButton($text = 'Save Changes', $type = 'primary large', $name = 'submit', $wrap = ''): string
    {
        return get_submit_button($text, $type, $name, $wrap);
    }

    /**
     * Define user access level for the admin form, who can acces and use the form.
     *
     * @param string $role .
     *
     * @return string
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


    private static function spinnerStyle($css = []): void
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
}
