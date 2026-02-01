<?php

class Publicidades extends Controller
{

    private $publicidade_model;

    public function __construct()
    {
        $this->publicidade_model = $this->model("PublicidadeModel");
    }

    public function index($slug = null)
    {
        $publicidade_slug = $this->publicidade_model->busca_publicidade_slug($slug);

        if (!$publicidade_slug) {
            Funcoes::redirecionar("paginas/404");
        }

        $dados = ["publicidade_slug" => $publicidade_slug];

        $this->view('publicidades/index', (object) $dados);
    }
}
