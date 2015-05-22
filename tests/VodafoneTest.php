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

    public function testProcessGivenComplexPathsGetsCorrectPath()
    {
        $vodafone = new Vodafone(dirname(__FILE__) . '/data/complex_example.csv');
        $expected = array(
            'time' => 1120,
            'path' => array('A', 'B', 'D', 'E', 'F'),
        );
        $this->assertEquals($expected, $vodafone->process('A', 'F', 1200));
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