<?php
require_once __DIR__.'/../includes/responseHTTP.php';
require_once __DIR__.'/../includes/connection.php';
require_once __DIR__.'/iTweetDao.php';

class TweetDao extends Connection implements iTweetDao  {

    public function __construct() {
        parent::__construct();
    }

    public function findAll($idUser) {
        $sql =
            "select ".
            "   t.id_tweet, t.created_date, t.message, t.id_user, u.user_name, ".
            "   count(tl.id_tweet_like) as count_likes, ". //cantidad de likes del tweet
            "   count(tl.id_tweet_like) > 0 as my_like, ". //verifico si he dado like a este tweet
            "   count(my_user.id_user) > 0 as my_tweet ". //verifico si soy el autor del tweet
            "from tweet t ".
            "inner join user u on u.id_user = t.id_user ".
            "left join tweet_like tl on tl.id_tweet = t.id_tweet and tl.id_user = u.id_user ".
            "left join tweet_like my_tl on  my_tl.id_tweet = t.id_tweet  and my_tl.id_user = :idUser ".
            "left join user my_user on my_user.id_user = u.id_user and u.id_user = :idUser ".
            "group by t.id_tweet, t.created_date, t.message, t.id_user, u.user_name ".
            "order by t.created_date desc ";

        $this->setQuery($sql);
        $this->setInteger('idUser', $idUser);

        return $this->fetchAll();
    }

    public function findFavorites($idUser) {
        $sql =
            "select ".
            "   t.id_tweet, t.created_date, t.message, t.id_user, u.user_name, ".
            "   count(tl.id_tweet_like) as count_likes, ". //cantidad de likes del tweet
            "   count(tl.id_tweet_like) > 0 as my_like, ". //verifico si he dado like a este tweet
            "   true as my_tweet ". //indico que soy el autor del tweet
            "from tweet t ".
            "inner join user u on u.id_user = t.id_user ".
            "left join tweet_like tl on tl.id_tweet = t.id_tweet and tl.id_user = u.id_user ".
            "inner join tweet_like my_tl on  my_tl.id_tweet = t.id_tweet  and my_tl.id_user = :idUser ".
            "group by t.id_tweet, t.created_date, t.message, t.id_user, u.user_name ".
            "order by t.created_date desc ";

        $this->setQuery($sql);
        $this->setInteger('idUser', $idUser);

        return $this->fetchAll();
    }

    public function create($idUser, $message) {
        $sql =
            "insert into tweet(id_user, message, created_date) ".
            "values (:idUser, :message, now())";

        $this->setQuery($sql);
        $this->setInteger('idUser', $idUser);
        $this->setString('message', $message);

        $code = $this->execute();

        if($code == Connection::OK) {
            return $code;
        } else {
            throw new \Exception("error al ejecutar la consulta", $code);

        }
    }

    public function findById($idTweet, $idUser) {
        $sql =
            "select ".
            "   t.id_tweet, t.created_date, t.message, t.id_user, u.user_name, ".
            "   count(tl.id_tweet_like) as count_likes, ". //cantidad de likes del tweet
            "   count(tl.id_tweet_like) > 0 as my_like ". //verifico si he dado like a este tweet
            "from tweet t ".
            "inner join user u on u.id_user = t.id_user ".
            "left join tweet_like tl on tl.id_tweet = t.id_tweet and tl.id_user = u.id_user ".
            "left join tweet_like my_tl on  my_tl.id_tweet = t.id_tweet  and my_tl.id_user = :idUser ".
            "where t.id_tweet = :idTweet";

        $this->setQuery($sql);
        $this->setInteger('idTweet', $idTweet);
        $this->setInteger('idUser', $idUser);

        $data = $this->fetch();

        if(!$data) {
            throw new \Exception("registro no encontrado", Connection::DATA_NOT_FOUND);
        }
        return $this->fetch();
    }

    public function likeTweet($idUser, $idTweet) {
        $sql = "insert into tweet_like(id_user, id_tweet) values (:idUser, :idTweet)";
        $this->setQuery($sql);
        $this->setInteger('idUser', $idUser);
        $this->setInteger('idTweet', $idTweet);

        $code = $this->execute();

        if($code == Connection::OK) {
            return $code;
        } else {
            throw new \Exception("error al ejecutar la consulta", $code);
        }
    }

    public function unlikeTweet($idUser, $idTweet) {
        $sql = "delete from tweet_like where id_user = :idUser and id_tweet = :idTweet";
        $this->setQuery($sql);
        $this->setInteger('idUser', $idUser);
        $this->setInteger('idTweet', $idTweet);

        $code = $this->execute();

        if($code == Connection::OK) {
            return $code;
        } else {
            throw new \Exception("error al ejecutar la consulta", $code);
        }
    }

    public function delete($idTweet) {
        $sql = "delete from tweet where id_tweet = :idTweet";
        $this->setQuery($sql);
        $this->setInteger('idTweet', $idTweet);
        $code = $this->execute();

        if($code == Connection::OK) {
            return $code;
        } else {
            throw new \Exception("error al ejecutar la consulta", $code);
        }
    }
}
?>
