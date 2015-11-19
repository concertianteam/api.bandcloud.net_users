<?php

class DbHandler
{
    private $connection;
    private $connectionWc;

    function __construct()
    {
        // open database connection
        $this->connection = Database::getInstance();
        $this->connectionWc = Database::getWCInstance();
    }

    function __destruct()
    {
        $this->connection = null;
        $this->connectionWc = null;
    }

    /* -------------------------------Events------------------------------------------ */
    public function getAllEvents($page, $results)
    {
        $offset = $page * $results;
        $STH = $this->connection->prepare("SELECT idEvents as id, v.idVenues as venueId, e.name as eventName, date, time, e.visible,
            v.name as venueName,  v.email as venueEmail,v.urlPhoto, a.address_1, a.city, a.state, a.zip
            FROM Events e
            INNER JOIN Venues v
            ON e.idVenue = v.idVenues
            INNER JOIN Address a
            ON a.idAddress = v.idAddress
            WHERE visible = 1
            AND date >= CURDATE()
            ORDER BY date
            LIMIT :results
            OFFSET :page;");
        $STH->bindValue(':results', $results);
        $STH->bindValue(':page', $offset);
        $STH->execute();
        $events = $STH->fetchAll();

        return $events;
    }

    public function getAllVenueEvents($idVenue, $page, $results)
    {
        $offset = $page * $results;
        $STH = $this->connection->prepare("SELECT idEvents as id, v.idVenues as venueId, e.name as eventName, date, time, e.visible,
            v.name as venueName, v.email as venueEmail, v.urlPhoto, a.address_1, a.city, a.state, a.zip
            FROM Events e
            INNER JOIN Venues v
            ON e.idVenue = v.idVenues
            INNER JOIN Address a
            ON a.idAddress = v.idAddress
            WHERE visible = 1
            AND v.idVenues = :idVenue
            AND date >= CURDATE()
            ORDER BY date
            LIMIT :results
            OFFSET :page;");
        $STH->bindValue(':idVenue', $idVenue);
        $STH->bindValue(':results', $results);
        $STH->bindValue(':page', $offset);
        $STH->execute();
        $events = $STH->fetchAll();

        return $events;
    }

    public function getMostViewedEvents($page, $results)
    {
        $offset = $page * $results;
        $STH = $this->connection->prepare("SELECT idEvents as id, e.name as eventName, date, time,e.visible, v.name as venueName,
            v.email as venueEmail, v.urlPhoto, a.address_1, a.city, a.state, a.zip
            FROM Events e
            INNER JOIN Venues v
            ON e.idVenue = v.idVenues
            INNER JOIN Address a
            ON a.idAddress = v.idAddress
            WHERE visible = 1
            ORDER BY (SELECT COUNT(*) FROM ViewCounter WHERE e.idEvents = idEvent) desc
            LIMIT :results
            OFFSET :page;");
        $STH->bindValue(':results', $results);
        $STH->bindValue(':page', $offset);
        $STH->execute();
        $events = $STH->fetchAll();

        return $events;
    }

    public function getCityEvents($city, $page, $results)
    {
        $offset = $page * $results;
        $STH = $this->connection->prepare("SELECT idEvents as id, v.idVenues as venueId, e.name as eventName, date, time,e.visible,
            v.name as venueName,  v.email as venueEmail, v.urlPhoto, a.address_1, a.city, a.state, a.zip
            FROM Events e
            INNER JOIN Venues v
            ON e.idVenue = v.idVenues
            INNER JOIN Address a
            ON a.idAddress = v.idAddress
            WHERE visible = 1
            AND LOWER(a.city) = LOWER(:city)
            AND date >= CURDATE()
            ORDER BY date
            LIMIT :results
            OFFSET :page;");
        $STH->bindValue(':city', $city);
        $STH->bindValue(':results', $results);
        $STH->bindValue(':page', $offset);
        $STH->execute();
        $events = $STH->fetchAll();

        return $events;
    }

    public function getCities()
    {
        $STH = $this->connection->prepare("SELECT DISTINCT city FROM Address;");
        $STH->execute();
        $cities = $STH->fetchAll();
        return $cities;
    }

    public function getSingleEvent($idEvent)
    {
        $STH = $this->connection->prepare("SELECT idEvents as id, e.name as eventName, date, time, v.name as venueName,e.visible,
            v.email as venueEmail, v.urlPhoto, a.address_1, a.city, a.state, a.zip
            FROM Events e
            INNER JOIN Venues v
            ON e.idVenue = v.idVenues
            INNER JOIN Address a
            ON a.idAddress = v.idAddress
            WHERE e.idEvents = :idEvent");
        $STH->bindParam(':idEvent', $idEvent);
        $STH->execute();

        $event = $STH->fetchAll();
        //}
        return $event;
    }

    /* -------------------------------VENUES------------------------------------------ */
    public function getAllVenues($page, $results)
    {
        $offset = $page * $results;
        $STH = $this->connection->prepare("SELECT idVenues, name, email, urlPhoto, state, city, zip, address_1, address_2
            FROM Venues
            INNER JOIN Address
            ON Venues.idAddress = Address.idAddress
            ORDER BY name
            LIMIT :results
            OFFSET :page;");
        $STH->bindValue(':results', $results);
        $STH->bindValue(':page', $offset);
        $STH->execute();
        $venues = $STH->fetchAll();

        return $venues;
    }

    public function getSingleVenue($idVenue)
    {
        $STH = $this->connection->prepare("SELECT idVenues, name, email, urlPhoto, state, city, zip, address_1, address_2
                FROM Venues INNER JOIN Address
				ON Venues.idAddress = Address.idAddress
				WHERE idVenues = :idVenue;");
        $STH->bindParam(':idVenue', $idVenue);
        $STH->execute();

        $venue = $STH->fetchAll();

        return $venue;
    }
}