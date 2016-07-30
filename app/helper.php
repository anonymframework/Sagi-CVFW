<?php


function url($url)
{
    global $configs;

    return $configs['uri'] . '/' . $url;
}