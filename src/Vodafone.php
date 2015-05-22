<?php
namespace MarinusJvv\Vodafone;

use MarinusJvv\Vodafone\Exceptions\ImpossiblePathException;

class Vodafone
{
    /**
     * @var array
     */
    private $mappings;

    private $result;

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
        unset($this->result);
        $followedPath = array($from);
        $timeTaken = 0;
        $this->getPathsUsingFrom($from, $to, $maxDuration, $followedPath, $timeTaken);
        if (empty($this->result) === true) {
            throw new ImpossiblePathException();
        }
        return $this->result;
    }

    private function getPathsUsingFrom($from, $to, $maxDuration, &$followedPath, &$timeTaken)
    {
        if (empty($this->result) === false) {
            return;
        }
        foreach ($this->mappings[$from] as $destination => $time) {
            if (in_array($destination, $followedPath) === true) {
                continue;
            }
            if ($destination === $to && $time + $timeTaken <= $maxDuration) {
                $followedPath[] = $destination;
                $timeTaken += $time;
                if (empty($this->result) === true) {
                    $this->result = array(
                        'time' => $timeTaken,
                        'path' => $followedPath,
                    );
                }
                return;
            }
            if (array_key_exists($destination, $this->mappings)) {
                $followedPath[] = $destination;
                $timeTaken += $time;
                $this->getPathsUsingFrom($destination, $to, $maxDuration, $followedPath, $timeTaken);
            }
        }
    }
}
