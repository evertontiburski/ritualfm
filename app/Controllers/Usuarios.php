<?php

class Usuarios extends Controller
{

    private $usuario_model;

    public function __construct()
    {
        $this->usuario_model = $this->model("UsuarioModel");
    }

    public function cadastrar()
    {
        if (isset($_SESSION['usuario_id'])) {
            Funcoes::redirecionar();
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            $this->view('usuarios/cadastrar');

            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

            $dados = [
                'nome' => trim(Funcoes::protegerStringAtaqueXSS($dados['nome']) ?? ''),
                'email' => trim($dados['email'] ?? ''),
                'senha' => trim($dados['senha'] ?? ''),
                'confirma_senha' => trim($dados['confirma_senha'] ?? '')
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

            if ($this->usuario_model->consulta_email($dados['email']) != false) {

                Funcoes::resposta(false, "O e-mail informado já foi cadastrado", 400);
            }

            if (!Validador::campo_obrigatorio($dados['senha'])) {

                Funcoes::resposta(false, "Campo senha em branco", 400);
            }

            if (!Validador::senha($dados['senha'])) {

                Funcoes::resposta(false, "A senha deve ter no mínimo 6 caracteres", 400);
            }

            if (!Validador::campo_obrigatorio($dados['confirma_senha'])) {

                Funcoes::resposta(false, "Campo confirma senha em branco", 400);
            }

            if (!Validador::confirma_senha($dados['senha'], $dados['confirma_senha'])) {

                Funcoes::resposta(false, "As senhas devem ser iguais", 400);
            }

            // Instanciando a classe usuario com os dados válidos
            $usuario = new UsuarioCadastro($dados);

            if ($this->usuario_model->armazenar($usuario->dados_array())) {

                // Removendo a senha, confirma senha, e o status da conta, pois não quero enviar ao front-end
                unset($dados['senha'], $dados['confirma_senha'], $dados['status']);

                Funcoes::resposta(true, "Cadastro realizado com sucesso", 200, [
                    'redirecionar' => URL . '/usuarios/login'
                ]);

            } else {

                Funcoes::resposta(true, "Não foi possível realizar o seu cadastro, por favor tente novamente mais tarde", 400);
            }
        }
    }

    public function login()
    {
        if (isset($_SESSION['usuario_id'])) {
            Funcoes::redirecionar();
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            $this->view('usuarios/login');

            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

            $dados = [
                'email' => trim($dados['email']),
                'senha' => trim($dados['senha']),
            ];

            if (empty($dados['email'])) {

                Funcoes::resposta(false, "Campo e-mail em branco", 400);

            } else if (empty($dados['senha'])) {

                Funcoes::resposta(false, "Campo senha em branco", 400);

            } else if (!Funcoes::validarEmail($dados['email'])) {

                Funcoes::resposta(false, "O e-mail informado é inválido", 400);

            } else {

                // Verifica os dados do usuário, se existe ou não
                $usuario = $this->usuario_model->login($dados['email'], $dados['senha']);

                if ($usuario) {

                    // Criando a sessão do usuário
                    $this->criar_sessao_usuario($usuario);

                    Funcoes::resposta(true, 'Login efetuado com sucesso', 200, [
                        'redirecionar' => URL . '/dashboard',
                        'usuario_nome' => $_SESSION['usuario_nome']
                    ]);

                } else {

                    Funcoes::resposta(false, "Usuário ou senha inválidos", 400);
                }
            }
        }
    }


    // TESTE
    private function renderizarTemplateEmail(string $caminhoArquivoEmail, array $dados = []): string
    {
        // Transforma as chaves do array $dados em variáveis (ex: $dados['usuario'] vira $usuario)
        extract($dados);

        // Inicia o buffer de saída para capturar o HTML
        ob_start();

        // Caminho de forma relativa ao diretório deste arquivo (app/Controllers)
        // __DIR__ aponta para a pasta 'Controllers'
        // '/../' sobe um nível (para a pasta 'app')
        // '/Views/' entra na pasta de views
        require_once __DIR__ . '/../Views/' . $caminhoArquivoEmail . '.php';

        // Pega o conteúdo do buffer e o limpa, retornando o HTML como string
        return ob_get_clean();
    }

    public function recuperacao()
    {
        if (isset($_SESSION['usuario_id'])) {
            Funcoes::redirecionar();
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            $this->view('usuarios/recuperacao');

            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

            $dados = [
                'email' => trim($dados['email'])
            ];

            if (empty($dados['email'])) {

                Funcoes::resposta(false, "Campo e-mail em branco", 400);

            } else if (!Funcoes::validarEmail($dados['email'])) {

                Funcoes::resposta(false, "O e-mail informado é inválido", 400);
            }

            // Gerando um token para salvar no banco de dados
            $token = bin2hex(random_bytes(32));

            // Definindo a expiração para 1 hora para salvar no banco de dados
            $data_expiracao = date("Y-m-d H:i:s", time() + 3600);

            // Enviado as informações para o banco de dados
            $salvar = [
                'token' => $token,
                'email' => $dados['email'],
                'data_expiracao' => $data_expiracao,
            ];

            // Buscando o email do usuario se existe ou não no banco de dados
            if($this->usuario_model->consulta_email($dados['email']) != false) {

                // Buscando o usuario pelo e-mail
                $usuario = $this->usuario_model->busca_usuario_email($dados['email']);

                // Criando senha temporária
                $senha_temporaria = Funcoes::gerarSenhaTemporaria();

                // Enviando a senha temporária para o banco de dados
                $this->usuario_model->senha_temporaria($senha_temporaria, $usuario->id_usuario);

                // Dados que serão enviado para o e-mail
                $dados_usuario_email = [
                    "nome" => $usuario->nome,
                    "email" => $usuario->email,
                    "senha_temporaria" => $senha_temporaria,
                ];

                // Enviando e-mail para o usuario com a senha temporaria
                try {
                    $email = new Email();

                    // Usamos o novo método dedicado para renderizar APENAS o template do e-mail
                    $conteudoEmail = $this->renderizarTemplateEmail(
                        'usuarios/emails/atualizar',
                        ["usuario" => $dados_usuario_email]
                    );

                    // Corpo do e-mail
                    $email->criar(
                        'Recuperação de Senha — ' . NOME_SITE,
                        $conteudoEmail,
                        $dados_usuario_email["email"],
                        $dados_usuario_email['nome']
                    );

                    // Quem esta enviando o e-mail
                    $email->enviar('noreply@ritualfm.com', 'RitualFM');

                    $this->usuario_model->tokenEsqueciMinhaSenha($salvar);

                    Funcoes::resposta(true, "Enviamos um e-mail para você, por favor verifique o seu e-mail.", 200);


                } catch(\PHPMailer\PHPMailer\Exception $ex) {

                    echo $ex;

                }

                

            } else {
                Funcoes::resposta(false, "Ocorreu um erro, por favor tente novamente mais tarde.", 400);
            }

        }

    }


    // Pegando os dados do usuário, e criando a sessão, com os respectivos campos usuario_id, usuario_nome, e usuario_email
    private function criar_sessao_usuario($usuario)
    {
        $_SESSION['usuario_id'] = $usuario->id_usuario;
        $_SESSION['usuario_nome'] = $usuario->nome;
        $_SESSION['usuario_email'] = $usuario->email;
        $_SESSION['usuario_status'] = $usuario->status;
        $_SESSION['usuario_level'] = $usuario->level;
        $_SESSION['usuario_cadastrado_em'] = $usuario->cadastrado_em;
        $_SESSION['usuario_atualizado_em'] = $usuario->atualizado_em;
    }

    public function sair()
    {
        // Limpa todas as variáveis de sessão
        session_unset();
        // Destrói a sessão
        session_destroy();

        // Verifica se é uma requisição AJAX
        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        $loginUrl = URL . '/usuarios/login';

        if ($isAjax) {
            // Para AJAX, envia uma resposta JSON com o aviso de logout e a URL de redirecionamento
            Funcoes::resposta(true, 'Logout efetuado com sucesso.', 200, [
                'redirecionar' => $loginUrl,
                'acao' => 'logout' // <-- O "aviso" que o JavaScript vai procurar
            ]);
        } else {
            // Para requisições normais, faz o redirect padrão
            header('Location: ' . $loginUrl);
            exit;
        }
    }
}
