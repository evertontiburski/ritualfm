<?php

class Home extends Controller {

    private $post_model;
    private $social_model;

    public function __construct()
    {
        $this->post_model = $this->model("PostModel");
        $this->social_model = $this->model("SocialModel");
    }

    public function index() {

        $posts = $this->post_model->listar_post_home();
        $sociais = $this->social_model->listar_todos_sociais();
        $dados = ["posts" => $posts, "sociais" => $sociais];

        $this->view('home/index', (object) $dados);

    }

}