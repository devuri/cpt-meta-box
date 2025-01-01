<?php

/*
 * This file is part of the cptMeta package.
 *
 * (c) Uriel Wilson
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Urisoft\PostMeta\QueryMonitor;

use QM_Collector;
use QM_Collectors;
use QM_Output_Html;


class Collector extends QM_Collector
{
    /**
     * Unique ID for the collector.
     *
     * @var string
     */
    public $id;

	public function __construct(array $metaContext, object $formContext)
    {
		$this->formContext = $formContext;
		$this->metaContext = $metaContext;

		// parent.
		$this->id = $this->metaContext['metaId'];
		$this->data = $this->get_storage();
    }

    /**
     * Returns the human-readable name for this panel.
     *
     * @return string
     */
    public function name(): string
    {
        return __('cpt Meta Library Data', 'cpt-meta');
    }

    /**
     * Gathers data to display in the Query Monitor panel.
     *
     * @return void
     */
    public function process(): void
    {
        $this->data['version'] = '1.0.0';
        $this->data['setting_x'] = get_option('my_plugin_setting_x');
        $this->data['active_features'] = ['feature_a', 'feature_b'];
        //$this->data['meta_context'][$this->id] = $this->metaContext;
    }
}
