<?php
namespace MarinusJvv\Vodafone;

use MarinusJvv\Vodafone\Exceptions\InvalidConnectionException;

class Mapper
{
    /**
     * @var array Array containing all the original connections.
     *
     * The format of the array looks like this:
     *  array(
     *      origin => array(
     *          destination => travel_time,
     *          destination2 => travel_time,
     *      ),
     *      origin2 => array(
     *          destination => travel_time,
     *      ),
     *  )
     */
    private $connections = array();

    /**
     * @param array $connection
     */
    public function mapConnection($connection)
    {
        try {
            $this->validateConnection($connection);
        } catch (InvalidConnectionException $e) {
            return;
        }
        
        $this->connections[$connection[0]][$connection[1]] = (int)$connection[2];
    }

    private function validateConnection($connection)
    {
        if (count($connection) !== 3) {
            throw new InvalidConnectionException();
        }
        if (ctype_alnum($connection[0]) === false || ctype_alnum($connection[1]) === false) {
            throw new InvalidConnectionException();
        }
        if ((int)$connection[2] <= 0) {
            throw new InvalidConnectionException();
        }
    }

    /**
     * @return array
     */
    public function getConnections()
    {
        return $this->connections;
    }
}
