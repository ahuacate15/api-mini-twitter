<?php
require_once __DIR__.'/../includes/connection.php';
require_once __DIR__.'/../dao/iTweetDao.php';

class MockTweetDao implements iTweetDao {

    public function findAll($idUser) {

        $data = array(
            array(
                'id_tweet' => 1,
                'created_date' => '2020-09-28 20:23:39',
                'message' => 'Relegaron, pues, al creador y maestro al término de suyo un tanto lejano y oscuro fundador sus coruscantes discípulos y continuadores.',
                'id_user' => 1
            ),
            array(
                'id_tweet' => 2,
                'created_date' => '2020-09-28 20:22:39',
                'message' => 'Sea como fuere, la primera traducción al español de Sein und Zeit está aún contagiada del entusiasmo inicial, hecho que se manifiesta particularmente en el prólogo del traductor',
                'id_user' => 1
            ),
            array(
                'id_tweet' => 3,
                'created_date' => '2020-09-28 20:21:39',
                'Después de múltiples ensayos he traducido por realidad de verdad esta palabra, sujeto básico para todo lo que se dice en las obras de Heidegger.',
                'id_user' => 1
            )
        );

        return $data;
    }

    public function findFavorites($idUser) {
        $data = array(
            array(
                'id_tweet' => 1,
                'created_date' => '2020-09-28 20:23:39',
                'message' => 'Relegaron, pues, al creador y maestro al término de suyo un tanto lejano y oscuro fundador sus coruscantes discípulos y continuadores.',
                'id_user' => 1
            )
        );

        return $data;
    }

    public function create($idUser, $message) {

        if($idUser == 1) {
            if(strlen($message) > 256) {
                throw new \Exception("error al ejecutar la consulta", Connection::DATA_TO_LONG);
            }
            else {
                return Connection::OK;
            }
        } else {
            throw new \Exception("error al ejecutar la consulta", Connection::FOREIGN_KEY_FAIL);
        }
    }

    public function findById($idTweet, $idUser) {
        $data = array(
            1 => array(
                'id_tweet' => 1,
                'created_date' => '2020-09-28 20:23:39',
                'message' => 'Relegaron, pues, al creador y maestro al término de suyo un tanto lejano y oscuro fundador sus coruscantes discípulos y continuadores.',
                'id_user' => 1
            )
        );

        return isset($data[$idTweet]) ? $data[$idTweet] : false;
    }

    public function likeTweet($idUser, $idTweet) {

        /* registros existentes */
        if($idUser == 1 && $idTweet == 1) {
            return Connection::OK;
        }
        /* simulo que el usuario ID 1 ya ha dado like al tweet 2 */
        else if($idUser == 1 && $idTweet == 2) {
            throw new \Exception("error al ejecutar la consulta", Connection::DUPLICATE_ROW);
        }
        /* inserto datos inexistentes, se produce un error de clave foarena */
        else {
            throw new \Exception("error al ejecutar la consulta", Connection::FOREIGN_KEY_FAIL);
        }


    }

    public function unlikeTweet($idUser, $idTweet) {
        return Connection::OK;
    }

    public function lastInsertId() {
        return 1;
    }
}
?>
