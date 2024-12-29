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

trait Nonce
{
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
        // @phpstan-ignore-next-line
        $nonceField = wp_nonce_field($action, $name, $referer, $display);

        // Store the nonce name.
        $this->wpnonce = $name;

        // Add the nonce field to internal fields.
        $this->addField(['nonce_field' => $this->wpnonce]);

        return $nonceField;
    }

    /**
     * Generates and displays a nonce field.
     *
     * @param string $name Optional. Nonce name. Default '_cptmeta_wpnonce'.
     *
     * @return void
     */
    public function nonce($name = '_cptmeta_wpnonce'): void
    {
        echo $this->getNonce(-1, $name);
    }

    /**
     * nonce_check.
     *
     * @param string $noncefield [description]
     *
     * @return bool
     *
     * @see https://developer.wordpress.org/reference/functions/wp_verify_nonce/
     */
    public function verifyNonce(?string $noncefield = null): bool
    {
        if ($noncefield && isset($_POST[$noncefield])) {
            return wp_verify_nonce($_POST[$noncefield]);
        }

        if (isset($_POST[$this->wpnonce])) {
            return wp_verify_nonce($_POST[$this->wpnonce]);
        }

        return false;
    }
}
