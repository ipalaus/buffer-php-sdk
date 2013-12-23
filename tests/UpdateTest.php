<?php

use Ipalaus\Buffer\Update;

class UpdateTest extends PHPUnit_Framework_TestCase
{

    public function testAddProfile()
    {
        $update = new Update;
        $update->addProfile('ipalaus');

        $this->assertEquals(array('ipalaus'), $update->profiles);
    }

    public function testAddMedia()
    {
        $update = new Update;
        $update->addMedia('link', 'http://ipalaus.com');

        $this->assertEquals(array('link' => 'http://ipalaus.com'), $update->media);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidMediaTypeThrowsException()
    {
        $update = new Update;
        $update->addMedia('ipalaus', 'test');
    }

    public function testSchedule()
    {
        // timestamp
        $now = time();
        $dt1 = new DateTime;
        $dt1->setTimestamp($now);
        $dt1 = $dt1->format(DateTime::ISO8601);

        $update1 = new Update;
        $update1->schedule($now);

        // string
        $string = '2013-12-23 02:00:00';
        $dt2 = new DateTime($string);
        $dt2 = $dt2->format(DateTime::ISO8601);

        $update2 = new Update;
        $update2->schedule($string);

        $this->assertEquals($dt1, $update1->scheduled_at);
        $this->assertEquals($dt2, $update2->scheduled_at);
    }

}
