<?php

class Hitradio {

    private $db;
    private $dateShema = 'Y-m-d H:i:s';
    private $playlistResource = 'http://oe3meta.orf.at/oe3mdata/WebPlayerFiles/PlayList200.json';

    public function __construct() {
        $config = [
            'host' => 'localhost',
            'username' => 'root',
            'password' => '',
            'database' => 'hitradio'
        ];
        try {
            $this->db = new Mysqli($config['host'], $config['username'], $config['password'], $config['database']);
        } catch(Exception $e) {
            Console::write('Can\'t connect to database: ' . $e->getMessage());
        }
        $this->db->set_charset('utf8');
    }


    public function __destruct() {
        $this->db->close();
    }
    
    public function init() {
        Console::newLine();
        Console::line();
        Console::write('Hitradio OE3 "Crawler"');
        Console::line();
        
        try {
            $songList = $this->loadSongList();
        } catch(Exception $e) {
            Console::write('Error: '. $e->getMessage());
            Console::line();
            return;
        }
        Console::write('Songs');
        Console::line();
        
        foreach($songList as $song) {
            if($this->shouldInsertSong($song['artist'], $song['title'], count($songList))) {
                $this->insertSong($song['artist'], $song['title'], $song['cover'], $song['played_at']);
                $this->printSong($song, true);
            } else {
                $this->printSong($song, false);
            }
        }
        Console::newLine();
    }

    private function loadSongList() {
        $document = @file_get_contents($this->playlistResource);
        
        if(!$document) {
            throw new Exception('Failed loading document!');
        }
        $document = json_decode($document);
        $songList = [];
        
        foreach($document as $song) { 
            $song = [
                'artist'    => $song->Artist,
                'title'     => $song->SongName,
                'cover'     => $song->Cover,
                'played_at' => date($this->dateShema, strtotime($song->Time)),
            ];
            $songList[] = $song;
        }
        return $songList;
    }

    private function shouldInsertSong($artist, $title, $historyLookup = 10) {
        $query = 'SELECT id FROM (SELECT * FROM tracks LIMIT ?) as tmp WHERE artist=? AND title=?';
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('iss', $historyLookup, $artist, $title);

        if(!$stmt->execute()) {
            throw new Exception($stmt->error);
        }
        $result = $stmt->get_result();
        
        if($result->num_rows > 0) {
            return false;
        }
        return true;
    }

    private function insertSong($artist, $title, $cover, $played_at) { 
        $created_at = date($this->dateShema);
        $query = 'INSERT INTO tracks (artist, title, cover, played_at, created_at) VALUES (?, ?, ?, ?, ?)';
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('sssss', $artist, $title, $cover, $played_at, $created_at);
        
        if(!$stmt->execute()) {
            throw new Exception($stmt->error);
        }
        if($stmt->affected_rows > 0) {
            return true;
        }
        throw new Exception('Insert failed');
    }

    private function printSong($song, $inserted = null) {
        Console::write('artist:    ' . $song['artist']);
        Console::write('title:     ' . $song['title']);
        Console::write('played at: ' . $song['played_at']);

        if($inserted == true) {
            Console::write('status:    inserted');
        } elseif($inserted == false) {
            Console::write('status:    already in database');
        }
        Console::line();
    }
}