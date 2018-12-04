<?php

namespace App\Libs\Sniffer\Keyword;

use App\Libs\Sniffer\Filter;
use Illuminate\Database\Eloquent\Builder;

abstract class KeywordFilter extends Filter
{
	protected $keyword;
	protected $columns;
	protected $columnQuery;

	public function __construct(Builder $query, String $keyword = null)
	{
		parent::__construct($query);
		if (isset($keyword))
			$this->keyword = $keyword;
	}

	public function setColumns(array $columns)
	{
		$this->columns = $columns;
	}

	protected function applyFilter()
	{
		if ($this->isKeywordSet()) {
			$this->initializeFilter();
		}
	}

	protected function isKeywordSet()
	{
		return isset($this->keyword);
	}

	protected function initializeFilter()
	{
		$this->query->where(function ($query) {
			$this->columnQuery = $query;

			if ($this->areColumnsSet()) {
				$this->applyFilterForColumns();
			}
		});
	}

	protected function areColumnsSet()
	{
		return !empty($this->columns);
	}

	protected function applyFilterForColumns()
	{
		foreach ($this->columns as $key => $column) {
			if ($key === 0) {
				$this->columnQuery->where($column, 'like', '%' . $this->keyword . '%');
			} else {
				$this->columnQuery->orWhere($column, 'like', '%' . $this->keyword . '%');
			}
		}
	}


}
