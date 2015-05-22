<?php
use MarinusJvv\Vodafone\Vodafone;

class VodafoneTest extends PHPUnit_Framework_TestCase
{
    public function testProcessGivenSimplePathGetsCorrectPath()
    {
        $vodafone = new Vodafone(dirname(__FILE__) . '/data/example.csv');
        $expected = array(
            'time' => 1000,
            'path' => array('a', 'd'),
        );
        $this->assertEquals($expected, $vodafone->process('a', 'd', 1000));
    }

    public function testProcessGivenComplexPathGetsCorrectPath()
    {
        $this->markTestIncomplete();

        $vodafone = new Vodafone(dirname(__FILE__) . '/data/example.csv');
        $expected = array(
            'time' => 110,
            'path' => 'a => b => d',
        );
        $this->assertEquals($expected, $vodafone->process('a', 'd', 110));
    }

    public function testProcessGivenPathToShortTimeThrowsException()
    {
        $this->markTestIncomplete();
        $this->setExpectedException('NotSureOfTheNameYet');

        $vodafone = new Vodafone(dirname(__FILE__) . '/data/example.csv');
        $vodafone->process('a', 'd', 1);
    }

    public function testProcessGivenImpossiblePathThrowsException()
    {
        $this->markTestIncomplete();
        $this->setExpectedException('NotSureOfTheNameYet');

        $vodafone = new Vodafone(dirname(__FILE__) . '/data/example.csv');
        $vodafone->process('a', 'not possible', 100);
    }
}