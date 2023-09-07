<?php

namespace DevUri\PostTypeMeta\Contracts;

use WPAdminPage\FormHelper;

interface SettingsInterface
{
    /**
     * Load the FormHelper class.
     *
     * @return FormHelper
     */
    public static function form(): FormHelper;

    /**
     * Meta settings.
     */
    public function settings();

    /**
     * Settings data.
     *
     * @param $post_data
     */
    public function data( $post_data );
}
