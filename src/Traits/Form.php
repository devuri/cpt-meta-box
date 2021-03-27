<?php

namespace DevUri\Meta\Traits;

use WPAdminPage\FormHelper;

trait Form {

	/**
	 * Load the FormHelper class
	 *
	 * @return FormHelper
	 */
	public static function form(): FormHelper
    {
		return new FormHelper();
	}

}
