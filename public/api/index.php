<?php

define('APP_PATH', realpath('..') . '/');

// Use Loader() to autoload our model
$loader = new \Phalcon\Loader();

$loader->registerDirs(array(
    __DIR__ . '/models/'
))->register();

$di = new \Phalcon\DI\FactoryDefault();

$config = new Phalcon\Config\Adapter\Ini(APP_PATH . '../app/config/config.ini');

$di->set('db', function() use ($config) {
	return new \Phalcon\Db\Adapter\Pdo\Mysql(array(
		"host"     => $config->database->host,
		"username" => $config->database->username,
		"password" => $config->database->password,
		"dbname"   => $config->database->name
	));
});


$app = new \Phalcon\Mvc\Micro($di);

// 1)  Запись новой точки для велосипеда по key  http://invo.local/api/routes/new/555/30.1234/35.2234
$app->get('/routes/new/{key:[a-zA-Z0-9]+}/{lat:[0-9\.]+}/{long:[0-9\.]+}', function($key,$lat,$long) use ($app) {
    
    $date = time();
    $track_id = 0;

    $phql = "INSERT INTO Routes (key, latitude, longitude, date, track_id) VALUES (:key:, :latitude:, :longitude:, :date:, :track_id:)";
    $status = $app->modelsManager->executeQuery($phql, array(
        'key'   =>  $key ,
        'latitude'   =>  $lat ,
        'longitude'  =>  $long,
        'date'  => $date,
        'track_id' => $track_id,
    ));
    
    if ($status->success() == true){
        echo json_encode(array('status' => 1));
    }else{
        $errors = array();
        foreach ($status->getMessages() as $message) {
            $errors[] = $message->getMessage();
        }
        
        echo json_encode(array('status' => 0, 'errors' => $errors));
    }
});

// 2) Выборка последнего местоположения велосипеда по key  http://invo.local/api/routes/getlast/555
$app->get('/routes/getlast/{key:[a-zA-Z0-9]+}', function($key) use ($app) {
    
    $phql = "SELECT * FROM Routes WHERE key = :key: ORDER BY date DESC LIMIT 1";
    
    $route = $app->modelsManager->executeQuery($phql, array(
        'key' =>  $key 
    )) -> getFirst();
    
    echo json_encode($route->toArray());
    
});

// 3) Выборка точек для велосипеда по key и datestart dateend  http://invo.local/api/routes/bike/555/1416304799/1416341383
$app->get('/routes/bike/{key:[a-zA-Z0-9]+}/{datestart:[0-9]{10}}/{dateend:[0-9]{10}}', function($key, $datestart, $dateend) use ($app) {
    
    $phql = "SELECT * FROM Routes WHERE key = :key: AND date > :datestart: AND date < :dateend: ORDER BY date";
    
    $route = $app->modelsManager->executeQuery($phql, array(
        'key' =>  $key ,
        'datestart' =>  $datestart ,
        'dateend' =>  $dateend ,
    ));
    
    echo json_encode($route->toArray());
    
});


// 4) Выборка всех точек для заданного трека для завершенного маршрута http://invo.local/api/routes/track/33
$app->get('/routes/track/{id:[0-9]+}', function($id) use ($app) {
    
    $phql = "SELECT * FROM Routes WHERE track_id = :id: ORDER BY date ASC";
    
    $route = $app->modelsManager->executeQuery($phql, array(
        'id' =>  $id 
    ));
    
    echo json_encode($route->toArray());
    
});



$app->handle();