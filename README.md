# Sniffer
Application for making Laravel filtering easy! 

## Requirements
Application will work only in Laravel 5.5 and higher, and therefore PHP 7.0 is required.

## Installation
The best way to install this package is through Composer. 

### Composer
In folder where you project lies type in console: `composer install gumkle/laravel_sniffer`, and then the installation will start. If you face problems with versions, please try to specyfi package version, for example `composer install gumkle/laravel_sniffer="1.1.0"`.

## Usage
If you installed the package correctly then you should have access to package's Sniffer class. First you have to use it:
`use Sniffer\Sniffer;`

It is recommended to create new function in model, which jobs will be to call all these filters and return alredy prepared query, so that the controller can remain cleaner. Now you can access all the Sniffer Class' static methods, which are:

```php
Sniffer::searchFilter(Builder $query, String $keyword, Array $columns);
Sniffer::findGreater(Builder $query, Array $colsAndVals);
Sniffer::findLesser(Builder $query, Array $colsAndVals);
Sniffer::findEqual(Builder $query, Array $colsAndVals);
Sniffer::findMatchnigValues(Builder $query, Array $colsAndVals);
```

As you can see, each of filter functions require earlier prepared query and also returns query with applied "where" statements.

### Functions descriptions

#### Sniffer::searchFilter(Builder $query, $keyword, Array $columns);
This function requires as arguments `$keyword`, which is the string we're looking for, and `$column`, which is the array with the names of columns we're going to look for this string in.
 The package searches for all records, where the letters are in the same order as typed in keyword, for the string is processed to the form, where it has '%' every letter,
 so if you type in "something", the where qurey will look: "%s%o%m%e%t%h%i%n%g%".

#### Sniffer::findGreater(Builder $query, Array $colsAndVals);
This funtion requires `$colsAndVals` array, which is an array, where **keys are names of columns, and their values are the values, which the columns in model have to be greater from.** For example:

```php
Sniffer::findGreater($query, [
'age' => 18,
'height' => 160
]);
```

This query will search for records, where age is above 18 and where height is above 160.

#### Sniffer::findLesser(Builder $query, Array $colsAndVals);
This funtion rerquires the same array as in ::findGreater method, except it looks for values lower than given ones.

#### Sniffer::findEqual(Builder $query, Array $colsAndVals);
This function requires the same array as in ::findGreater method, except it looks for values equal to the given ones.

#### Sniffer::findMatchnigValues(Builder $query, Array $colsAndVals);
This function requires array, in which values are also arrays with values. It looks for columns, which are equal to at least one of the values provided for this column.
For example: 

```php
Sniffer:findMatchnigValues($query, [
'age' => [
14, 13, 12
],
'height' => [
160, 170, 180]
]);
```
This function will make query look for records, where age is 14, 13 or 12 and where height is 160, 170 or 180
