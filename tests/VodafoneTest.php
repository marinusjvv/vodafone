<?php
use MarinusJvv\Vodafone\Vodafone;

class VodafoneTest extends PHPUnit_Framework_TestCase
{
    public function testProcessGivenPathGetsCorrectPath()
    {
        $vodafone = new Vodafone(dirname(__FILE__) . '/data/example.csv');
        $expected = array(
            'time' => 110,
            'path' => array('a', 'b', 'd'),
        );
        $this->assertEquals($expected, $vodafone->process('a', 'd', 110));
    }

    public function testProcessGivenPathToShortTimeThrowsException()
    {
        $this->setExpectedException('MarinusJvv\Vodafone\Exceptions\ImpossiblePathException');
        $vodafone = new Vodafone(dirname(__FILE__) . '/data/example.csv');
        $vodafone->process('a', 'd', 1);
    }

    public function testProcessGivenImpossiblePathThrowsException()
    {
        $this->setExpectedException('MarinusJvv\Vodafone\Exceptions\ImpossiblePathException');
        $vodafone = new Vodafone(dirname(__FILE__) . '/data/example.csv');
        $vodafone->process('a', 'not possible', 100);
    }
}