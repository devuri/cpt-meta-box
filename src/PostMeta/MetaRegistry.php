<?php

/*
 * This file is part of the cptMeta package.
 *
 * (c) Uriel Wilson
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Urisoft\PostMeta;

use WP_Screen;

class MetaRegistry
{
    /**
     * The meta box ID.
     *
     * @var string
     */
    private string $id;

    /**
     * The meta box title.
     *
     * @var string
     */
    private string $title;

    /**
     * The callback used to display the meta box content.
     *
     * @var callable
     */
    private $callback;

    /**
     * The screen or screens on which to show the box (post type, page, etc.).
     *
     * @var null|array|string|WP_Screen
     */
    private $screen;

    /**
     * The context within the screen where the box should display.
     * Accepts 'normal', 'side', and 'advanced'.
     *
     * @var string
     */
    private string $context;

    /**
     * The priority within the context where the box should be displayed.
     * Accepts 'high', 'core', 'default' or 'low'.
     *
     * @var string
     */
    private string $priority;

    /**
     * Optional array of arguments to pass into your callback.
     *
     * @var null|array
     */
    private ?array $callbackArgs;

    /**
     * Constructor.
     *
     * @param string                      $id           Unique ID of the meta box.
     * @param string                      $title        The label or title of the meta box.
     * @param callable                    $callback     A callback function that renders the meta box.
     * @param null|array|string|WP_Screen $screen       The screen(s) or post type(s) on which to show the box.
     * @param string                      $context      The context within the screen: 'advanced', 'side', or 'normal'.
     * @param string                      $priority     The priority within the context: 'default', 'low', 'high'.
     * @param null|array                  $callbackArgs Optional arguments for the callback.
     */
    public function __construct(
        string $id,
        string $title,
        callable $callback,
        $screen = null,
        string $context = 'advanced',
        string $priority = 'default',
        ?array $callbackArgs = null
    ) {
        $this->id           = $id;
        $this->title        = $title;
        $this->callback     = $callback;
        $this->screen       = $screen;
        $this->context      = $context;
        $this->priority     = $priority;
        $this->callbackArgs = $callbackArgs;
    }

    /**
     * Create the meta box.
     *
     * @return void
     */
    public function addMeta(): void
    {
        add_action('add_meta_boxes', [$this, 'registerMetaBox']);
    }

    /**
     * Registers the meta box using the provided properties.
     *
     * @return void
     */
    public function registerMetaBox(): void
    {
        add_meta_box(
            $this->id,
            $this->title,
            $this->callback,
            $this->screen,
            $this->context,
            $this->priority,
            $this->callbackArgs
        );
    }
}
