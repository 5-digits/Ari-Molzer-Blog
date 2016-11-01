<?php
/**
 * Created by PhpStorm.
 * User: arimolzer
 * Date: 21/10/2016
 * Time: 1:55 PM
 */

namespace AppBundle\Util;

class StringHelper
{
    /**
     * converts 'blog title's of the day'
     * into 'blog-titles-of-the-day'
     *
     * @param $string
     * @return string
     */
    static function stringToUrl($string)
    {
        // validation based off - http://stackoverflow.com/questions/11330480/
        // lower case everything
        $string = strtolower($string);
        // make alphanumeric (removes all other characters)
        $string = preg_replace('/[^a-z0-9_\s-]/', "", $string);
        // clean up multiple dashes or whitespaces
        $string = preg_replace('/[\s-]+/', " ", $string);
        // convert whitespaces and underscore to dash
        $string = preg_replace('/[\s_]/', "-", $string);

        return $string;
    }
}