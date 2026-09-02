<?php

require_once 'Models/Category.php';

class CategoryController
{
    private $model;

    public function __construct()
    {
        $this->model = new Category();
    }

    public function getAll()
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

}