<?php


require_once __DIR__ . '/../Models/Category.php';

class CategoryController
{
    private $model;

    public function __construct()
    {
        $this->model = new Category();
    }

    public function getAll($params = null)
    {
        try {
            header('Content-Type: application/json');
        $categories = $this->model->getAll();
        echo json_encode($categories);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['ERROR'=>$e->getMessage(),'code'=>'500']);
        }
    }

    public function getById($params)
    {
        $id = $params[1] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(['ERROR'=>'ID no proporcionado','Code'=>400]);
        }

        try {
            $categoria = $this->model->getById($id);
            if ($categoria) {
                header('Content-Type: application/json');
                echo json_encode($categoria);
            } else {
                http_response_code(400);
                echo json_encode(['ERROR :'=>'Categoria no encontrado','Code'=>400]);
            }
        } catch (Exception $e){
            http_response_code(500);
            echo json_encode(['ERROR :'=>$e->getMessage(),'Code'=>500]);
        }
    }

}