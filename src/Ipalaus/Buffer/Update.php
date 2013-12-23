<?php

namespace Ipalaus\Buffer;

use DateTime;
use InvalidArgumentException;

class Update
{

    /**
     * The status update text.
     *
     * @var string
     */
    public $text = null;

    /**
     * An array of profile id’s that the status update should be sent to.
     * Invalid profile_id’s will be silently ignored.
     *
     * @var array
     */
    public $profiles = array();

    /**
     * If shorten is false links within the text will not be automatically
     * shortened, otherwise they will.
     *
     * @var boolean
     */
    public $shorten = true;

    /**
     * If now is set, this update will be sent immediately to all profiles
     * instead of being added to the buffer.
     *
     * @var boolean
     */
    public $now = false;

    /**
     * If top is set, this update will be added to the top of the buffer and
     * will become the next update sent.
     *
     * @var boolean
     */
    public $top = false;

    /**
     * An associative array of media to be attached to the update, currently
     * accepts link, description and picture parameters.
     *
     * @var array
     */
    public $media = array();

    /**
     * A date describing when the update should be posted. Overrides any top or
     * now parameter. When using ISO 8601 format, if no UTC offset is specified,
     * UTC is assumed.
     *
     * @var \DateTime
     */
    public $scheduled_at = null;

    /**
     * Add a social profile to be updated.
     *
     * @param  string  $id
     * @return \Ipalaus\Buffer\Update
     */
    public function addProfile($id)
    {
        $this->profiles[] = $id;

        return $this;
    }

    /**
     * Add media to the update.
     *
     * @param string  $key
     * @param string  $value
     * @return \Ipalaus\Buffer\Update
     */
    public function addMedia($key, $value)
    {
        $available = array('link', 'description', 'picture');

        // accept only valid types for media
        if ( ! in_array($key, $available)) {
            throw new InvalidArgumentException('Media type must be a valid value: '.implode(', ', $available));
        }

        $this->media[$key] = $value;

        return $this;
    }

    /**
     * Schedule a post with a timestamp or a valid DateTime string.
     *
     * @param  mixed $when
     * @return \Ipalaus\Buffer\Update
     */
    public function schedule($when)
    {
        if (is_numeric($when)) {
            $dt = new DateTime;
            $dt->setTimestamp($when);
        } else {
            $dt = new DateTime($when);
        }

        $this->scheduled_at = $dt->format(DateTime::ISO8601);

        return $this;
    }

}
