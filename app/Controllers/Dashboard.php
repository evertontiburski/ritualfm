<?php

class Dashboard extends Controller
{

    private $usuario_model;
    private $comentario_model;
    private $notificacao_model;

    // Resposável por verificar se o usuário esta logado, caso contrário joga para a página de login
    public function __construct()
    {
        if (!isset($_SESSION['usuario_id'])) {
            session_unset();
            session_destroy();
            Funcoes::redirecionar('usuarios/login');
            exit;
        }

        $this->usuario_model = $this->model("UsuarioModel");
        $this->comentario_model = $this->model("ComentarioModel");
        $this->notificacao_model = $this->model("NotificacaoModel");
    }

    public function index()
    {
        $usuario = $this->usuario_model->busca_usuario_id($_SESSION['usuario_id']);

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            $comentarios = $this->comentario_model->listar_qtd_comentario_usuario($usuario->id_usuario);
            $notificacoes = $this->notificacao_model->listar_qtd_notificacao();

            $dados = ["comentarios" => $comentarios, "notificacoes" => $notificacoes];

            $this->view('dashboard/index', (object) $dados);

            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        }
    }

    public function configuracoes($requisicao = null)
    {
        // Obtendo os dados do usuário
        $usuario = $this->usuario_model->busca_usuario_id($_SESSION['usuario_id']);

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            unset($usuario->senha);

            $dados = ["usuario" => $usuario];

            $this->view('dashboard/configuracoes', $dados);

            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if ($requisicao == 'atualizar-dados') {

                $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

                if (!Validador::campo_obrigatorio($dados['nome'])) {

                    Funcoes::resposta(false, "Campo nome em branco", 400);
                }

                if (!Validador::nome($dados['nome'])) {

                    Funcoes::resposta(false, "O nome informado é inválido", 400);
                }

                if (strlen($dados['nome']) > 50 || strlen($dados['nome']) < 3) {

                    Funcoes::resposta(false, "O nome que você inseriu excede o limite máximo caracteres ou possui menos de 3 caracteres", 400);
                }

                if (!Validador::campo_obrigatorio($dados['email'])) {

                    Funcoes::resposta(false, "Campo e-mail em branco", 400);
                }

                if (!Validador::email($dados['email'])) {

                    Funcoes::resposta(false, "O e-mail informado é inválido", 400);
                }

                // Validandações de email...
                $email_novo = $dados['email'];
                $email_atual = $usuario->email;
                $email_existente = $this->usuario_model->consulta_email($email_novo);

                if ($email_novo === $email_atual || !$email_existente) {

                    $usuario->email = (!empty($dados['email']) ? Funcoes::protegerStringAtaqueXSS($dados['email']) : $usuario->email);

                } else {

                    Funcoes::resposta(false, "Use um endereço de e-mail diferente", 400);
                }

                if (!Validador::senha($dados['senha_atual'])) {

                    Funcoes::resposta(false, "Você deve informar a senha atual", 400);
                }

                // Caso o usuario deseja alterar a senha...
                if (!empty($dados['nova_senha'])) {

                    if (!Validador::senha($dados['nova_senha'])) {

                        Funcoes::resposta(false, "A senha deve ter no mínimo 6 caracteres", 400);
                    }

                    if (!Validador::campo_obrigatorio($dados['confirmar_senha'])) {

                        Funcoes::resposta(false, "Campo confirma senha em branco", 400);
                    }

                    if (!Validador::confirma_senha($dados['nova_senha'], $dados['confirmar_senha'])) {

                        Funcoes::resposta(false, "As senhas devem ser iguais", 400);
                    }

                }

                // Validando as informações
                $usuario->nome = (!empty($dados['nome']) ? Funcoes::protegerStringAtaqueXSS($dados['nome']) : $usuario->nome);
                $usuario->senha = (!empty($dados['nova_senha']) ? Funcoes::gerarSenha($dados['nova_senha']) : $usuario->senha);
                $usuario->atualizado_em = date('Y-m-d H:i:s');

                // Verificando a senha informada pelo usuário
                if (password_verify($dados['senha_atual'], $usuario->senha) != true) {

                    Funcoes::resposta(false, "A senha digitada não corresponde à cadastrada no sistema.", 400);

                    return;
                }

                // Transformando o objeto em um array, para assim salvar no banco de dados as infos do usuario
                $usuario_array = (array) $usuario;

                if ($this->usuario_model->atualizar($usuario_array, $usuario->id_usuario)) {

                    Funcoes::resposta(true, "Usuário atualizado com sucesso.", 200, [
                        'redirecionar' => URL . '/dashboard/configuracoes',
                    ]);
                } else {

                    Funcoes::resposta(false, "Ocorreu um erro de sistema em tentar atualizar o usuário.", 400);
                }
            }
        }
    }

    public function comentarios($id_comentario = null)
    {
        // Obtendo os dados do usuário
        $usuario = $this->usuario_model->busca_usuario_id($_SESSION['usuario_id']);

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            $comentarios = $this->comentario_model->listar_comentarios_por_usuario($usuario->id_usuario);

            $dados = ["comentarios" => $comentarios];

            $this->view('dashboard/comentarios', (object) $dados);

            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // Validando o usuario que quer excluir o comentario, a ideia central é o usuario excluir o comentario,
            // no entanto o comentario vai apenas ter o status 0 - dasativado

            $id_comentario = (int) $id_comentario;
            $retorno = $this->comentario_model->comentario_pertence_ao_usuario($id_comentario, $usuario->id_usuario);

            if($retorno != true) {
                // Trollando o macaco metido a hacker
                Funcoes::resposta(false, "Hoje sim, hoje sim, Hoje não!", 400);
                die;
            }

            // Desativando o comentario
            if($this->comentario_model->desativando_comentario_usuario($id_comentario) != true) {
                Funcoes::resposta(false, "Ops ocorreu um erro, tente novamente mais tarde.", 400);
                return;
                
            } else {
                Funcoes::resposta(true, "Comentário excluído com sucesso.", 200, [
                    "redirecionar" => URL . "/dashboard/comentarios"
                ]);
            }

        }
    }

    public function novidades()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            $notificacoes = $this->notificacao_model->listar_notificacao_dashboard();

            $dados = ["notificacoes" => $notificacoes];

            $this->view('dashboard/novidades', (object) $dados);

            return;

        }

    }
}
