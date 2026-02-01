<?php

class Musicas extends Controller
{
    private $musica_model;

    public function __construct()
    {
        $this->musica_model = $this->model("MusicaModel");
    }

    public function index()
    {        
        $musicas = $this->musica_model->listar_musica_home();

        $dados = ["musicas" => $musicas];

        $this->view('musicas/index', (object) $dados);

    }
}
