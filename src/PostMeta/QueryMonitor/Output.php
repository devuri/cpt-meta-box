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

class Output extends QM_Output_Html
{
    /**
     * Constructor sets up the panel ID and label.
     *
     * @param QM_Collector $collector
     */
    public function __construct(QM_Collector $collector, array $metaContext)
    {

		$this->metaContext = $metaContext;

		parent::__construct($collector);

        // Must match the collector's $id.
        $this->id = $this->metaContext['metaId'];
        $this->label = __('cpt Meta Library Data', 'cpt-meta');
    }

    /**
     * Render the data in the Query Monitor panel.
     *
     * @return void
     */
    public function output(): void
    {
        $data = $this->collector->get_data();

        echo '<div class="qm qm-section">';
        echo '<h2>' . esc_html__('cptMeta Lib Information', 'cpt-meta') . '</h2>';

        // Simple list of key => value pairs
        echo '<dl>';
        foreach ($data as $key => $value) {
            echo '<dt>' . esc_html($key) . '</dt>';

            // If it's an array, convert it to a comma-separated string
            $displayValue = is_array($value)
                ? implode(', ', $value)
                : (string) $value;

            echo '<dd>' . esc_html($displayValue) . '</dd>';
        }
        echo '</dl>';

        echo '</div>';
    }
}
