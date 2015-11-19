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
            $tmp ["venueId"] = $row ["venueId"];
            $tmp ["eventName"] = $row ["eventName"];
            $tmp ["date"] = $row ["date"];
            $tmp ["stringDate"] = ClientEcho::formatDate($row["date"]);
            $tmp ["time"] = $row ["time"];
            $tmp ["visible"] = $row ["visible"];
            $tmp ["venueName"] = $row ["venueName"];
            $tmp ["venueEmail"] = $row["venueEmail"];
            $tmp ["urlPhoto"] = $row ["urlPhoto"];
            $tmp ["address"] = $row['address_1'];
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
        $response ["success"] = TRUE;
        foreach ($result as $row) {
            $tmp = array();

            $tmp ["id"] = $row ["idVenues"];
            $tmp ["venueName"] = $row ["name"];
            $tmp ["email"] = $row ["email"];
            $tmp ["urlPhoto"] = $row ["urlPhoto"];
            $tmp ["state"] = $row ["state"];
            $tmp ["city"] = $row ["city"];
            $tmp ["zip"] = $row ["zip"];
            $tmp ["addressFirst"] = $row ["address_1"];
            $tmp ["addressSecond"] = $row ["address_2"];

            $response ['venues'] [] = $tmp;

        }
        return $response;
    }

    private static function formatDate($date)
    {
        $dateParts = explode("-", $date);
        return $dateParts[0] . "-" . ClientEcho::monthNumberToName($dateParts[1]) . "-" . $dateParts[2];
    }

    private static function monthNumberToName($month)
    {
        switch ($month) {
            case 1:
                return JANUARY;
            case 2:
                return FEBRUARY;
            case 3:
                return MARCH;
            case 4:
                return APRIL;
            case 5:
                return MAY;
            case 6:
                return JUNE;
            case 7:
                return JULY;
            case 8:
                return AUGUST;
            case 9:
                return SEPTEMBER;
            case 10:
                return OCTOBER;
            case 11:
                return NOVEMBER;
            case 12:
                return DECEMBER;

        }
    }
}