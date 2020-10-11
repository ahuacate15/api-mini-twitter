<?php

interface iTweetDao {

    /**
    * @param idUser verifico que el usuario del parametro haya dado like al tweet
    */
    public function findAll($idUser);

    /**
    * @param idUser verifico que el usuario del parametro haya dado like al tweet
    */
    public function findFavorites($idUser);

    public function create($idUser, $message);

    /**
    * @param idUser verifico que el usuario del parametro haya dado like al tweet
    */
    public function findById($idTweet, $idUser);
    public function likeTweet($idUser, $idTweet);
    public function unlikeTweet($idUser, $idTweet);
}

?>
