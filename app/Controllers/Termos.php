<?php

class Termos extends Controller
{
    private $termo_model;

    public function __construct()
    {
        $this->termo_model = $this->model("TermoModel");
    }

    public function index()
    {        
        $termos = $this->termo_model->listar_termos_home();
        $dados = ["termos" => $termos];

        $this->view('termos/index', (object) $dados);

    }
}
