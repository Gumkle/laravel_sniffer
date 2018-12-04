<?php

namespace App\Libs\Sniffer\Keyword;

use App\Libs\Sniffer\Keyword\KeywordFilter;

class RelationsKeywordFilter extends KeywordFilter
{
	private $relationsColumns;
	private $relationNames;
	private $currentRelationName;
	private $relationQuery;

	public function setRelationsColumns(array $relationsColumns)
	{
		$this->relationsColumns = $relationsColumns;
		$this->relationNames = array_keys($this->relationsColumns);
	}

	protected function applyFilter()
	{
		if ($this->isKeywordSet()) {

			if ($this->areRelationsSet()) {
				$this->setRelationsOnQuery();
			}
			$this->initializeFilter();
		}
	}

	private function areRelationsSet()
	{
		return !empty($this->relationsColumns);
	}

	private function setRelationsOnQuery()
	{
		$this->query->with($this->relationNames);
	}

	protected function initializeFilter()
	{
		$this->query->where(function ($query) {
			$this->columnQuery = $query;
			$this->relationQuery = $query;

			if ($this->areColumnsSet()) {
				$this->applyFilterForColumns();
			}
			if ($this->areRelationsSet()) {
				$this->applyFilterForRelations();
			}

		});
	}

	private function applyFilterForRelations()
	{
		foreach ($this->relationNames as $key => $relationName) {
			$this->currentRelationName = $relationName;
			if (!$this->areColumnsSet() && $key === 0) {
				$this->relationQuery->whereHas($this->currentRelationName, function ($query) {
					$this->columnQuery = $query;
					$this->columns = $this->relationsColumns[$this->currentRelationName];
					$this->applyFilterForColumns();
				});
			} else {
				$this->relationQuery->orWhereHas($this->currentRelationName, function ($query) {
					$this->columnQuery = $query;
					$this->columns = $this->relationsColumns[$this->currentRelationName];
					$this->applyFilterForColumns();
				});
			}
		}
	}
}
