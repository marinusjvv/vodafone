<?php
namespace MarinusJvv\Vodafone;

use MarinusJvv\Vodafone\Exceptions\FileNotFoundException;

class CsvReader
{
    /**
     * @param string $path
     *
     * @throws MarinusJvv\Vodafone\Exceptions\FileNotFoundException
     *
     * @return MarinusJvv\Vodafone\Mapper
     */
    public function read($path)
    {
        return $this->mapConnections($this->getHandle($path));
    }

    /**
     * @param string $path
     *
     * @throws MarinusJvv\Vodafone\Exceptions\FileNotFoundException
     *
     * @return resource
     */
    private function getHandle($path)
    {
        @$handle = fopen($path, 'r');
        if ($handle === false) {
            throw new FileNotFoundException();
        }
        return $handle;
    }

    /**
     * @param resource $handle
     *
     * @return MarinusJvv\Vodafone\Mapper
     */
    private function mapConnections($handle)
    {
        $mapper = new Mapper();
        while (($connection = fgetcsv($handle)) !== FALSE) {
            $mapper->mapConnection($connection);
        }
        return $mapper;
    }
}
