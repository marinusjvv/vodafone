<?php
use MarinusJvv\Vodafone\Vodafone;

class VodafoneTest extends PHPUnit_Framework_TestCase
{
    public function testProcessGivenPathGetsCorrectPath()
    {
        $vodafone = new Vodafone(dirname(__FILE__) . '/data/example.csv');
        $expected = array('a' => 0, 'b' => 10, 'd' => 100);
        $this->assertEquals($expected, $vodafone->process('a', 'd', 110));
    }

    public function testProcessGivenComplexPathsGetsCorrectPath()
    {
        $vodafone = new Vodafone(dirname(__FILE__) . '/data/complex_example.csv');
        $expected = array('A' => 0, 'C' => 20, 'D' => 30, 'E' => 10, 'F' => 1000);
        $this->assertEquals($expected, $vodafone->process('A', 'F', 1060));
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