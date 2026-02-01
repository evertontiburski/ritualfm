<?php

class Eventos extends Controller
{
    private $evento_model;

    public function __construct()
    {
        $this->evento_model = $this->model("EventoModel");
    }

    public function index()
    {        
        $eventos = $this->evento_model->listar_evento_home();
        $dados = ["eventos" => $eventos];

        $this->view('eventos/index', (object) $dados);

    }
}
