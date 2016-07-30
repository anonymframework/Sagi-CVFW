<?php


function url($url)
{
    global $configs;
    return $configs['uri'] . '/' . $url;
}

/**
 * @param $file
 * @return string
 */
function assets($file)
{
    return url('assets/' . $file);
}