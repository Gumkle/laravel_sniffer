<?php

namespace App\Libs\Sniffer;


use TypeError;
use Illuminate\Database\Eloquent\Builder;
use App\Libs\Sniffer\Keyword\ModelKeywordFilter;
use App\Libs\Sniffer\Keyword\RelationsKeywordFilter;

class Sniffer
{

    public static function searchQueryForKeywordInColumns(Builder $query, String $keyword = null, array $columns = null)
    {
        $keywordFilter = new ModelKeywordFilter($query, $keyword);
        $keywordFilter->setColumns($columns);
        return $keywordFilter->getFilteredQuery();
    }

    public static function searchQueryForKeywordInColumnsAndRelations(Builder $query, String $keyword = null, Array $columns = null, Array $relationsColumns = null)
    {
        $keywordFilter = new RelationsKeywordFilter($query, $keyword);
        $keywordFilter->setColumns($columns);
        $keywordFilter->setRelationsColumns($relationsColumns);
        return $keywordFilter->getFilteredQuery();
    }

    /**
     * Try to apply findGreater filter and handle errors in case no keyword is specified
     * @param Builder $query
     * @param array $colsAndVals
     */
    public static function searchQueryForGreaterValues(Builder $query, Array $colsAndVals)
    {
        // TODO make error handling
        self::applyFindGreater($query, $colsAndVals);
    }

    /**
     * Search for models with values in given columns greater than given values
     *
     * @param Builder $query
     * @param array $colsAndVals
     */
    private static function applyFindGreater(Builder $query, Array $colsAndVals)
    {
        foreach ($colsAndVals as $col => $val) {
            if (isset($val)) {
                $query->where($col, '>', $val);
            }
        }
    }

    /**
     * Try to apply findLesser filter and handle errors in case no keyword is specified
     *
     * @param Builder $query
     * @param array $colsAndVals
     */
    public static function searchQueryForLesserValues(Builder $query, Array $colsAndVals)
    {
        try {
            self::applyFindLesser($query, $colsAndVals);
        } catch (TypeError $e) {
            return;
        }
    }

    /**
     * Search for models with values in given columns lesser than given values
     *
     * @param Builder $query
     * @param array $colsAndVals
     */
    private static function applyFindLesser(Builder $query, Array $colsAndVals)
    {
        foreach ($colsAndVals as $col => $val) {
            if (isset($val)) {
                $query->where($col, '>', $val);
            }
        }
    }

    /**
     * Try to apply findEqual filter and handle errors in case no keyword is specified
     *
     * @param Builder $query
     * @param array $colsAndVals
     */
    public static function searchQueryForEqualValues(Builder $query, Array $colsAndVals)
    {
        try {
            self::applyFindEqual($query, $colsAndVals);
        } catch (TypeError $e) {
            return;
        }
    }

    /**
     * Search for models with values in given columns equal to given values
     *
     * @param Builder $query
     * @param array $colsAndVals
     */
    private static function applyFindEqual(Builder $query, Array $colsAndVals)
    {
        foreach ($colsAndVals as $col => $val) {
            if (isset($val)) {
                $query->where($col, $val);
            }
        }
    }

    /**
     * Try to apply findMatchingValues filter and handle errors in case no keyword is specified
     *
     * @param Builder $query
     * @param array $colsAndVals
     */
    public static function searchQueryForValuesContainingValues(Builder $query, Array $colsAndVals)
    {
        try {
            self::applyFindMatchingValues($query, $colsAndVals);
        } catch (TypeError $e) {
            return;
        }
    }

    /**
     * Search for model with values in given columns equal to some of given values
     *
     * @param Builder $query
     * @param array $colsAndVals
     */
    private static function applyFindMatchingValues(Builder $query, Array $colsAndVals)
    {
        if (is_array($colsAndVals))
            foreach ($colsAndVals as $col => $val) {
                if (is_array($val))
                    $query->whereIn($col, $val);
            }
    }
}