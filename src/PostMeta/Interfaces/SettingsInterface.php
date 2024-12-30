<?php

/*
 * This file is part of the cptMeta package.
 *
 * (c) Uriel Wilson
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Urisoft\PostMeta\Interfaces;

use Urisoft\PostMeta\Form\Form;
use Urisoft\PostMeta\MetaBox;
use WP_Post;

interface SettingsInterface
{
    /**
     * @return Form
     */
    public function withContext(MetaBox $metaBox): Form;

    /**
     * Set up the settings and metadata for a specific post.
     *
     * @param WP_Post $postObject Current post object.
     * @param string  $metaField  Meta field name to retrieve data.
     *
     * @return SettingsInterface
     */
    public function create(WP_Post $postObject, string $metaField): self;

    /**
     * Get the post type.
     *
     * @return string
     */
    public function getPostType(): string;

    /**
     * Meta settings.
     */
    public function settings();

    /**
     * Settings data.
     */
    public function data();
}
