<?php

if(!function_exists('clean_text')){
    function clean_text($string, $removeSpace = true): string
    {
        if($removeSpace)
            $regex = '/[^A-Za-z0-9\-]/';
        else
            $regex = '/[^A-Za-z0-9 \-]/';
        $string = preg_replace($regex, '', $string);
        return preg_replace('/-+/', '-', $string);
    }
}
