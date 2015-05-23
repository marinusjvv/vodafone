<?php
namespace MarinusJvv\Vodafone;

use MarinusJvv\Vodafone\Exceptions\ImpossiblePathException;

class PathBuilder
{
    /**
     * @var array Final resulting path
     */
    private $result;

    /**
     * @var array Mappings to be used in determining path
     */
    private $mappings;

    /**
     * @var int Maximum travel time
     */
    private $maxDuration;

    /**
     * Sets default values for use in execution of script.
     *
     * @param array $mappings Mappings to be used in determining path
     * @param int $maxDuration Maximum travel time
     */
    public function __construct($mappings, $maxDuration)
    {
        $this->mappings = $mappings;
        $this->maxDuration = $maxDuration;
    }

    /**
     * Builds path using from and to location.
     *
     * @param string $origin Original location
     * @param string $destination Final destination
     *
     * @throws MarinusJvv\Vodafone\Exceptions\ImpossiblePathException
     * 
     * @return array
     */
    public function build($origin, $destination)
    {
        $followedPath = array($origin);
        $timeTaken = 0;
        $this->getPathsUsingFrom($origin, $destination, $followedPath, $timeTaken);
        $this->checkIfPathHasBeenFound();
        return $this->result;
    }

    /**
     * @param string $from Current starting location
     * @param string $destination Final destination
     * @param array &$followedPath Current path taken
     * @param string &$timeTaken Current amount of time taken
     *
     * @return void
     */
    private function getPathsUsingFrom($from, $destination, &$followedPath, &$timeTaken)
    {
        if ($this->hasPathBeenFoundAlready() === true) {
            return;
        }
        if (array_key_exists($from, $this->mappings) === false) {
            return;
        }
        foreach ($this->mappings[$from] as $to => $time) {
            if ($this->hasDestinationBeenUsedAlready($to, $followedPath) === true) {
                continue;
            }
            if ($this->writeDataIfComplete($to, $destination, $time, $timeTaken, $followedPath) === true) {
                return;
            }
            $this->getPathsFromNextDestination($to, $time, $destination, $followedPath, $timeTaken);
        }
    }

    /*
     * Has the answer already been set?
     * 
     * @return bool
     */
    private function hasPathBeenFoundAlready()
    {
        return empty($this->result) === false;
    }

    /*
     * Has the current destination already been used as an origin?
     *
     * @param string $to Current destination to be checked
     * @param array $followedPath The current path being followed
     * 
     * @return bool
     */
    private function hasDestinationBeenUsedAlready($to, $followedPath)
    {
        return in_array($to, $followedPath) === true;
    }

    /*
     * Writes the data to the result variable if the final destination is reached.
     *
     * @param string $to Current destination to be checked
     * @param string $destination Final destination
     * @param int $time Time from current origin to current destination
     * @param int $timeTaken Current total time from origin
     * @param array $followedPath Current path taken
     * 
     * @return bool
     */
    private function writeDataIfComplete($to, $destination, $time, $timeTaken, $followedPath)
    {
        if ($this->isDestinationFinalAndWithinTimeLimit($to, $destination, $time, $timeTaken) === true) {
            $this->setResultData(
                $timeTaken + $time,
                array_merge($followedPath, array($to))
            );
            return true;
        }
        return false;
    }

    /**
     * Gets paths where current destination is an origin
     *
     * @param string $to Current destination
     * @param integer $time Time from current origin to current destination
     * @param string $destination Final destination
     * @param array &$followedPath Current path taken
     * @param int $timeTaken Current total time from origin
     */
    private function getPathsFromNextDestination($to, $time, $destination, &$followedPath, &$timeTaken)
    {
        if ($this->doesDestinationExistAsOrigin($to) === true) {
            $followedPath[] = $to;
            $timeTaken += $time;
            $this->getPathsUsingFrom($to, $destination, $followedPath, $timeTaken);
        }
    }

    /**
     * Checks to see if we have reached the final destination within the alloted time
     *
     * @param string $to Current destination
     * @param string $destination Final destination
     * @param integer $time Time from current origin to current destination
     * @param int $timeTaken Current total time from origin
     *
     * @return bool
     */
    private function isDestinationFinalAndWithinTimeLimit($to, $destination, $time, $timeTaken)
    {
        return $to === $destination
            && $time + $timeTaken <= $this->maxDuration;
    }

    /**
     * Sets the esulting path and time if it is not yet set
     *
     * @param int $timeTaken Current total time from origin
     * @param array $followedPath Current path taken
     */
    private function setResultData($timeTaken, $followedPath)
    {
        if ($this->hasPathBeenFoundAlready() === false) {
            $this->result = array(
                'time' => $timeTaken,
                'path' => $followedPath,
            );
        }
    }

    /**
     * Checks to see if we have used this destination as an origin before
     *
     * @param string $to Current destination
     *
     * @return bool
     */
    private function doesDestinationExistAsOrigin($to)
    {
        return array_key_exists($to, $this->mappings) === true;
    }

    /**
     * Checks to see if we have found a path to the destination yet
     *
     * @throws MarinusJvv\Vodafone\Exceptions\ImpossiblePathException
     */
    private function checkIfPathHasBeenFound()
    {
        if (empty($this->result) === true) {
            throw new ImpossiblePathException();
        }
    }
}
