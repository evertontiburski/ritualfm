<?php

class Posts extends Controller
{
    private $post_model;

    public function __construct()
    {
        $this->post_model = $this->model("PostModel");
    }

    public function index()
    {
        $posts = $this->post_model->listar_post_lista();
        $dados = ["posts" => $posts];

        $this->view('posts/index', (object) $dados);
    }

}
