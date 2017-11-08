<?php 
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
require '../vendor/autoload.php';

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$config['db']['host'] = "localhost";
$config['db']['user'] = "root";
$config['db']['pass'] = "";
$config['db']['dbname'] = "dm107pos";

$app = new \Slim\App(["config" => $config]);
$container = $app->getContainer();

// $dsn = "mysql:dbname=dm107pos;host=127.0.0.1";
// $pdo = new PDO($dsn, "root", "");
// $library = new NotORM($pdo);


$container['db'] = function ($c) {
    $dbConfig = $c['config']['db'];
    $pdo = new PDO("mysql:host=" . $dbConfig['host'] . ";dbname=" .
    $dbConfig['dbname'],
     $dbConfig['user'], $dbConfig['pass']);
     $pdo->setAttribute(PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION);
     $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,
    PDO::FETCH_ASSOC);
     $db = new NotORM($pdo);
     return $db;
};
    
$app->get('/produtos/{id}', function(Request $request, Response $response){
    echo $request->getAttribute('id');
});

$app->get('/api/{nome}', function (Request $request, Response $response) {
    $nome = $request->getAttribute('nome');
    $response->getBody()->write("Bem vindo a API, $nome");
    return $response;
});

$app->post('/produtos', function (Request $request, Response $response) {
    $nome = $request->getAttribute('nome');
    $dsn = "mysql:dbname=dm107pos;host=127.0.0.1";
    $pdo = new PDO($dsn, "root", "");
    $db = new NotORM($pdo);
    $entregas = $db->entrega()->where('id', 4);
    $data = array(
        "num_pedido" => "999999999",
        "recebedor_nome" => "Seba",
        "recebedor_cpf" => "098765432",
        "client_id" => "EWEQE12312",
        "data_entrega" => "10/11/2007",
    );
    $result = $entregas->insert($data);
    $code = 201; 
    $response->withStatus(409)->getBody()->write('Entrega criada com sucesso!'); 
    // echo "id: " . $result["id"] . " num do pedido:" . $result["num_pedido"] . "<br>";
    return $response;
});

$app->put('/produtos/{id}', function(Request $request, Response $response){
    $dsn = "mysql:dbname=dm107pos;host=127.0.0.1";
    $pdo = new PDO($dsn, "root", "");
    $db = new NotORM($pdo);

    // $entregas = $db-> $entrega()->where('id', 2);

    $entregas = $db->entrega()->where('id', 1);
    // $entrega = $db->entregas()->insert(array(
    //     "num_pedido" => "Estudar NotORM",
    //     "recebedor_nome" => "Seba",
    //     "recebedor_cpf" => "Estudar operações SQL",
    //     "client_id" => "Estudar operações SQL",
    //     "data_entrega" => "10/11/2007",
    // ));
    foreach ($entregas as $entrega) {
        $data = array(
            "num_pedido" => "999999999",
            "recebedor_nome" => "Seba",
            "recebedor_cpf" => "098765432",
            "client_id" => "EWEQE12312",
            "data_entrega" => "10/11/2007",
        );
        $result = $entregas->update($data);

        
    }
    return $response->withStatus(200)->getBody()->write('Entrega editada com sucesso!'); 
});

$app->delete('/produto/{id}', function(Request $request, Response $response){
    $dsn = "mysql:dbname=dm107pos;host=127.0.0.1";
    $pdo = new PDO($dsn, "root", "");
    $db = new NotORM($pdo);
    $idToDelete = $request->getAttribute('id');
    $entregas = $db->entrega()->where('id', $idToDelete);

    foreach ($entregas as $entrega) {
        $result = $entregas->delete();
    }
    return $response->withStatus(200)->getBody()->write('Entrega editada com sucesso!'); 
});

$app->run();
?>