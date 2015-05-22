<?php
namespace MarinusJvv\Vodafone;

class Vodafone
{
    /**
     * @var array
     *
     * @throws MarinusJvv\Vodafone\Exceptions\FileNotFoundException
     */
    private $mappings; 

    public function __construct($path)
    {
        $reader = new CsvReader();
        $mapper = $reader->read($path);
        $this->mappings = $mapper->getConnections();
    }

    public function process()
    {
        return $this->mappings;
    }
}
