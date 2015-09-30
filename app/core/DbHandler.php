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
        $STH = $this->connection->prepare("SELECT idEvents as id, e.name as eventName, date, time, v.name as venueName, v.urlPhoto, a.address_1, a.city, a.state, a.zip
            FROM Events e
            INNER JOIN Venues v
            ON e.idVenue = v.idVenues
            INNER JOIN Address a
            ON a.idAddress = v.idAddress
            WHERE visible = 1
            ORDER BY date
            LIMIT :results
            OFFSET :page;");
        $STH->bindValue(':results', $results);
        $STH->bindValue(':page', $offset);
        $STH->execute();
        $events = $STH->fetchAll();

        /* doèasné dáta z webcravlera
        $STH = $this->connectionWc->prepare("Select CONCAT('w', idEvent) as id, eventName, dateTime, venueName, urlPhoto,
         city, state FROM Concerts  LIMIT :results OFFSET :page;");
        $STH->bindParam(':results', $results);
        $STH->bindParam(':page', $offset);
        $STH->execute();
        $wc = $STH->fetchAll();
        foreach ($wc as $row) {
            $events[] = $row;
        }*/

        return $events;
    }


    public function getMostViewedEvents($page, $results)
    {
        $offset = $page * $results;
        $STH = $this->connection->prepare("SELECT idEvents as id, e.name as eventName, date, time, v.name as venueName, v.urlPhoto, a.address_1, a.city, a.state, a.zip
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

        /* doèasné dáta z webcravlera
        $STH = $this->connectionWc->prepare("Select CONCAT('w', idEvent) as id, eventName, dateTime, venueName, urlPhoto, city, state
            FROM Concerts c
            ORDER BY (SELECT COUNT(*) FROM ViewCounter WHERE c.idEvent = idEvent) desc
            LIMIT :results
            OFFSET :page;");
        $STH->bindParam(':results', $results);
        $STH->bindParam(':page', $offset);
        $STH->execute();
        $wc = $STH->fetchAll();
        foreach ($wc as $row) {
            $events[] = $row;
        }*/

        return $events;
    }

    public function getCityEvents($city, $page, $results)
    {
        $offset = $page * $results;
        $STH = $this->connection->prepare("SELECT idEvents as id, e.name as eventName, date, time, v.name as venueName, v.urlPhoto, a.address_1, a.city, a.state, a.zip
            FROM Events e
            INNER JOIN Venues v
            ON e.idVenue = v.idVenues
            INNER JOIN Address a
            ON a.idAddress = v.idAddress
            WHERE visible = 1
            AND LOWER(city) = LOWER(:city)
            LIMIT :results
            OFFSET :page;");
        $STH->bindValue(':city', $city);
        $STH->bindValue(':results', $results);
        $STH->bindValue(':page', $offset);
        $STH->execute();
        $events = $STH->fetchAll();

        /* doèasné dáta z webcravlera
        $STH = $this->connectionWc->prepare("Select CONCAT('w', idEvent) as id, eventName, dateTime, venueName, urlPhoto, city, state
            FROM Concerts c
            WHERE LOWER(city) = LOWER(:city)
            LIMIT :results
            OFFSET :page;");
        $STH->bindValue(':city', $city);
        $STH->bindParam(':results', $results);
        $STH->bindParam(':page', $offset);
        $STH->execute();
        $wc = $STH->fetchAll();
        foreach ($wc as $row) {
            $events[] = $row;
        }*/

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
        /*if ($idEvent[0] == 'w') {
            $STH = $this->connectionWc->prepare("Select CONCAT('w', idEvent) as id, eventName, dateTime, venueName, urlPhoto,
              city, state FROM Concerts
            WHERE idEvent = :idEvent");
            $id = trim($idEvent, 'w');
            $STH->bindParam(':idEvent', $id);
            $STH->execute();

            $event = $STH->fetchAll();
        } else {*/
        $STH = $this->connection->prepare("SELECT idEvents as id, e.name as eventName, date, time, v.name as venueName, v.urlPhoto, a.address_1, a.city, a.state, a.zip
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
    /* -------------------------------Events------------------------------------------ */
}