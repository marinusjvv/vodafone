<?php
namespace MarinusJvv\Vodafone;

class Vodafone
{
    /**
     * @var array Default mappings set by CSV
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
     *
     * @throws MarinusJvv\Vodafone\Exceptions\ImpossiblePathException
     */
    public function process($from, $to, $maxDuration)
    {
        $builder = new PathBuilder($this->mappings, $maxDuration);
        return $builder->build($from, $to);
    }
}
