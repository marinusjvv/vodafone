<?php 

require_once dirname(__FILE__) . '/vendor/autoload.php';

use MarinusJvv\Vodafone\Vodafone;

$index = new index();
$index->begin(@$argv[1]);

class index
{
    public function begin($location)
    {
        $this->checkLocationHasBeenEntered($location);
        try {
            $vodafone = new Vodafone($location);
        } catch (MarinusJvv\Vodafone\Exceptions\FileNotFoundException $e) {
            exit('Please ensure that csv file exists and the script has permission to access it');
        }
        echo "Please enter desired to and from device, as well as time limit. Format should be [Device From] [Device To] [Time]\n\n";
        
        while (true) {
            echo "Input: \n";
            $stdin = fopen('php://stdin', 'r');
            $response = fgets($stdin);
            $response_data = explode(' ', trim($response));
            if (count($response_data) !== 3) {
               echo "Invalid input\n";
            } else {
                try {
                    $returned = $vodafone->process($response_data[0], $response_data[1], (int)$response_data[2]);
                } catch (MarinusJvv\Vodafone\Exceptions\ImpossiblePathException $e) {
                    echo "Path not found\n";
                    continue;
                }
                foreach ($returned['path'] as $device) {
                    echo $device . ' => ';
                }
                echo $returned['time'] . "\n";
            }
        }
    }

    private function checkLocationHasBeenEntered($location)
    {
        if (empty($location) === true) {
            exit('Please include the location of the CSV as a parameter');
        }
    }
}