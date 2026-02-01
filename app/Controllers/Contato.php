<?php

class Contato extends Controller
{

    private $contato_model;

    public function __construct()
    {
        $this->contato_model = $this->model("ContatoModel");
    }

    public function index()
    {

        $this->view('contato/index');
    }

    public function enviar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            $this->view('/contato');

            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

            $dados = [
                'nome' => trim(Funcoes::protegerStringAtaqueXSS($dados['nome']) ?? ''),
                'email' => trim($dados['email']) ?? '',
                'mensagem' => trim(Funcoes::protegerStringAtaqueXSS($dados['mensagem']) ?? '')
            ];


            if (!Validador::campo_obrigatorio($dados['nome'])) {

                Funcoes::resposta(false, "Campo nome em branco", 400);
            }

            if (!Validador::nome($dados['nome'])) {

                Funcoes::resposta(false, "O nome informado é inválido", 400);
            }

            if (!Validador::campo_obrigatorio($dados['email'])) {

                Funcoes::resposta(false, "Campo e-mail em branco", 400);
            }

            if (!Validador::email($dados['email'])) {

                Funcoes::resposta(false, "O e-mail informado é inválido", 400);
            }

            if (!Validador::campo_obrigatorio($dados['mensagem'])) {

                Funcoes::resposta(false, "Campo mensagem em branco", 400);
            }

            if (!Funcoes::validaStringComNumerosEPontos($dados['mensagem'])) {

                Funcoes::resposta(false, "O campo mensagem é inválido", 400);
            }

            if ($this->contato_model->armazenar($dados)) {

                Funcoes::resposta(true, "Mensagem de contato enviado com sucesso. Você esta sendo redirecionado, aguarde..", 200, [
                    "redirecionar" => URL . "/contato"
                ]);

            } else {

                Funcoes::resposta(false, "Não foi possível enviar sua mensagem, por favor tente novamente mais tarde", 400);
            }
        }
    }
}
