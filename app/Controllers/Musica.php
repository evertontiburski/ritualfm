<?php

class Musica extends Controller
{
    private $musica_model;
    private $usuario_model;

    public function __construct()
    {
        $this->musica_model = $this->model("MusicaModel");
        $this->usuario_model = $this->model("UsuarioModel");
    }

    public function index($slug = null)
    {        
        $musica_slug = $this->musica_model->busca_musica_slug($slug);

        if(!$musica_slug) {
            Funcoes::redirecionar();
        }

        $autor = $this->usuario_model->busca_usuario_id($musica_slug->usuario_id);

        // Retirando informações sensiveis do usuário, deixei apenas o nome passar para o frontend
        unset($autor->id_usuario, $autor->email, $autor->senha, $autor->status, $autor->level, $autor->level, $autor->cadastrado_em, $autor->atualizado_em);

        $musica_slug->usuario_id = $autor->nome;

        $dados = ["musica_slug" => $musica_slug];

        $this->view('musica/index', (object) $dados); 

    }
}
