<?php

namespace Sniffer;


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
    public static function searchFilter(Builder $query, $keyword, Array $columns)
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
        // Convert string into array of single chars and stick them together to make sample
        $keyword = str_split($keyword, 1);
        $sample = "";
        foreach($keyword as $index => $char){

            if($index == 0)
                $sample = '%'.$char.'%';
            else
                $sample = $sample.$char.'%';

        }

        // Init where group, so the orWhere statements will be relative only to this group1
        $query->where(function ($query) use ($columns, $sample)
        {
            // Search for sample in first column
            $query->where($columns[0], 'like', $sample);

            // If there's more than one column - search for sample in the rest of them
            if(count($columns) > 1)
            {
                // We're skipping the first column for we already applied it above
                foreach(array_slice($columns,1) as $column)
                {
                    $query->orWhere($column, 'like', $sample);
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
    public static function findGreater(Builder $query, Array $colsAndVals)
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
    public static function findLesser(Builder $query, Array $colsAndVals)
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
    public static function findEqual(Builder $query, Array $colsAndVals)
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
    public static function findMatchingValues(Builder $query, Array $colsAndVals)
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
        foreach($colsAndVals as $col => $val)
        {
            $query->whereIn($col, $val);
        }
    }
}