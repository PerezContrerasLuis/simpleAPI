<?php
// Punto de entrada de la aplicación
require_once __DIR__ .'/routes/api.php';

// Obtener método y URI
$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);


// Buscar ruta

$routeFound = false;
foreach ($routes as $route) {
    if ($route['method'] === $method && preg_match($route['pattern'], $uri, $matches)) {
        $controller = new $route['controller']();
        $controller->{$route['action']}($matches);
        $routeFound = true;
        break;
    }
}

if (!$routeFound) {
    http_response_code(404);
    echo json_encode(['error' => 'Ruta no encontrada', 'code' => 404]);
}
?>
