<?php

use Ipalaus\Buffer\Schedule;

class ScheduleTest extends PHPUnit_Framework_TestCase
{

    public function testAddDaysAndAddTime()
    {
        $schedule = new Schedule;
        $schedule->addDay('mon');
        $schedule->addDay(array('tue', 'wed'));
        $schedule->addtime('00:01');
        $schedule->addTime(array('00:02', '00:03'));

        $this->assertEquals(array('mon', 'tue', 'wed'), $schedule->getDays());
        $this->assertEquals(array('00:01', '00:02', '00:03'), $schedule->getTimes());
    }

    public function testAddDaysAndAddTimeInConstructor()
    {
        $schedule = new Schedule('mon', '00:01');

        $this->assertEquals(array('mon'), $schedule->getDays());
        $this->assertEquals(array('00:01'), $schedule->getTimes());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidDayNameThrowsException()
    {
        $schedule = new Schedule();
        $schedule->addDay('ipalaus');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidTimeFormatThrowsException()
    {
        $schedule = new Schedule();
        $schedule->addTime('ipalaus');
    }

}
