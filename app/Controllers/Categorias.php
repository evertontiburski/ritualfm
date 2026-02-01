<?php

class Categorias extends Controller {

    private $categoria_model;

    public function __construct()
    {
        $this->categoria_model = $this->model("CategoriaModel");
    }

    public function index($slug = null)
    {
        if (!$slug) {

            Funcoes::redirecionar('404');
        }

        $categoria_slug = $this->categoria_model->busca_categoria_slug($slug);

        $this->view('categorias/index', (object) $dados = [
            'categoria_slug' => $categoria_slug
        ]);
        
    }

}