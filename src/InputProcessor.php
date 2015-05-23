<?php
namespace MarinusJvv\Vodafone;

use MarinusJvv\Vodafone\Exceptions\InvalidInputException;

class InputProcessor
{
    public function recieveInput()
    {
        $stdin = fopen('php://stdin', 'r');
        $response = fgets($stdin);
        $response_data = explode(' ', trim($response));
        $this->validateInput($response_data);
        return array(
            'from' => $response_data[0],
            'to' => $response_data[1],
            'time' => (int)$response_data[2],
        );
    }

    private function validateInput($response_data)
    {
        if (count($response_data) !== 3) {
            throw new InvalidInputException();
        }
    }
}
