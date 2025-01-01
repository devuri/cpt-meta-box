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

class QueryMonitor
{
	protected static $monitor;

    public function __construct(array $metaContext, object $formContext)
    {
		$this->formContext = $formContext;
		$this->metaContext = $metaContext;
		self::$monitor = $this->metaContext['metaId'];
    }

	public function init()
	{
		add_filter('qm/collectors', [$this, 'registerCollector']);
		add_filter('qm/outputter/html',  [$this, 'registerOutput']);
	}
	/**
	 * Registers the Collector class with Query Monitor.
	 *
	 * @param array $collectors
	 * @return array
	 */
	public function registerCollector(array $collectors): array
	{
	    $collectors[self::$monitor] = new Collector($this->metaContext, $this->formContext);
	    return $collectors;
	}

	/**
	 * Registers the Output class to display the collected data.
	 *
	 * @param array $output
	 * @return array
	 */
	public function registerOutput(array $output): array
	{
	    $collector = QM_Collectors::get(self::$monitor);
	    if ($collector instanceof QM_Collector) {
	        $output[self::$monitor] = new Output($collector, $this->metaContext);
	    }
	    return $output;
	}
}
