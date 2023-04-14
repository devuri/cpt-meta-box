<?php

namespace DevUri\Meta;

class Editor
{
    protected $content = '';
    protected $id      = 'new_editor';
    protected $toolbar = [];

    /**
     * Private $instance.
     *
     * @var
     */
    private static $instance;

    public function __construct( string $id, $content )
    {
        $this->content = $content;
        $this->id      = strtolower( $id );
        $this->toolbar = [
            'bold',
            'italic',
            'underline',
            'separator',
            'alignleft',
            'aligncenter',
            'alignright',
            'separator',
            'bullist',
            'numlist',
            'outdent',
            'indent',
            'blockquote',
            'link',
            'unlink',
            'undo',
            'redo',
        ];
    }

    /**
     * Singleton.
     *
     * @param $id
     * @param $content
     *
     * @return Editor
     */
    public static function init( $id, $content )
    {
        if ( ! isset( self::$instance ) ) {
            self::$instance = new self( $id, $content );
        }

        return self::$instance;
    }

    /**
     * The wp editor.
     *
     * @return false|string
     *
     * @see https://developer.wordpress.org/reference/functions/wp_editor/
     * @see https://developer.wordpress.org/reference/classes/_wp_editors/parse_settings/
     */
    public function editor()
    {
        ob_start();
        $args = [
            'media_buttons' => false,
            'quicktags'     => false,
            'tinymce'       => [
                'toolbar1' => implode( ',', $this->toolbar ),
                'toolbar2' => '',
                'toolbar3' => '',
            ],
        ];
        wp_editor( $this->content, $this->id . '_textarea', $args );

        return ob_get_clean();
    }

    /**
     * Get the editor.
     *
     * @return string
     */
    public function get()
    {
        $name  = str_replace( ' ', '_', $this->id );
        $label = ucwords( str_replace( '_', ' ', $name ) );

        return sprintf(
            '<tr class="input">
		    <th><label for="%1$s">%2$s</label></th>
			    <td>%3$s</td>
		    </tr>',
            $name,
            $label,
            $this->editor()
        );
    }
}
