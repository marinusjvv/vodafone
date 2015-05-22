<?php
use MarinusJvv\Vodafone\CsvReader;

class CsvReaderTest extends PHPUnit_Framework_TestCase
{
    public function testReadGivenPathCorrectlyMapsConnections()
    {
        $reader = new CsvReader();
        $path = dirname(__FILE__) . '/data/example.csv';
        $mapper = $reader->read($path);

        $expected = array(
            'a' => array(
                'b' => 10,
                'c' => 20,
            ),
            'b' => array(
                'd' => 100,
            ),
        );
        $this->assertEquals($expected, $mapper->getConnections());
    }

    public function testReadGivenInvalidPathThrowsException()
    {
        $this->setExpectedException('MarinusJvv\Vodafone\Exceptions\FileNotFoundException');
        $reader = new CsvReader();
        $path = '/not/a/valid/path';
        $mapper = $reader->read($path);
    }
}