<?php defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('array_merge_recursive_distinct'))
{
    /**
     * @param array $arr1
     * @param array $arr2
     * @return array
     */
    function array_merge_recursive_distinct($arr1, $arr2)
    {
        foreach ($arr2 as $key => $value)
        {
            if ( isset($arr1[$key]) && is_array($arr1[$key]) )
            {
                if ( is_array($value) )
                {
                    $arr1[$key] = array_merge_recursive_distinct($arr1[$key], $value);
                }
                else
                {
                    $arr1[$key][] = $value;
                }
            }
            else
            {
                if ( is_string($key) )
                {
                    $arr1[$key] = $value;
                }
                else
                {
                    $arr1[] = $value;
                }
            }
        }

        return $arr1;
    }
}

if ( ! function_exists('array_object_column'))
{
    /**
     * @param array $input
     * @param string $column_key
     * @param null|string $index_key
     * @return array
     */
    function array_object_column(array $input, $column_key, $index_key = NULL)
    {
        $arr = array();

        if ( $column_key === NULL && $index_key !== NULL )
        {
            foreach ($input as $element)
            {
                if ( is_object($element) ) { $element = (array) $element; }
                elseif ( is_array($element) ) { }
                else { continue; }

                if ( ! isset($element[$index_key]) ) { continue; }
                $arr[$element[$index_key]] = $element;
            }
        }
        elseif ( $column_key !== NULL)
        {
            if ( $index_key === NULL )
            {
                foreach ($input as $element)
                {
                    if ( is_object($element) ) { $element = (array) $element; }
                    elseif ( is_array($element) ) { }
                    else { continue; }

                    if ( ! isset($element[$column_key]) ) { continue; }
                    $arr[] = $element[$column_key];
                }
            }
            else
            {
                foreach ($input as $element)
                {
                    if ( is_object($element) ) { $element = (array) $element; }
                    elseif ( is_array($element) ) { }
                    else { continue; }

                    if ( ! isset($element[$column_key]) || ! isset($element[$index_key]) ) { continue; }
                    $arr[$element[$index_key]] = $element[$column_key];
                }
            }
        }

        return $arr;
    }
}

if ( ! function_exists('check_date'))
{
    /**
     * @param string $date A date formatted 'YYYY-MM-DD'.
     * @return bool
     */
    function check_date($date)
    {
        $date = explode('-', $date);
        if ( count($date) !== 3 ) { return FALSE; }

        return checkdate($date[1], $date[2], $date[0]);
    }
}

if ( ! function_exists('nice_date_two'))
{
    /**
     * @param $date
     * @return string
     */
    function nice_date_two($date)
    {
        $months = array(
            1   => 'Ene',
            2   => 'Feb',
            3   => 'Mar',
            4   => 'Abr',
            5   => 'May',
            6   => 'Jun',
            7   => 'Jul',
            8   => 'Ago',
            9   => 'Sep',
            10  => 'Oct',
            11  => 'Nov',
            12  => 'Dic'
        );

        if ( !check_date($date) ) { return ''; }

        $temp = explode('-', $date);
        return "{$temp[2]}/{$months[(int)$temp[1]]}/{$temp[0]}";
    }
}

if ( ! function_exists('get_number_month'))
{
    /**
     * @param $month
     * @return mixed
     */
    function get_number_month($month)
    {
        $months = array(
            'Ene'   =>  1,
            'Feb'   =>  2,
            'Mar'   =>  3,
            'Abr'   =>  4,
            'May'   =>  5,
            'Jun'   =>  6,
            'Jul'   =>  7,
            'Ago'   =>  8,
            'Sep'   =>  9,
            'Oct'   =>  10,
            'Nov'   =>  11,
            'Dic'   =>  12
        );
        return (isset($months[$month])) ? $months[$month] : $month;
    }
}

if ( ! function_exists('short_name'))
{
    /**
     * @param string $first_name
     * @param string $last_name
     * @return string
     */
    function short_name($first_name, $last_name)
    {
        $not_names = array(
            'de',
            'los',
            'la'
        );
        $short_name = '';

        $t = explode(' ', trim($first_name));
        foreach ($t as $word)
        {
            if ( strlen($short_name) > 0 ) { $short_name .= ' '; }
            $short_name .= $word;
            if ( !in_array(strtolower($word), $not_names) ) { break; }
        }

        $t = explode(' ', trim($last_name));
        foreach ($t as $word)
        {
            if ( strlen($short_name) > 0 ) { $short_name .= ' '; }
            $short_name .= $word;
            if ( !in_array(strtolower($word), $not_names) ) { break; }
        }

        return $short_name;
    }
}

if ( ! function_exists('date_difference'))
{
    function date_difference($date_1, $date_2 = NULL)
    {
        if ( $date_1 === NULL || $date_1 === '' || $date_1 === '0000-00-00' ) { return ''; }
        $datetime1 = date_create($date_1);
        $datetime2 = ($date_2 === NULL) ? date_create() : date_create($date_2);
        $interval = date_diff($datetime2, $datetime1);
        $text = '';

        if ( $interval->format('%y') != '0' )
        {
            $text .= $interval->format('%r%yy ');
        }

        if ( $interval->format('%m') != '0' )
        {
            $text .= $interval->format('%r%mm ');
        }

        if ( $interval->format('%d') != '0' )
        {
            $text .= $interval->format('%r%dd ');
        }
        elseif ( $text === '' )
        {
            $text .= $interval->format('%dd ');
        }

        return $text;
    }
}
