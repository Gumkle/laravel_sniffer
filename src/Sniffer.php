<?php

namespace App\Libs\Sniffer;


use Illuminate\Database\Eloquent\Builder;
use TypeError;

class Sniffer
{

    /**
     * Try to apply search filter and handle any errors
     *
     * @param Builder $query
     * @param $keyword
     * @param array $columns
     * @return string
     */
    public static function SearchQueryForKeywordsInColumns(Builder $query, $keyword, Array $columns)
    {
        try{
            self::applySearchFilter($query, $keyword, $columns);
        } catch (TypeError $e){
            return 'No keyword';
        }
    }

    /**
     * Search for keyword in given columns and save results to query
     *
     * @param Builder $query
     * @param String $keyword
     * @param array $columns
     * @return string
     */
    private static function applySearchFilter(Builder $query, String $keyword, Array $columns)
    {
        $query->where(function ($query) use ($columns, $keyword)
        {
            $query->where($columns[0], 'like', '%'.$keyword.'%');

            if(count($columns) > 1)
            {
                foreach(array_slice($columns,1) as $column)
                {
                    $query->orWhere($column, 'like', '%'.$keyword.'%');
                }
            }
        });

        return 'Search applied';
    }


    /**
     * Try to apply findGreater filter and catch any errors
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
        foreach ($colsAndVals as $col => $val)
        {
            if(isset($val))
            {
                $query->where($col, '>', $val);
            }
        }
    }

    /**
     * Try to apply findLesser filter and catch any errors
     *
     * @param Builder $query
     * @param array $colsAndVals
     */
    public static function searchQueryForLesserValues(Builder $query, Array $colsAndVals)
    {
        // TODO make error handling
        self::applyFindLesser($query, $colsAndVals);
    }

    /**
     * Search for models with values in given columns lesser than given values
     *
     * @param Builder $query
     * @param array $colsAndVals
     */
    private static function applyFindLesser(Builder $query, Array $colsAndVals)
    {
        foreach ($colsAndVals as $col => $val)
        {
            if(isset($val))
            {
                $query->where($col, '>', $val);
            }
        }
    }

    /**
     * Try to apply findEqual filter and catch any errors
     *
     * @param Builder $query
     * @param array $colsAndVals
     */
    public static function searchQueryForEqualValues(Builder $query, Array $colsAndVals)
    {
        // TODO make error handling
        self::applyFindEqual($query, $colsAndVals);
    }

    /**
     * Search for models with values in given columns equal to given values
     *
     * @param Builder $query
     * @param array $colsAndVals
     */
    private static function applyFindEqual(Builder $query, Array $colsAndVals)
    {
        foreach ($colsAndVals as $col => $val)
        {
            if(isset($val))
            {
                $query->where($col, $val);
            }
        }
    }

    /**
     * Try to apply findMatchingValues filter and catch any errors
     *
     * @param Builder $query
     * @param array $colsAndVals
     */
    public static function searchQueryForValuesContainingValues(Builder $query, Array $colsAndVals)
    {
        // TODO make error hangling
        self::applyFindMatchingValues($query, $colsAndVals);
    }

    /**
     * Search for model with values in given columns equal to some of given values
     *
     * @param Builder $query
     * @param array $colsAndVals
     */
    private static function applyFindMatchingValues(Builder $query, Array $colsAndVals)
    {
        if(is_array($colsAndVals))
            foreach($colsAndVals as $col => $val)
            {
                if (is_array($val))
                    $query->whereIn($col, $val);
            }
    }
}