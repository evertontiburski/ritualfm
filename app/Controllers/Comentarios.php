<?php

class Comentarios extends Controller
{
    private $comentario_model;

    public function __construct()
    {
        $this->comentario_model = $this->model("ComentarioModel");
    }

    public function cadastrar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            
            $this->view("post/");

            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

            $dados = [
                'comentario' => trim(Funcoes::protegerStringAtaqueXSS($dados['comentario']) ?? ''),
                'status' => $dados['status'] = 0, // Comentário quando cadastrado é 0 - inativo, quando aprovado pelo adm 1 - ativo
                'usuario_id' => (int) trim($dados['usuario_id'] ?? ''),
                'post_id' => (int) trim($dados['post_id'] ?? ''),
                'post_slug' => trim($dados['post_slug'] ?? ''),
            ];

            // Evitando que o slug seja excluido
            $slug = $dados['post_slug'];

            if (empty($dados['comentario'])) {

                Funcoes::resposta(false, "Por favor, escreva seu comentário", 400);

            } else if (strlen($dados['comentario']) < 3) {

                Funcoes::resposta(false, "O comentário deve ter no mínimo 3 caracteres", 400);

            } else if (strlen($dados['comentario']) > 250) {

                Funcoes::resposta(false, "O comentário deve ter no máximo 250 caracteres", 400);

            }

            // Excluindo o slug, pois ele não é salvo no banco de dados
            unset($dados['post_slug']);

            if ($this->comentario_model->armazenar($dados)) {

                Funcoes::resposta(true, "Seu comentário foi cadastrado com sucesso, aguarde a página será atualizada...", 200, [
                    'redirecionar' => URL . '/post/' . $slug
                ]);

            } else {
                Funcoes::resposta(false, "Erro de sistema ao tentar efetuar o cadastro do comentário", 400, $dados);
            }
        }
    }
}
