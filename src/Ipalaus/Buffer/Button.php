<?php

namespace Ipalaus\Buffer;

use InvalidArgumentException;

class Button
{

    /**
     * Button count styles.
     *
     * @var array
     */
    public static $styles = array('vertical', 'horizontal', 'none');

    /**
     * Generates a Buffer Button to let people share your content on Twitter and
     * Facebook seamlessly. They can share right away or at a better time using
     * Buffer.
     *
     * @param  string  $style
     * @param  string  $tweet
     * @param  string  $url
     * @param  string  $username
     * @param  string  $picture
     * @return string
     */
    public static function create($style, $tweet = null, $url = null, $username = null, $picture = null)
    {
        if ( ! in_array($style, static::$styles)) {
            throw new InvalidArgumentException("Button style [{$style}] not supported.");
        }

        $anchor = '<a href="http://bufferapp.com/add" class="buffer-add-button" data-count="' . $style . '"';

        if ( ! is_null($tweet)) {
            $anchor .= ' data-text="' . htmlspecialchars($tweet) . '"';
        }

        if ( ! is_null($url)) {
            $anchor .= ' data-url="' . urlencode($url) . '"';
        }

        if ( ! is_null($username)) {
            $anchor .= ' data-via="' . htmlspecialchars($username) . '"';
        }

        if ( ! is_null($picture)) {
            $anchor .= ' data-picture="' . urlencode($picture) . '"';
        }

        $anchor .= '>Buffer</a>';

        $script = '<script type="text/javascript" src="//static.bufferapp.com/js/button.js"></script>';

        return $anchor.$script;
    }

    /**
     * Handle dynamic static method calls.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        array_unshift($parameters, $method);

        return call_user_func_array(array(__CLASS__, 'create'), $parameters);
    }

}
