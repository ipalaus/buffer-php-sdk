<?php

use Mockery as m;
use Ipalaus\Buffer\Client;
use Ipalaus\Buffer\Update;
use Ipalaus\Buffer\Schedule;
use Guzzle\Http\Message\Response;
use Guzzle\Plugin\Mock\MockPlugin;
use Ipalaus\Buffer\TokenAuthorization;

class ClientTest extends PHPUnit_Framework_TestCase
{

    protected $url = 'https://api.bufferapp.com/1/';

    public function testGetHttp()
    {
        $client = new Client(new TokenAuthorization('ipalaus'));

        $method = $this->getMethod('getHttp');
        $return = $method->invokeArgs($client, array());

        $this->assertInstanceOf('Guzzle\Http\Client', $return);
    }

    public function testGetUser()
    {
        $guzzle = $this->getGuzzle($plugin = $this->getPlugin(new Response(200)));

        $client = $this->getClient();
        $client->expects($this->once())->method('getHttp')->will($this->returnValue($guzzle));
        $client->getUser();

        $request = $plugin->getReceivedRequests();

        $this->assertEquals('GET', $request[0]->getMethod());
        $this->assertEquals($this->url.'user.json', $request[0]->getUrl());
    }

    public function testGetProfiles()
    {
        $guzzle = $this->getGuzzle($plugin = $this->getPlugin(new Response(200)));

        $client = $this->getClient();
        $client->expects($this->once())->method('getHttp')->will($this->returnValue($guzzle));
        $client->getProfiles();

        $request = $plugin->getReceivedRequests();

        $this->assertEquals('GET', $request[0]->getMethod());
        $this->assertEquals($this->url.'profiles.json', $request[0]->getUrl());
    }

    public function testGetProfile()
    {
        $guzzle = $this->getGuzzle($plugin = $this->getPlugin(new Response(200)));

        $client = $this->getClient();
        $client->expects($this->once())->method('getHttp')->will($this->returnValue($guzzle));
        $client->getProfile('ipalaus');

        $request = $plugin->getReceivedRequests();

        $this->assertEquals('GET', $request[0]->getMethod());
        $this->assertEquals($this->url.'profiles/ipalaus.json', $request[0]->getUrl());
    }

    public function testGetProfileSchedules()
    {
        $guzzle = $this->getGuzzle($plugin = $this->getPlugin(new Response(200)));

        $client = $this->getClient();
        $client->expects($this->once())->method('getHttp')->will($this->returnValue($guzzle));
        $client->getProfileSchedules('ipalaus');

        $request = $plugin->getReceivedRequests();

        $this->assertEquals('GET', $request[0]->getMethod());
        $this->assertEquals($this->url.'profiles/ipalaus/schedules.json', $request[0]->getUrl());
    }

    public function testUpdateProfileSchedules()
    {
        $guzzle = $this->getGuzzle($plugin = $this->getPlugin(new Response(200)));

        $client = $this->getClient();
        $client->expects($this->once())->method('getHttp')->will($this->returnValue($guzzle));
        $client->updateProfileSchedules('ipalaus', new Schedule(array('mon'), array('09:00')));

        $request = $plugin->getReceivedRequests();

        $this->assertEquals('POST', $request[0]->getMethod());
        $this->assertEquals($this->url.'profiles/ipalaus/schedules/update.json', $request[0]->getUrl());
    }

    /**
     * @expectedException Exception
     */
    public function testUpdateProfileSchedulesAsArrayThrowsException()
    {
        $client = $this->getClient();
        $client->updateProfileSchedules('ipalaus', array(new Schedule, new Schedule));
    }

    public function testGetUpdate()
    {
        $guzzle = $this->getGuzzle($plugin = $this->getPlugin(new Response(200)));

        $client = $this->getClient();
        $client->expects($this->once())->method('getHttp')->will($this->returnValue($guzzle));
        $client->getUpdate('ipalaus');

        $request = $plugin->getReceivedRequests();

        $this->assertEquals('GET', $request[0]->getMethod());
        $this->assertEquals($this->url.'updates/ipalaus.json', $request[0]->getUrl());
    }

    public function testGetProfilePendingUpdates()
    {
        $guzzle = $this->getGuzzle($plugin = $this->getPlugin(new Response(200)));

        $client = $this->getClient();
        $client->expects($this->once())->method('getHttp')->will($this->returnValue($guzzle));
        $client->getProfilePendingUpdates('ipalaus');

        $request = $plugin->getReceivedRequests();

        $this->assertEquals('GET', $request[0]->getMethod());
        $this->assertEquals($this->url.'profiles/ipalaus/updates/pending.json', $request[0]->getUrl());

        $guzzle = $this->getGuzzle($plugin = $this->getPlugin(new Response(200)));

        $client = $this->getClient();
        $client->expects($this->once())->method('getHttp')->will($this->returnValue($guzzle));
        $client->getProfilePendingUpdates('ipalaus', 1, 1, 2013, true);

        $request = $plugin->getReceivedRequests();

        $this->assertEquals('GET', $request[0]->getMethod());
        $this->assertEquals($this->url.'profiles/ipalaus/updates/pending.json?page=1&count=1&since=2013&utc=1', $request[0]->getUrl());
    }

