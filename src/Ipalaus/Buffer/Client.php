<?php

namespace Ipalaus\Buffer;

use Exception;
use Guzzle\Http\Message\Request;

class Client
{

    /**
     * Current version of the SDK
     *
     * @var string
     */
    const VERSION = '1.1.1';

    /**
     * Endpoint base URL.
     *
     * @var string
     */
    protected $url = 'https://api.bufferapp.com/1/';

    /**
     * Authorization instance.
     *
     * @var \Ipalaus\Buffer\AuthorizationInterface
     */
    protected $auth;

    /**
     * Create a new instance.
     *
     * @param  \Ipalaus\Buffer\AuthorizationInterface  $auth
     * @return void
     */
    public function __construct(AuthorizationInterface $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Returns a single user.
     *
     * @return array
     */
    public function getUser()
    {
        return $this->send($this->getHttp()->get($this->url.'user.json'));
    }

    /**
     * Returns an array of social media profiles connected to a users account.
     *
     * @return array
     */
    public function getProfiles()
    {
        return $this->send($this->getHttp()->get($this->url.'profiles.json'));
    }

    /**
     * Returns details of the single specified social media profile.
     *
     * @param  string  $id
     * @return array
     */
    public function getProfile($id)
    {
        return $this->send($this->getHttp()->get($this->url.'profiles/'.$id.'.json'));
    }

    /**
     * Returns details of the posting schedules associated with a social media profile.
     *
     * @param  string  $id
     * @return array
     */
    public function getProfileSchedules($id)
    {
        return $this->send($this->getHttp()->get($this->url.'profiles/'.$id.'/schedules.json'));
    }

    /**
     * Set the posting schedules for the specified social media profile.
     *
     * @param  string  $id
     * @param  mixed   $schedules
     * @return array
     */
    public function updateProfileSchedules($id, $schedules)
    {
        $payload = $this->buildProfileSchedulesPayload($schedules);

        return $this->send($this->getHttp()->post($this->url.'profiles/'.$id.'/schedules/update.json', null, $payload));
    }

    /**
     * Given an array of Schedule objects, build the necessary payload to
     * update the profile schedules.
     *
     * @param  mixed  $schedules
     * @return array
     */
    protected function buildProfileSchedulesPayload($schedules)
    {
        if (is_array($schedules)) {
            throw new Exception('Multiple schedules update are not implemented.');
        }

        $payload = array('schedules' => array());

        $payload['schedules'][] = array(
            'days'  => $schedules->getDays(),
            'times' => $schedules->getTimes(),
        );

        return $payload;
    }

    /**
     * Returns a single social media update.
     *
     * @param  string  $id
     * @return array
     */
    public function getUpdate($id)
    {
        return $this->send($this->getHttp()->get($this->url.'updates/'.$id.'.json'));
    }

    /**
     * Returns an array of updates that are currently in the buffer for an
     * individual social media profile.
     *
     * @param  string   $id
     * @param  integer  $page
     * @param  integer  $count
     * @param  integer  $since
     * @param  bool     $utc
     * @return array
     */
    public function getProfilePendingUpdates($id, $page = null, $count = null, $since = null, $utc = false)
    {
        $payload = $this->buildProfileUpdatesPayload(compact('page', 'count', 'since', 'utc'));

        return $this->send($this->getHttp()->get($this->url.'profiles/'.$id.'/updates/pending.json?'.$payload));
    }

    /**
     * Returns an array of updates that have been sent from the buffer for an
     * individual social media profile.
     *
     * @param  string   $id
     * @param  integer  $page
     * @param  integer  $count
     * @param  integer  $since
     * @param  bool     $utc
     * @return array
     */
    public function getProfileSentUpdates($id, $page = null, $count = null, $since = null, $utc = false)
    {
        $payload = $this->buildProfileUpdatesPayload(compact('page', 'count', 'since', 'utc'));

        return $this->send($this->getHttp()->get($this->url.'profiles/'.$id.'/updates/sent.json?'.$payload));
    }

    /**
     * Returns the detailed information on individual interactions with the
     * social media update such as favorites, retweets and likes.
     *
     * @param  string   $id
     * @param  integer  $page
     * @param  integer  $count
     * @param  string   $event
     * @return array
     */
    public function getUpdateInteractions($id, $page = null, $count = null, $event = null)
    {
        $payload = $this->buildProfileUpdatesPayload(compact('page', 'count', 'event'));

        return $this->send($this->getHttp()->get($this->url.'updates/'.$id.'/interactions.json?'.$payload));
    }

    /**
     * Generic querystring generator for some of the Updates methods.
     *
     * @param  array  $data
     * @return string
     */
    protected function buildProfileUpdatesPayload($data)
    {
        $payload = array();

        if (isset($data['page']) and is_numeric($data['page'])) {
            $payload['page'] = $data['page'];
        }

        if (isset($data['count']) and is_numeric($data['count'])) {
            $payload['count'] = $data['count'];
        }

        if (isset($data['since']) and is_numeric($data['since'])) {
            $payload['since'] = $data['since'];
        }

        if (isset($data['utc']) and $data['utc']) {
            $payload['utc'] = $data['utc'];
        }

        if (isset($data['event'])) {
            $available = array(
                'retweet', 'retweets', 'favorite', 'favorites', 'like', 'likes', 'comment', 'comments',
                'mention', 'mentions', 'share', 'shares',
            );

            if (in_array($data['event'], $available)) {
                $payload['event'] = $data['event'];
            }
        }

        return (count($payload) > 0) ? http_build_query($payload) : '';
    }

    /**
     * Edit the order at which statuses for the specified social media profile
     * will be sent out of the buffer.
     *
     * @param  string        $id
     * @param  array|string  $order
     * @param  integer       $offset
     * @param  boolean       $utc
     * @return array
     */
    public function reorderProfileUpdates($id, $order, $offset = null, $utc = false)
    {
        $payload = $this->buildReorderProfileUpdatesPayload((array) $order, $offset, $utc);

        return $this->send($this->getHttp()->post($this->url.'profiles/'.$id.'/updates/reorder.json', null, $payload));
    }

    /**
     * Build the payload for reorderProfileUpdates.
     *
     * @param  array    $order
     * @param  integer  $offset
     * @param  boolean  $utc
     * @return array
     */
    protected function buildReorderProfileUpdatesPayload($order, $offset, $utc)
    {
        $payload = array();

        $payload['order'] = $order;

        if (is_numeric($offset)) {
            $payload['offset'] = $offset;
        }

        if ($utc) {
            $payload['utc'] = true;
        }

        return $payload;
    }

    /**
     * Randomize the order at which statuses for the specified social media
     * profile will be sent out of the buffer.
     *
     * @param  string   $id
     * @param  integer  $count
     * @param  boolean  $utc
     * @return array
     */
    public function shuffleProfileUpdates($id, $count = null, $utc = false)
    {
        $payload = $this->buildShuffleProfileUpdatesPayload($count, $utc);

        return $this->send($this->getHttp()->post($this->url.'profiles/'.$id.'/updates/shuffle.json', null, $payload));
    }

    /**
     * Build the payload for shuffleProfileUpdates.
     *
     * @param  integer  $count
     * @param  boolean  $utc
     * @return array
     */
    protected function buildShuffleProfileUpdatesPayload($count, $utc)
    {
        $payload = array();

        if (is_numeric($count)) {
            $payload['count'] = $count;
        }

        if ($utc) {
            $payload['utc'] = true;
        }

        return $payload;
    }

    /**
     * Create one or more new status updates.
     *
     * @param  \Ipalaus\Buffer\Update  $update
     * @return array
     */
    public function createUpdate(Update $update)
    {
        $payload = $this->buildCreateUpdatePayload($update);

        return $this->send($this->getHttp()->post($this->url.'updates/create.json', null, $payload));
    }

    /**
     * Build the payload for creeateUpdate.
     *
     * @param  \Ipalaus\Buffer\Update  $update
     * @return array
     */
    protected function buildCreateUpdatePayload(Update $update)
    {
        $payload = array(
            'text' => $update->text,
            'profile_ids' => $update->profiles,
            'shorten' => $update->shorten,
            'now' => $update->now,
            'top' => $update->top,
        );

        if ( ! empty($update->media)) {
            $payload['media'] = $update->media;
        }

        if ( ! is_null($update->scheduled_at)) {
            $payload['scheduled_at'] = $update->scheduled_at;
        }

        return $payload;
    }

    /**
     * Edit an existing, individual status update.
     *
     * @param  string                  $id
     * @param  \Ipalaus\Buffer\Update  $update
     * @return array
     */
    public function updateUpdate($id, Update $update)
    {
        $payload = $this->buildUpdateUpdatePayload($update);

        return $this->send($this->getHttp()->post($this->url.'updates/'.$id.'/update.json', null, $payload));
    }

    /**
     * Build the payload for updateUpdate.
     *
     * @param  \Ipalaus\Buffer\Update  $update
     * @return array
     */
    protected function buildUpdateUpdatePayload(Update $update)
    {
        $payload = array(
            'text' => $update->text,
            'now' => $update->now,
        );

        if ( ! empty($update->media)) {
            $payload['media'] = $update->media;
        }

        if ( ! is_null($update->scheduled_at)) {
            $payload['scheduled_at'] = $update->scheduled_at;
        }

        return $payload;
    }

    /**
     * Immediately shares a single pending update and recalculates times for
     * updates remaining in the queue.
     *
     * @param  string  $id
     * @return array
     */
    public function shareUpdate($id)
    {
        return $this->send($this->getHttp()->post($this->url.'updates/'.$id.'/share.json'));
    }

    /**
     * Permanently delete an existing status update.
     *
     * @param  string  $id
     * @return array
     */
    public function destroyUpdate($id)
    {
        return $this->send($this->getHttp()->post($this->url.'updates/'.$id.'/destroy.json'));
    }

    /**
     * Move an existing status update to the top of the queue and recalculate
     * times for all updates in the queue. Returns the update with its new
     * posting time.
     *
     * @param  string  $id
     * @return array
     */
    public function moveUpdateToTop($id)
    {
        return $this->send($this->getHttp()->post($this->url.'updates/'.$id.'/move_to_top.json'));
    }

    /**
     * Returns an object with a the numbers of shares a link has had using
     * Buffer.
     *
     * @param  string  $url
     * @return array
     */
    public function getLinkShares($url)
    {
        $payload = http_build_query(array('url' => $url));

        return $this->send($this->getHttp()->get($this->url.'links/shares.json?'.$payload));
    }

    /**
     * Returns an object with the current configuration that Buffer is using,
     * including supported services, their icons and the varying limits of
     * character and schedules.
     *
     * The services keys map directly to those on profiles and updates so that
     * you can easily show the correct icon or calculate the correct character
     * length for an update.
     *
     * @return array
     */
    public function getConfigurationInfo()
    {
        return $this->send($this->getHttp()->get($this->url.'info/configuration.json'));
    }

    /**
     * Send an authorized request and the response as an array.
     *
     * @param  \Guzzle\Http\Message\Request  $request
     * @return array
     */
    protected function send(Request $request)
    {
        $request = $this->auth->addCredentialsToRequest($request);

        return $request->send()->json();
    }

    /**
     * Create a new Guzzle HTTP Client instance.
     *
     * @return \Guzzle\Http\Client
     */
    protected function getHttp()
    {
        return new \Guzzle\Http\Client;
    }

}
