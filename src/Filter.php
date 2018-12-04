<?php

namespace App\Libs\Sniffer;

use Illuminate\Database\Eloquent\Builder;
use TypeError;


abstract class Filter
{
	protected $query;

	public function __construct(Builder $query)
	{
		$this->query = $query;
	}

	public function getFilteredQuery()
	{
		$this->filterWithErrorInterception();
		return $this->query;
	}

	private function filterWithErrorInterception()
	{
		try {
			$this->applyFilter();
		} catch (TypeError $e) {
			// No required argument specified, don't execute callback - query just won't run.
		}
	}

	abstract protected function applyFilter();
}