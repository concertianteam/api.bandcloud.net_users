<?php


class TicketsSoapHandler
{

    static function getTickets($idEvent)
    {
        $client = TicketsSoapClient::getInstance();
        try {
            return $client->getTicketsUser($idEvent);
        } catch (SoapFault $e) {
            echo $client->__getLastResponse();
        }
    }

}