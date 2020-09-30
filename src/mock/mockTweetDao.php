<?php
require_once __DIR__.'/../includes/connection.php';
require_once __DIR__.'/../dao/iTweetDao.php';

class MockTweetDao implements iTweetDao {

    public function findAll() {

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
}
?>
