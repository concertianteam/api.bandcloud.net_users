<?php

class ClientEcho
{
    /**
     * Echoing json response to client
     *
     * $statusCode Http response code
     * $response Json response
     */
    static function echoResponse($statusCode, $response)
    {
        $app = \Slim\Slim::getInstance();
        // Http response code
        $app->status($statusCode);

        // setting response content type to json
        $app->contentType('application/json');

        echo json_encode($response);
    }

    /*
     * @param $result - result from database
     * @param $type - type of response (from responseTypes.php)
     * @param $bands - array of bands if ($type == EVENT), NULL otherwise
     */
    static function buildResponse($result, $type)
    {
        // looping through result and preparing order array
        if (!count($result) == 0) {
            switch ($type) {
                case EVENT :
                    $response = ClientEcho::buildEventsResponse($result);
                    break;
                case VENUE :
                    $response = ClientEcho::buildVenuesResponse($result);
                    break;
                case CITIES:
                    $response = ClientEcho::buildCitiesResponse($result);
                    break;
                default :
                    $response ["success"] = FALSE;
                    $response ["message"] = "Oops! An error occurred!";
                    break;
            }

            ClientEcho::echoResponse(OK, $response);
        } else {
            echo count($result);
            $response ["success"] = FALSE;
            $response ["message"] = "The requested resource doesn't exists";
            ClientEcho::echoResponse(NOT_FOUND, $response);
        }
    }

    private static function buildEventsResponse($result)
    {
        $response ["success"] = TRUE;
        foreach ($result as $row) {
            $tmp = array();

            $tmp ["id"] = $row ["id"];
            $tmp ["eventName"] = $row ["eventName"];
            $tmp ["date"] = $row ["date"];
            $tmp ["time"] = $row ["time"];
            $tmp ["venueName"] = $row ["venueName"];
            $tmp ["urlPhoto"] = $row ["urlPhoto"];
            $tmp["address"] = $row['address_1'];
            $tmp ["city"] = $row ["city"];
            $tmp ["state"] = $row ["state"];
            $tmp ["zip"] = $row ["zip"];

            $response ['events'] [] = $tmp;
        }
        return $response;
    }

    private static function buildCitiesResponse($result)
    {
        $response ["success"] = TRUE;
        foreach ($result as $row) {
            $response ['cities'] [] = $row ["city"];;
        }
        return $response;
    }


    private static function buildVenuesResponse($result)
    {
        foreach ($result as $row) {
            $tmp = array();

            $tmp ["id"] = $row ["idVenues"];
            $tmp ["name"] = $row ["name"];
            $tmp ["email"] = $row ["email"];
            $tmp ["urlPhoto"] = $row ["urlPhoto"];
            $tmp ["state"] = $row ["state"];
            $tmp ["city"] = $row ["city"];
            $tmp ["zip"] = $row ["zip"];
            $tmp ["address_1"] = $row ["address_1"];
            $tmp ["address_2"] = $row ["address_2"];
            $tmp ["idAccount"] = $row ["idAccount"];

            $response ['venues'] [] = $tmp;

        }
        return $response;
    }
}