    public function testGetProfileSentUpdates()
    {
        $guzzle = $this->getGuzzle($plugin = $this->getPlugin(new Response(200)));

        $client = $this->getClient();
        $client->expects($this->once())->method('getHttp')->will($this->returnValue($guzzle));
        $client->getProfileSentUpdates('ipalaus');

        $request = $plugin->getReceivedRequests();

        $this->assertEquals('GET', $request[0]->getMethod());
        $this->assertEquals($this->url.'profiles/ipalaus/updates/sent.json', $request[0]->getUrl());

        $guzzle = $this->getGuzzle($plugin = $this->getPlugin(new Response(200)));

        $client = $this->getClient();
        $client->expects($this->once())->method('getHttp')->will($this->returnValue($guzzle));
        $client->getProfileSentUpdates('ipalaus', 1, 1, 2013, true);

        $request = $plugin->getReceivedRequests();

        $this->assertEquals('GET', $request[0]->getMethod());
        $this->assertEquals($this->url.'profiles/ipalaus/updates/sent.json?page=1&count=1&since=2013&utc=1', $request[0]->getUrl());
    }

    public function testGetUpdateInteractions()
    {
        $guzzle = $this->getGuzzle($plugin = $this->getPlugin(new Response(200)));

        $client = $this->getClient();
        $client->expects($this->once())->method('getHttp')->will($this->returnValue($guzzle));
        $client->getUpdateInteractions('ipalaus');

        $request = $plugin->getReceivedRequests();

        $this->assertEquals('GET', $request[0]->getMethod());
        $this->assertEquals($this->url.'updates/ipalaus/interactions.json', $request[0]->getUrl());

        $guzzle = $this->getGuzzle($plugin = $this->getPlugin(new Response(200)));

        $client = $this->getClient();
        $client->expects($this->once())->method('getHttp')->will($this->returnValue($guzzle));
        $client->getUpdateInteractions('ipalaus', 1, 1, 'shares');

        $request = $plugin->getReceivedRequests();

        $this->assertEquals('GET', $request[0]->getMethod());
        $this->assertEquals($this->url.'updates/ipalaus/interactions.json?page=1&count=1&event=shares', $request[0]->getUrl());
    }

    public function testReorderProfileUpdates()
    {
        $guzzle = $this->getGuzzle($plugin = $this->getPlugin(new Response(200)));

        $client = $this->getClient();
        $client->expects($this->once())->method('getHttp')->will($this->returnValue($guzzle));
        $client->reorderProfileUpdates('ipalaus', 'lorem');

        $request = $plugin->getReceivedRequests();

        $this->assertEquals('POST', $request[0]->getMethod());
        $this->assertEquals($this->url.'profiles/ipalaus/updates/reorder.json', $request[0]->getUrl());

        $guzzle = $this->getGuzzle($plugin = $this->getPlugin(new Response(200)));

        $client = $this->getClient();
        $client->expects($this->once())->method('getHttp')->will($this->returnValue($guzzle));
        $client->reorderProfileUpdates('ipalaus', array('lorem', 'ipsum'), 1, true);

        $request = $plugin->getReceivedRequests();

        $this->assertEquals('POST', $request[0]->getMethod());
        $this->assertEquals($this->url.'profiles/ipalaus/updates/reorder.json', $request[0]->getUrl());

    }

    public function testShuffleProfileUpdates()
    {
        $guzzle = $this->getGuzzle($plugin = $this->getPlugin(new Response(200)));

        $client = $this->getClient();
        $client->expects($this->once())->method('getHttp')->will($this->returnValue($guzzle));
        $client->shuffleProfileUpdates('ipalaus');

        $request = $plugin->getReceivedRequests();

        $this->assertEquals('POST', $request[0]->getMethod());
        $this->assertEquals($this->url.'profiles/ipalaus/updates/shuffle.json', $request[0]->getUrl());

        $guzzle = $this->getGuzzle($plugin = $this->getPlugin(new Response(200)));

        $client = $this->getClient();
        $client->expects($this->once())->method('getHttp')->will($this->returnValue($guzzle));
        $client->shuffleProfileUpdates('ipalaus', 1, true);

        $request = $plugin->getReceivedRequests();

        $this->assertEquals('POST', $request[0]->getMethod());
        $this->assertEquals($this->url.'profiles/ipalaus/updates/shuffle.json', $request[0]->getUrl());
    }

