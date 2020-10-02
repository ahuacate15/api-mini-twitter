<?php
require_once __DIR__.'/../includes/responseHTTP.php';
require_once __DIR__.'/../includes/connection.php';
require_once __DIR__.'/iTweetDao.php';

class TweetDao extends Connection implements iTweetDao  {

    public function __construct() {
        parent::__construct();
    }

    public function findAll() {
        $sql =
            "select ".
            "   t.id_tweet, t.created_date, t.message, t.id_user, u.user_name, ".
            "   (select count(0) from tweet_like _tl where _tl.id_user = u.id_user and _tl.id_tweet = t.id_tweet) as count_likes ".
            "from tweet t ".
            "inner join user u on u.id_user = t.id_user ".
            "order by created_date desc";
        $this->setQuery($sql);
        return $this->fetchAll();
    }
}
?>
