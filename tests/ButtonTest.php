<?php

use Ipalaus\Buffer\Button;

class ButtonTest extends PHPUnit_Framework_TestCase
{

    public function testCreateMethodReturnsAValidButton()
    {
        $button = Button::create('vertical');

        $string = '<a href="http://bufferapp.com/add" class="buffer-add-button" data-count="vertical">Buffer</a><script type="text/javascript" src="http://static.bufferapp.com/js/button.js"></script>';

        $this->assertEquals($button, $string);
    }

    public function testCreateMethodStyles()
    {
        $vertical = Button::create('vertical');
        $horizontal = Button::create('horizontal');
        $none = Button::create('none');

        $this->assertContains('data-count="vertical"', $vertical);
        $this->assertContains('data-count="horizontal"', $horizontal);
        $this->assertContains('data-count="none"', $none);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testCreateMethodWithInvalidStyleThrowsException()
    {
        Button::create('ipalaus');
    }

    public function testCreateMethodOptionalParameters()
    {
        $tweet = Button::create('none', 'Isern Palaus');
        $url = Button::create('none', null, 'http://ipalaus.com');
        $username = Button::create('none', null, null, 'ipalaus');
        $image = Button::create('none', null, null, null, 'http://ipalaus.com');

        $this->assertContains('data-text="Isern Palaus"', $tweet);
        $this->assertContains('data-url="http%3A%2F%2Fipalaus.com"', $url);
        $this->assertContains('data-via="ipalaus', $username);
        $this->assertContains('data-picture="http%3A%2F%2Fipalaus.com"', $image);
    }

    public function testMagicCallStatic()
    {
        $none = Button::none();

        $this->assertContains('data-count="none"', $none);
    }

}
