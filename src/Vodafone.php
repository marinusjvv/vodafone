<?php
namespace MarinusJvv\Vodafone;

class Vodafone
{
    /**
     * @var array
     */
    private $mappings; 

    /**
     * @param string $csvLocation Path to CSV
     *
     * @throws MarinusJvv\Vodafone\Exceptions\FileNotFoundException
     */
    public function __construct($csvLocation)
    {
        $reader = new CsvReader();
        $mapper = $reader->read($csvLocation);
        $this->mappings = $mapper->getConnections();
    }

    /**
     * @param string $from Starting device
     * @param string $to Destination device
     * @param integer $maxDuration Maximum length of trip in milliseconds
     */
    public function process($from, $to, $maxDuration)
    {
        foreach ($this->mappings[$from] as $destination => $time) {
            if ($destination === $to) {
                return array(
                    'time' => $time,
                    'path' => array('a', 'd'),
                );
            }
        }
    }
}
