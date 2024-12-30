<?php

/*
 * This file is part of the cptMeta package.
 *
 * (c) Uriel Wilson
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Urisoft\PostMeta\MetaBox;
use Urisoft\PostMeta\PostType;
use Urisoft\PostMeta\Settings;

/**
 * Creates and registers a meta box for a custom post type.
 *
 * This function initializes a custom post type and associates it with a meta box
 * based on the provided meta settings and arguments. It ensures that the post type
 * is defined within the provided meta settings and throws an exception if not.
 *
 * @param Settings    $metaSettings    Instance of the Settings class containing meta fields and configuration.
 *                                     Must include the post type.
 * @param array       $args            Optional. Additional arguments for the MetaBox configuration.
 *                                     Defaults to an empty array.
 * @param null|string $singularName    Optional. Singular name for the post type. Defaults to null.
 * @param null|string $pluralName      Optional. Plural name for the post type. Defaults to null.
 * @param array       $postTypeOptions Optional. Additional options for configuring the post type.
 *                                     Defaults to an empty array.
 *
 * @throws InvalidArgumentException If the post type is not defined in the meta settings.
 *
 * @return bool Returns true if the meta box was successfully registered, false otherwise.
 */
function createMeta(
    Settings $metaSettings,
    array $args = [],
    ?string $singularName = null,
    ?string $pluralName = null,
    array $postTypeOptions = []
) {
    if ( ! $metaSettings->getPostType()) {
        throw new InvalidArgumentException('Post type is required in meta settings.');
    }

    $postType = new PostType(
        $metaSettings->getPostType(),
        $singularName,
        $pluralName,
        $postTypeOptions
    );

    $metaBox = new MetaBox($metaSettings, $args);

    return $metaBox->register($postType);
}
