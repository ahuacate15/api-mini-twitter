<?php

interface iTweetDao {

    public function findAll();
    public function create($idUser, $message);
    public function findById($idTweet);
}

?>