    public function testCreateUpdate()
    {
        $guzzle = $this->getGuzzle($plugin = $this->getPlugin(new Response(200)));

        $update = new Update();
        $update->text = 'Lorem ipsum';
        $update->addProfile('ipalaus');
        $update->addMedia('link', 'http://ipalaus.com');
        $update->schedule(time() + 3600); // you can use timestamp

        $client = $this->getClient();
        $client->expects($this->once())->method('getHttp')->will($this->returnValue($guzzle));

        $client->createUpdate($update);

        $request = $plugin->getReceivedRequests();

        $this->assertEquals('POST', $request[0]->getMethod());
        $this->assertEquals($this->url.'updates/create.json', $request[0]->getUrl());
    }

    public function testUpdateUpdate()
    {
        $guzzle = $this->getGuzzle($plugin = $this->getPlugin(new Response(200)));

        $update = new Update();
        $update->text = 'Lorem ipsum';
        $update->addMedia('link', 'http://ipalaus.com');
        $update->schedule(time() + 3600); // you can use timestamp

        $client = $this->getClient();
        $client->expects($this->once())->method('getHttp')->will($this->returnValue($guzzle));

        $client->updateUpdate('ipalaus', $update);

        $request = $plugin->getReceivedRequests();

        $this->assertEquals('POST', $request[0]->getMethod());
        $this->assertEquals($this->url.'updates/ipalaus/update.json', $request[0]->getUrl());
    }

    public function testShareUpdate()
    {
        $guzzle = $this->getGuzzle($plugin = $this->getPlugin(new Response(200)));

        $client = $this->getClient();
        $client->expects($this->once())->method('getHttp')->will($this->returnValue($guzzle));

        $client->shareUpdate('ipalaus');

        $request = $plugin->getReceivedRequests();

        $this->assertEquals('POST', $request[0]->getMethod());
        $this->assertEquals($this->url.'updates/ipalaus/share.json', $request[0]->getUrl());
    }

    public function testDestroyUpdate()
    {
        $guzzle = $this->getGuzzle($plugin = $this->getPlugin(new Response(200)));

        $client = $this->getClient();
        $client->expects($this->once())->method('getHttp')->will($this->returnValue($guzzle));

        $client->destroyUpdate('ipalaus');

        $request = $plugin->getReceivedRequests();

        $this->assertEquals('POST', $request[0]->getMethod());
        $this->assertEquals($this->url.'updates/ipalaus/destroy.json', $request[0]->getUrl());
    }

    public function testMoveUpdateToTopUpdate()
    {
        $guzzle = $this->getGuzzle($plugin = $this->getPlugin(new Response(200)));

        $client = $this->getClient();
        $client->expects($this->once())->method('getHttp')->will($this->returnValue($guzzle));

        $client->moveUpdateToTop('ipalaus');

        $request = $plugin->getReceivedRequests();

        $this->assertEquals('POST', $request[0]->getMethod());
        $this->assertEquals($this->url.'updates/ipalaus/move_to_top.json', $request[0]->getUrl());
    }

    public function testGetLinkShares()
    {
        $guzzle = $this->getGuzzle($plugin = $this->getPlugin(new Response(200)));

        $client = $this->getClient();
        $client->expects($this->once())->method('getHttp')->will($this->returnValue($guzzle));
        $client->getLinkShares('http://ipalaus.com');

        $request = $plugin->getReceivedRequests();

        $this->assertEquals('GET', $request[0]->getMethod());
        $this->assertEquals($this->url.'links/shares.json?url=http%3A%2F%2Fipalaus.com', $request[0]->getUrl());
    }

    public function testGetConfiguration()
    {
        $guzzle = $this->getGuzzle($plugin = $this->getPlugin(new Response(200)));

        $client = $this->getClient();
        $client->expects($this->once())->method('getHttp')->will($this->returnValue($guzzle));
        $client->getConfigurationInfo();

        $request = $plugin->getReceivedRequests();

        $this->assertEquals('GET', $request[0]->getMethod());
        $this->assertEquals($this->url.'info/configuration.json', $request[0]->getUrl());
    }

    protected function getClient()
    {
        $client = $this->getMock('Ipalaus\Buffer\Client', array('getHttp'), array(new TokenAuthorization('ipalaus')));

        return $client;
    }

    protected function getGuzzle($plugin = null)
    {
        $client = new Guzzle\Http\Client();

        if ($plugin) {
            $client->addSubscriber($plugin);
        }

        return $client;
    }

    protected function getPlugin($responses)
    {
        $plugin = new MockPlugin();

        if (is_array($responses)) {
            foreach ($responses as $response) {
                $plugin->addResponse($response);
            }
        } else {
            $plugin->addResponse($responses);
        }

        return $plugin;
    }

    protected function getMethod($name) {
        $class = new ReflectionClass('Ipalaus\Buffer\Client');
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }

}
