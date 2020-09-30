<?php
require_once __DIR__.'/../includes/responseHTTP.php';
require_once __DIR__.'/../includes/connection.php';
require_once __DIR__.'/iTweetDao.php';

class TweetDao extends Connection implements iTweetDao  {

    public function __construct() {
        parent::__construct();
    }

    public function findAll() {
        $sql = "select id_tweet, created_date, message, id_user from tweet order by created_date desc";
        $this->setQuery($sql);
        return $this->fetchAll();
    }
}
?>
