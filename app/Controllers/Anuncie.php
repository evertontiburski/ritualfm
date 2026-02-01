<?php

class Anuncie extends Controller
{
    private $anuncie_model;

    public function __construct()
    {
        $this->anuncie_model = $this->model("AnuncieModel");
    }

    public function index()
    {        
        $anuncie = $this->anuncie_model->listar_anuncie_home();
        $dados = ["anuncies" => $anuncie];

        $this->view('anuncie/index', (object) $dados);

    }
}
