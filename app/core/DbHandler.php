<?php

class DbHandler
{
    private $connection;
    private $connectionWc;

    function __construct()
    {
        // open database connection
        $this->connection = Database::getInstance();
        //$this->connectionWc = Database::getWCInstance();
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
        $STH = $this->connection->prepare("SELECT idEvents as id, v.idVenues as venueId, e.name as eventName, date, time,
            e.visible, e.details, e.entry, e.imgUrl, youtubeVideo, v.name as venueName,  v.email as venueEmail,v.urlPhoto, a.address_1,
            a.city, a.state, a.zip
            FROM Events e
            INNER JOIN Venues v
            ON e.idVenue = v.idVenues
            INNER JOIN Address a
            ON a.idAddress = v.idAddress
            WHERE visible = 1
            AND date >= CURDATE()
            ORDER BY date, time
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
        $STH = $this->connection->prepare("SELECT idEvents as id, v.idVenues as venueId, e.name as eventName, date, time,
            e.visible, e.details, e.entry, e.imgUrl, youtubeVideo, v.name as venueName, v.email as venueEmail, v.urlPhoto, a.address_1,
            a.city, a.state, a.zip
            FROM Events e
            INNER JOIN Venues v
            ON e.idVenue = v.idVenues
            INNER JOIN Address a
            ON a.idAddress = v.idAddress
            WHERE visible = 1
            AND v.idVenues = :idVenue
            AND date >= CURDATE()
            ORDER BY date, time
            LIMIT :results
            OFFSET :page;");
        $STH->bindValue(':idVenue', $idVenue);
        $STH->bindValue(':results', $results);
        $STH->bindValue(':page', $offset);
        $STH->execute();
        $events = $STH->fetchAll();

        return $events;
    }

    public function getAllDomainEvents($domain)
    {
        $STH = $this->connection->prepare("SELECT idEvents as id, v.idVenues as venueId, e.name as eventName, date, time,
            e.visible, e.details, e.entry, e.imgUrl, youtubeVideo, v.name as venueName, v.email as venueEmail, v.urlPhoto, a.address_1,
            a.city, a.state, a.zip
            FROM Events e
            INNER JOIN Venues v
            ON e.idVenue = v.idVenues
            INNER JOIN Address a
            ON a.idAddress = v.idAddress
            WHERE visible = 1
            AND v.domain = :domain
            AND date >= CURDATE()
            ORDER BY date, time;");
        $STH->bindValue(':domain', $domain);

        $STH->execute();
        $events = $STH->fetchAll();

        return $events;
    }

    public function getMostViewedEvents($page, $results)
    {
        $offset = $page * $results;
        $STH = $this->connection->prepare("SELECT idEvents as id, e.name as eventName, date, time,e.visible, e.details,
            e.entry, e.imgUrl, v.name as venueName, v.email as venueEmail, v.urlPhoto, a.address_1, a.city, a.state, a.zip
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
        $STH = $this->connection->prepare("SELECT idEvents as id, v.idVenues as venueId, e.name as eventName, date, time,
            e.visible, e.details, e.entry, e.imgUrl, youtubeVideo, v.name as venueName,  v.email as venueEmail, v.urlPhoto, a.address_1,
            a.city, a.state, a.zip
            FROM Events e
            INNER JOIN Venues v
            ON e.idVenue = v.idVenues
            INNER JOIN Address a
            ON a.idAddress = v.idAddress
            WHERE visible = 1
            AND LOWER(a.city) = LOWER(:city)
            AND date >= CURDATE()
            ORDER BY date, time
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
        $STH = $this->connection->prepare("INSERT INTO ViewCounter(idEvent)
				VALUES(:idEvent);");
        $STH->bindParam(':idEvent', $idEvent);
        $STH->execute();

        $STH = $this->connection->prepare("SELECT idEvents as id, v.idVenues as venueId, e.name as eventName, date, time,
            e.visible, e.details, e.entry, e.imgUrl, youtubeVideo, v.name as venueName,  v.email as venueEmail,v.urlPhoto, a.address_1,
            a.city, a.state, a.zip
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

    //WHERE LOWER(name) like LOWER('%Maj%')
    public function getSearchedVenues($name, $page, $results)
    {
        $offset = $page * $results;
        $STH = $this->connection->prepare("SELECT idVenues, name, email, urlPhoto, state, city, zip, address_1, address_2
            FROM Venues
            INNER JOIN Address
            ON Venues.idAddress = Address.idAddress
            WHERE LOWER(name) like LOWER(:namelike)
            ORDER BY name
            LIMIT :results
            OFFSET :page;");
        $STH->bindValue(':namelike', '%' . $name . "%");
        $STH->bindValue(':results', $results);
        $STH->bindValue(':page', $offset);
        $STH->execute();
        $venues = $STH->fetchAll();

        return $venues;

    }

    /*------------------------CARDS-------------------------------*/
    public function getAllCards()
    {
        $STH = $this->connection->prepare("SELECT idCard, name, urlImage FROM Cards;");
        $STH->execute();

        $cards = $STH->fetchAll();
        return $cards;
    }

    public function getCardEventsCount($name)
    {
        $STH = $this->connection->prepare("SELECT COUNT(*) as count
            FROM Events e
            INNER JOIN Venues v
            ON e.idVenue = v.idVenues
            INNER JOIN Address a
            ON a.idAddress = v.idAddress
            WHERE visible = 1
            AND LOWER(a.city) = LOWER(:name)
            AND date >= CURDATE();");

        $STH->bindValue(':name', $name);
        $STH->execute();

        $count = $STH->fetchAll();
        return $count;
    }

    public function getRegisteredVenues()
    {
        $STH = $this->connection->prepare("SELECT COUNT(*) as count
            FROM Venues
            WHERE idAccount IS NOT NULL;");

        $STH->execute();

        $count = $STH->fetchAll();
        return $count[0][0];
    }
}