<?php
namespace MarinusJvv\Vodafone;

use MarinusJvv\Vodafone\Exceptions\ImpossiblePathException;

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
        $followedPath = array($from);
        $timeTaken = 0;
        $success = false;
        $this->getPathsUsingFrom($from, $to, $maxDuration, $followedPath, $timeTaken, $success);
        if ($success !== true) {
            throw new ImpossiblePathException();
        }
        return array(
            'time' => $timeTaken,
            'path' => $followedPath,
        );
    }

    private function getPathsUsingFrom($from, $to, $maxDuration, &$followedPath, &$timeTaken, &$success)
    {
        foreach ($this->mappings[$from] as $destination => $time) {
            if ($destination === $to && $time + $timeTaken <= $maxDuration) {
                $followedPath[] = $destination;
                $timeTaken += $time;
                $success = true;
                return;
            }
            if (array_key_exists($destination, $this->mappings)) {
                $followedPath[] = $destination;
                $timeTaken += $time;
                $this->getPathsUsingFrom($destination, $to, $maxDuration, $followedPath, $timeTaken, $success);
            }
        }
    }
}
