<?php

class Post extends Controller
{
    private $post_model;
    private $comentario_model;
    private $usuario_model;
    private $social_model;

    public function __construct()
    {
        $this->post_model = $this->model("PostModel");
        $this->comentario_model = $this->model("ComentarioModel");
        $this->usuario_model = $this->model("UsuarioModel");
        $this->social_model = $this->model("SocialModel");
    }

    public function index($slug = null)
    {
        $post_slug = $this->post_model->busca_post_slug($slug);

        if(!$post_slug) {
            Funcoes::redirecionar();
        }
        
        $autor = $this->usuario_model->busca_usuario_id($post_slug->usuario_id);
        $sociais = $this->social_model->listar_todos_sociais();

        // Retirando informações sensiveis do usuário, deixei apenas o nome passar para o frontend
        unset($autor->id_usuario, $autor->email, $autor->senha, $autor->status, $autor->level, $autor->level, $autor->cadastrado_em, $autor->atualizado_em);

        $post_slug->usuario_id = $autor->nome;

        $comentarios = $this->comentario_model->lista_comentarios_por_post($post_slug->id_post);

        $dados = ["post_slug" => $post_slug, "comentarios" => $comentarios, "sociais" => $sociais];

        $this->view('post/index', (object) $dados);

    }
}
