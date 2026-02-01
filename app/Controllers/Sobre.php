<?php

class Sobre extends Controller
{
    private $sobre_model;

    public function __construct()
    {
        $this->sobre_model = $this->model("SobreModel");
    }

    public function index()
    {        
        $sobre = $this->sobre_model->listar_sobre_home();
        $dados = ["sobres" => $sobre];

        $this->view('sobre/index', (object) $dados);

    }
}
