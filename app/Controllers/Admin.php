<?php

class Admin extends Controller
{
    private $imagem_post;
    private $imagem_evento;
    private $imagem_musica;
    private $imagem_publicidade;

    private $usuario_model;
    private $post_model;
    private $categoria_model;
    private $publicidade_model;
    private $comentario_model;
    private $contato_model;
    private $evento_model;
    private $musica_model;
    private $sobre_model;
    private $termo_model;
    private $anuncie_model;
    private $social_model;
    private $notificacao_model;

    public function __construct()
    {
        $this->usuario_model = $this->model("UsuarioModel");
        $this->post_model = $this->model("PostModel");
        $this->categoria_model = $this->model("CategoriaModel");
        $this->publicidade_model = $this->model("PublicidadeModel");
        $this->comentario_model = $this->model("ComentarioModel");
        $this->contato_model = $this->model("ContatoModel");
        $this->evento_model = $this->model("EventoModel");
        $this->musica_model = $this->model("MusicaModel");
        $this->sobre_model = $this->model("SobreModel");
        $this->termo_model = $this->model("TermoModel");
        $this->anuncie_model = $this->model("AnuncieModel");
        $this->social_model = $this->model("SocialModel");
        $this->notificacao_model = $this->model("NotificacaoModel");

        // 0 - usuário não validado, 1 - usuário validado, 2 - moderador/editor, 3 - administrador
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_level'] < 3) {
            session_unset();
            session_destroy();
            Funcoes::redirecionar('usuarios/login');
            exit;
        }
    }

    public function index()
    {

        // Dados completos
        $posts = $this->post_model->listar_todos_posts();
        $categorias = $this->categoria_model->listar_todas_categorias();
        $usuarios = $this->usuario_model->listar_todos_usuarios();
        $eventos = $this->evento_model->listar_todos_eventos();
        $comentarios = $this->comentario_model->lista_todos_comentarios();
        $contatos = $this->contato_model->listar_todos_contatos();

        // Quantidades apenas
        $usuarios_qtd = $this->usuario_model->listar_qtd_usuarios();
        $categorias_qtd = $this->categoria_model->listar_qtd_categorias();
        $posts_qtd = $this->post_model->listar_qtd_posts();
        $comentarios_qtd = $this->comentario_model->listar_qtd_comentarios();
        $eventos_qtd = $this->evento_model->listar_qtd_eventos();
        $contatos_qtd = $this->contato_model->listar_qtd_contatos();
        

        // $dados = [
            // 'posts' => $posts, 
            // 'categorias' => $categorias, 
            // 'usuarios' => $usuarios, 
            // 'eventos' => $eventos, 
            // 'comentarios' => $comentarios, 
            // 'contatos' => $contatos
        // ];

        $dados = [
            'posts' => $posts, 
            'categorias' => $categorias, 
            'usuarios' => $usuarios, 
            'eventos' => $eventos, 
            'comentarios' => $comentarios, 
            'contatos' => $contatos,
            'usuarios_qtd' => $usuarios_qtd, 
            'categorias_qtd' => $categorias_qtd, 
            'posts_qtd' => $posts_qtd, 
            'comentarios_qtd' => $comentarios_qtd, 
            'eventos_qtd' => $eventos_qtd, 
            'contatos_qtd' => $contatos_qtd
        ];

        $this->view('admin/index', (object) $dados);
    }


    // Posts
    public function posts($requisicao = null, $id_post = null)
    {
        if ($requisicao == 'cadastrar') {

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {

                $categorias = $this->categoria_model->listar_todas_categorias();
                $dados = ['categorias' => $categorias];

                $this->view('admin/posts/cadastrar', (object) $dados);
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

                $dados = [
                    'titulo' => trim($dados['titulo']),
                    'subtitulo' => trim(Funcoes::protegerStringAtaqueXSS($dados['subtitulo'])),
                    'texto' => trim($dados['texto']),
                    'status' => trim(Funcoes::protegerStringAtaqueXSS($dados['status'])),
                    'categoria_id' => trim(Funcoes::protegerStringAtaqueXSS($dados['categoria_id'])),
                    'usuario_id' => trim(Funcoes::protegerStringAtaqueXSS($dados['usuario_id']))
                ];

                if (Funcoes::valida_campo($dados['titulo'])) {

                    Funcoes::resposta(false, "Campo título em branco", 400);

                } else if (Funcoes::valida_campo($dados['subtitulo'])) {

                    Funcoes::resposta(false, "Campo subtítulo em branco", 400);

                } else if (Funcoes::valida_campo($dados['texto'])) {

                    Funcoes::resposta(false, "Campo texto em branco", 400, $dados);

                } else if (Funcoes::valida_campo($dados['status'])) {

                    Funcoes::resposta(false, "Campo status em branco", 400, $dados);
                    
                }

                // Inicia a imagem como nula
                // Se nenhum arquivo for enviado, ela permanecerá assim
                $dados['imagem_post'] = null;

                // Verifica se um arquivo foi enviado e se não há erros
                // (error 0 significa que o upload foi bem-sucedido até aqui)
                if (isset($_FILES['imagem_post']) && $_FILES['imagem_post']['error'] == 0) {

                    // 1. Define o caminho absoluto para sua pasta 'public'
                    $caminho_projeto = parse_url(URL, PHP_URL_PATH);
                    $caminho_public_abs = $_SERVER['DOCUMENT_ROOT'] . $caminho_projeto . DIRECTORY_SEPARATOR . 'public';

                    // 2. Instancia a classe com o caminho base
                    $upload = new Upload($caminho_public_abs);

                    // 3. Executa o upload
                    $uploadOk = $upload->arquivo(
                        $_FILES['imagem_post'],
                        Funcoes::slug($dados['titulo']),
                        'imagens'
                    );

                    if ($uploadOk) {
                        // Se o upload deu certo, pega o nome do novo arquivo
                        $dados['imagem_post'] = $upload->getResultado();
                    } else {
                        // Se falhou, mostra o erro e para a execução
                        Funcoes::resposta(false, $upload->getErro(), 400);
                        exit;
                    }
                }

                // Instanciando a classe post com os dados válidos
                $post = new PostCadastro($dados);

                if ($this->post_model->armazenar($post->dados_array())) {

                    Funcoes::resposta(true, "Post cadastrado com sucesso. Você esta sendo redirecionado, aguarde..", 200, [
                        "redirecionar" => URL . "/admin/posts"
                    ]);
                } else {

                    Funcoes::resposta(false, "Não foi possível cadastrar o post, por favor tente novamente mais tarde", 400);
                }
            }
        } else if ($requisicao == 'editar') {

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {

                $post = $this->post_model->busca_post_id($id_post);
                $categorias = $this->categoria_model->listar_todas_categorias();

                $dados = ["post" => $post, "categorias" => $categorias];


                $this->view('admin/posts/editar', (object) $dados);
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

                $dados = [
                    'titulo' => trim($dados['titulo']),
                    'subtitulo' => trim(Funcoes::protegerStringAtaqueXSS($dados['subtitulo'])),
                    'texto' => trim($dados['texto']),
                    'status' => trim(Funcoes::protegerStringAtaqueXSS($dados['status'])),
                    'categoria_id' => trim(Funcoes::protegerStringAtaqueXSS($dados['categoria_id'])),
                    'usuario_id' => trim(Funcoes::protegerStringAtaqueXSS($dados['usuario_id']))
                ];

                if (Funcoes::valida_campo($dados['titulo'])) {

                    Funcoes::resposta(false, "Campo título em branco", 400);

                } else if (Funcoes::valida_campo($dados['subtitulo'])) {

                    Funcoes::resposta(false, "Campo subtítulo em branco", 400);

                } else if (Funcoes::valida_campo($dados['texto'])) {

                    Funcoes::resposta(false, "Campo texto em branco", 400);

                } else if (Funcoes::valida_campo($dados['status'])) {

                    Funcoes::resposta(false, "Campo status em branco", 400, $dados);
                }

                $post = $this->post_model->busca_post_id($id_post);

                // Gerando o slug atualizado
                $dados['slug'] = Funcoes::slug($dados['titulo']) . '-' . date("Y-m-d");

                // Verificando se houve alteração no titulo ou no texto
                $dados['titulo'] ? Funcoes::protegerStringAtaqueXSS($dados['titulo']) : $post->titulo;
                $dados['texto'] ? Funcoes::sanitizarHtmlComPurifier($dados['texto']) : $post->texto;

                // Pegando a data e hora atual
                $dados['data_edicao'] = date("Y-m-d h:i:s");

                // ALTERANDO IMAGEM DO POST
                // Verifica se um novo arquivo foi enviado
                if (isset($_FILES['imagem_post']) && $_FILES['imagem_post']['error'] == 0) {

                    // 1. Define o caminho absoluto para sua pasta 'public'
                    $caminho_projeto = parse_url(URL, PHP_URL_PATH); // Extrai '/ritualfm'
                    $caminho_public_abs = $_SERVER['DOCUMENT_ROOT'] . $caminho_projeto . DIRECTORY_SEPARATOR . 'public';

                    // 2. Instancia a classe com o caminho base
                    $upload = new Upload($caminho_public_abs);

                    // 3. Executa o upload. Passe o nome do arquivo antigo para que a classe o delete.
                    $uploadOk = $upload->arquivo(
                        $_FILES['imagem_post'],
                        $dados['slug'],
                        'imagens',
                        $post->imagem_post // <--- O NOME DO ARQUIVO ANTIGO VAI AQUI
                    );

                    if ($uploadOk) {
                        // Se o upload deu certo, pega o nome do novo arquivo e o salva no banco
                        $dados['imagem_post'] = $upload->getResultado();
                    } else {
                        // Se falhou, mostra o erro e para a execução
                        Funcoes::resposta(false, $upload->getErro(), 400, [
                            "redirecionar" => URL . "/admin/posts/editar/{$id_post}"
                        ]);
                        exit;
                    }
                } else {
                    // Se nenhum novo arquivo foi enviado, mantém a imagem antiga no banco.
                    $dados['imagem_post'] = $post->imagem_post;
                }

                if ($this->post_model->atualizar($dados, $id_post)) {

                    Funcoes::resposta(true, "Post atualizado com sucesso. Você esta sendo redirecionado, aguarde..", 200, [
                        "redirecionar" => URL . "/admin/posts"
                    ]);

                } else {

                    Funcoes::resposta(false, "Não foi possível atualizar o post, por favor tente novamente mais tarde", 400);
                }
            }
        } else if ($requisicao == 'deletar') {

            $id_post = (int) $id_post;

            if ($this->post_model->deletar($id_post) != false) {

                Funcoes::resposta(true, "Post deletado com sucesso. A página será atualizada, aguarde..", 200, [
                    "redirecionar" => URL . "/admin/posts"
                ]);
            }
        } else {

            $posts = $this->post_model->listar_todos_posts();
            $categorias = $this->categoria_model->listar_todas_categorias();

            $dados = ["posts" => $posts, "categorias" => $categorias];


            $this->view('admin/posts/index', (object) $dados);
        }
    }


    // Categorias
    public function categorias($requisicao = null, $id_categoria = null)
    {
        if ($requisicao == 'cadastrar') {

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {

                $categorias = $this->categoria_model->listar_todas_categorias();

                $dados = ["categorias" => $categorias];


                $this->view('admin/categorias/cadastrar', (object) $dados);
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

                $dados = [
                    'titulo' => trim($dados['titulo']),
                    'texto' => trim($dados['texto']),
                    'status' => trim(Funcoes::protegerStringAtaqueXSS($dados['status']))
                ];

                if (Funcoes::valida_campo($dados['titulo'])) {

                    Funcoes::resposta(false, "Campo título em branco", 400);

                } else if (Funcoes::valida_campo($dados['texto'])) {

                    Funcoes::resposta(false, "Campo texto em branco", 400);

                } else if (Funcoes::valida_campo($dados['status'])) {

                    Funcoes::resposta(false, "Campo status em branco", 400, $dados);
                }

                // Url amigável
                $dados['slug'] = Funcoes::slug($dados['titulo']);

                // Passa o conteúdo pela sanatização
                $dados['texto'] = Funcoes::sanitizarHtmlComPurifier($dados['texto']);
                $dados['titulo'] = Funcoes::protegerStringAtaqueXSS($dados['titulo']);

                if ($this->categoria_model->armazenar($dados)) {

                    Funcoes::resposta(true, "Categoria cadastrada com sucesso. Você esta sendo redirecionado, aguarde..", 200, [
                        "redirecionar" => URL . "/admin/categorias"
                    ]);
                } else {

                    Funcoes::resposta(false, "Não foi possível cadastrar a categoria, por favor tente novamente mais tarde", 400);
                }
            }
        } else if ($requisicao == 'editar') {

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {

                $categoria = $this->categoria_model->busca_categoria_id($id_categoria);

                $dados = ["categoria" => $categoria];


                $this->view('admin/categorias/editar', (object) $dados);
            }


            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

                $dados = [
                    'titulo' => trim($dados['titulo']),
                    'texto' => trim($dados['texto']),
                    'status' => trim(Funcoes::protegerStringAtaqueXSS($dados['status']))
                ];

                if (Funcoes::valida_campo($dados['titulo'])) {

                    Funcoes::resposta(false, "Campo título em branco", 400);

                } else if (Funcoes::valida_campo($dados['texto'])) {

                    Funcoes::resposta(false, "Campo texto em branco", 400);

                } else if (Funcoes::valida_campo($dados['status'])) {

                    Funcoes::resposta(false, "Campo status em branco", 400, $dados);
                }

                $categoria = $this->categoria_model->busca_categoria_id($id_categoria);

                // Gerando o slug atualizado
                $dados['slug'] = Funcoes::slug($dados['titulo']);

                // Verificando se houve alteração no titulo ou no texto
                $dados['titulo'] ? Funcoes::protegerStringAtaqueXSS($dados['titulo']) : $categoria->titulo;
                $dados['texto'] ? Funcoes::sanitizarHtmlComPurifier($dados['texto']) : $categoria->texto;

                if ($this->categoria_model->atualizar($dados, $id_categoria)) {

                    Funcoes::resposta(true, "Categoria atualizada com sucesso. Você esta sendo redirecionado, aguarde..", 200, [
                        "redirecionar" => URL . "/admin/categorias"
                    ]);
                } else {

                    Funcoes::resposta(false, "Não foi possível atualizar a categoria, por favor tente novamente mais tarde", 400);
                }
            }
        } else if ($requisicao == 'deletar') {

            $id_categoria = (int) $id_categoria;

            if ($this->categoria_model->deletar($id_categoria) != false) {

                Funcoes::resposta(true, "Categoria deletada com sucesso. Você esta sendo redirecionado, aguarde..", 200, [
                    "redirecionar" => URL . "/admin/categorias"
                ]);
            }
        } else {

            $categorias = $this->categoria_model->listar_todas_categorias();

            $dados = ["categorias" => $categorias];


            $this->view('admin/categorias/index', (object) $dados);
        }
    }


    // Publicidades
    public function publicidades($requisicao = null, $id_publicidade = null)
    {
        if ($requisicao == 'cadastrar') {

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {

                $categorias = $this->categoria_model->listar_todas_categorias();

                $dados = ["categorias" => $categorias];


                $this->view('admin/publicidades/cadastrar', (object) $dados);
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

                $dados = [
                    'titulo' => trim($dados['titulo']),
                    'subtitulo' => trim(Funcoes::protegerStringAtaqueXSS($dados['subtitulo'])),
                    'texto' => trim($dados['texto']),
                    'status' => trim(Funcoes::protegerStringAtaqueXSS($dados['status'])),
                    'categoria_id' => trim(Funcoes::protegerStringAtaqueXSS($dados['categoria_id'])),
                    'usuario_id' => trim(Funcoes::protegerStringAtaqueXSS($dados['usuario_id']))
                ];

                if (Funcoes::valida_campo($dados['titulo'])) {

                    Funcoes::resposta(false, "Campo título em branco", 400);

                } else if (Funcoes::valida_campo($dados['subtitulo'])) {

                    Funcoes::resposta(false, "Campo subtítulo em branco", 400);

                } else if (Funcoes::valida_campo($dados['texto'])) {

                    Funcoes::resposta(false, "Campo texto em branco", 400);

                } else if (Funcoes::valida_campo($dados['status'])) {

                    Funcoes::resposta(false, "Campo status em branco", 400, $dados);
                }

                // Inicia a imagem como nula.
                // Se nenhum arquivo for enviado, ela permanecerá assim.
                $dados['imagem_publicidade'] = null;

                // Verifica se um arquivo foi enviado e se não há erros.
                // (error 0 significa que o upload foi bem-sucedido até aqui)
                if (isset($_FILES['imagem_publicidade']) && $_FILES['imagem_publicidade']['error'] == 0) {

                    // 1. Define o caminho absoluto para sua pasta 'public'
                    $caminho_projeto = parse_url(URL, PHP_URL_PATH);
                    $caminho_public_abs = $_SERVER['DOCUMENT_ROOT'] . $caminho_projeto . DIRECTORY_SEPARATOR . 'public';

                    // 2. Instancia a classe com o caminho base
                    $upload = new Upload($caminho_public_abs);

                    // 3. Executa o upload
                    $uploadOk = $upload->arquivo(
                        $_FILES['imagem_publicidade'],
                        Funcoes::slug($dados['titulo']),
                        'imagens'
                    );

                    if ($uploadOk) {
                        // Se o upload deu certo, pega o nome do novo arquivo.
                        $dados['imagem_publicidade'] = $upload->getResultado();
                    } else {
                        // Se falhou, mostra o erro e para a execução.
                        Funcoes::resposta(false, $upload->getErro(), 400);
                        exit;
                    }
                }

                // Instanciando a classe publicidade com os dados válidos
                $publicidade = new PublicidadeCadastro($dados);

                if ($this->publicidade_model->armazenar($publicidade->dados_array())) {

                    Funcoes::resposta(true, "Publicidade cadastrada com sucesso. Você esta sendo redirecionado, aguarde..", 200, [
                        "redirecionar" => URL . "/admin/publicidades"
                    ]);
                } else {

                    Funcoes::resposta(false, "Não foi possível cadastrar a publicidades, por favor tente novamente mais tarde", 400);
                }
            }
        } else if ($requisicao == 'editar') {

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {

                $publicidade = $this->publicidade_model->busca_publicidade_id($id_publicidade);
                $categorias = $this->categoria_model->listar_todas_categorias();

                $dados = ["publicidade" => $publicidade, "categorias" => $categorias];


                $this->view('admin/publicidades/editar', (object) $dados);
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

                $dados = [
                    'titulo' => trim($dados['titulo']),
                    'subtitulo' => trim(Funcoes::protegerStringAtaqueXSS($dados['subtitulo'])),
                    'texto' => trim($dados['texto']),
                    'status' => trim(Funcoes::protegerStringAtaqueXSS($dados['status'])),
                    'categoria_id' => trim(Funcoes::protegerStringAtaqueXSS($dados['categoria_id'])),
                    'usuario_id' => trim(Funcoes::protegerStringAtaqueXSS($dados['usuario_id']))
                ];

                if (Funcoes::valida_campo($dados['titulo'])) {

                    Funcoes::resposta(false, "Campo título em branco", 400);

                } else if (Funcoes::valida_campo($dados['subtitulo'])) {

                    Funcoes::resposta(false, "Campo subtítulo em branco", 400);

                } else if (Funcoes::valida_campo($dados['texto'])) {

                    Funcoes::resposta(false, "Campo texto em branco", 400);

                } else if (Funcoes::valida_campo($dados['status'])) {

                    Funcoes::resposta(false, "Campo status em branco", 400, $dados);
                }

                $publicidade = $this->publicidade_model->busca_publicidade_id($id_publicidade);

                // Gerando o slug atualizado
                $dados['slug'] = Funcoes::slug($dados['titulo']);

                // Verificando se houve alteração no titulo ou no texto
                $dados['titulo'] ? Funcoes::protegerStringAtaqueXSS($dados['titulo']) : $publicidade->titulo;
                $dados['texto'] ? Funcoes::sanitizarHtmlComPurifier($dados['texto']) : $publicidade->texto;

                // Pegando a data e hora atual
                $dados['data_edicao'] = date("Y-m-d h:i:s");

                // ALTERANDO IMAGEM DO POST
                // Verifica se um novo arquivo foi enviado
                if (isset($_FILES['imagem_publicidade']) && $_FILES['imagem_publicidade']['error'] == 0) {

                    // 1. Define o caminho absoluto para sua pasta 'public'
                    $caminho_projeto = parse_url(URL, PHP_URL_PATH); // Extrai '/ritualfm'
                    $caminho_public_abs = $_SERVER['DOCUMENT_ROOT'] . $caminho_projeto . DIRECTORY_SEPARATOR . 'public';

                    // 2. Instancia a classe com o caminho base
                    $upload = new Upload($caminho_public_abs);

                    // 3. Executa o upload. Passe o nome do arquivo antigo para que a classe o delete.
                    $uploadOk = $upload->arquivo(
                        $_FILES['imagem_publicidade'],
                        $dados['slug'],
                        'imagens',
                        $publicidade->imagem_publicidade // <--- O NOME DO ARQUIVO ANTIGO VAI AQUI
                    );

                    if ($uploadOk) {
                        // Se o upload deu certo, pega o nome do novo arquivo e o salva no banco
                        $dados['imagem_publicidade'] = $upload->getResultado();
                    } else {
                        // Se falhou, mostra o erro e para a execução
                        Funcoes::resposta(false, $upload->getErro(), 400, [
                            "redirecionar" => URL . "/admin/publicidades/editar/{$id_publicidade}"
                        ]);
                        exit;
                    }
                } else {
                    // Se nenhum novo arquivo foi enviado, mantém a imagem antiga no banco.
                    $dados['imagem_publicidade'] = $publicidade->imagem_publicidade;
                }

                if ($this->publicidade_model->atualizar($dados, $id_publicidade)) {

                    Funcoes::resposta(true, "Publicidade atualizado com sucesso. Você esta sendo redirecionado, aguarde..", 200, [
                        "redirecionar" => URL . "/admin/publicidades"
                    ]);
                } else {

                    Funcoes::resposta(false, "Não foi possível atualizar a publicidade, por favor tente novamente mais tarde", 400);
                }
            }
        } else if ($requisicao == 'deletar') {

            $id_publicidade = (int) $id_publicidade;

            if ($this->publicidade_model->deletar($id_publicidade) != false) {

                Funcoes::resposta(true, "Publicidade deletada com sucesso. A página será atualizada, aguarde..", 200, [
                    "redirecionar" => URL . "/admin/publicidades"
                ]);
            }
        } else {

            $publicidades = $this->publicidade_model->listar_todas_publicidades();

            $dados = ["publicidades" => $publicidades];


            $this->view('admin/publicidades/index', (object) $dados);
        }
    }


    public function usuarios($requisicao = null, $id_usuario = null)
    {
        if ($requisicao == 'cadastrar') {

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {


                $this->view('admin/usuarios/cadastrar');


                return;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

                $dados = [
                    'nome' => trim(Funcoes::protegerStringAtaqueXSS($dados['nome']) ?? ''),
                    'email' => trim($dados['email'] ?? ''),
                    'senha' => trim($dados['senha'] ?? ''),
                    'confirma_senha' => trim($dados['confirma_senha'] ?? ''),
                    'level' => trim($dados['level'] ?? 0),
                    'status' => trim($dados['status'] ?? 0)
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

                    Funcoes::resposta(false, "Campo senha em branco", 400, $dados);
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

                    Funcoes::resposta(true, "Usuário " . strtoupper($dados['nome']) . " cadastrado com sucesso. Você esta sendo redirecionado, aguarde..", 200, [
                        'redirecionar' => URL . '/admin/usuarios'
                    ]);
                } else {

                    Funcoes::resposta(true, "Não foi possível realizar o seu cadastro, por favor tente novamente mais tarde", 400);
                }
            }
        } else if ($requisicao == 'editar') {

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {

                $usuario = $this->usuario_model->busca_usuario_id($id_usuario);

                $dados = ["usuario" => $usuario];


                $this->view('admin/usuarios/editar', (object) $dados);
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

                // Pegando o usuário pelo id
                $usuario_obj = $this->usuario_model->busca_usuario_id($id_usuario);

                // Verificando se o novo e-mail é válido
                if (Funcoes::validarEmail($dados['email']) != false) {

                    // Verificando se o novo e-mail já existe no banco de dados
                    if ($this->usuario_model->consulta_email($dados['email'])) {

                        $usuario_obj->nome = (!empty($dados['nome']) ? Funcoes::protegerStringAtaqueXSS($dados['nome']) : $usuario_obj->nome);
                        $usuario_obj->email = (!empty($dados['email']) ? $dados['email'] : $usuario_obj->email);
                        $usuario_obj->senha = (!empty($dados['senha']) ? Funcoes::gerarSenha($dados['senha']) : $usuario_obj->senha);
                        $usuario_obj->status = (isset($dados['status']) ? (int)$dados['status'] : $usuario_obj->status);
                        $usuario_obj->level = (isset($dados['level']) ? (int)$dados['level'] : $usuario_obj->level);
                        $usuario_obj->atualizado_em = date('Y-m-d H:i:s');

                        // Transformando o objeto em um array, para assim salvar no banco de dados as infos do usuario
                        $usuario_array = (array) $usuario_obj;

                        if ($this->usuario_model->atualizar($usuario_array, $id_usuario)) {

                            Funcoes::resposta(true, "Usuário atualizado com sucesso, a página será atualizada, aguarde..", 200, [
                                'redirecionar' => URL . '/admin/usuarios/editar/' . $id_usuario,
                            ]);
                        } else {

                            Funcoes::resposta(false, "Ocorreu um erro de sistema em tentar editar o usuário.", 400);
                        }
                    } else {

                        Funcoes::resposta(false, "0 E-mail informado já consta no sistema, utilize outro e-mail.");
                    }
                } else {

                    Funcoes::resposta(false, "O e-mail informado é inválido.", 400);
                }
            }
        } else if ($requisicao === 'detalhes') {

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {

                $usuario = $this->usuario_model->busca_usuario_id($id_usuario);

                $dados = ["usuario" => $usuario];


                $this->view('admin/usuarios/detalhes', (object) $dados);
            }
        } else if ($requisicao === 'deletar') {

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $id_usuario = (int) $id_usuario;

                if ($this->usuario_model->deletar($id_usuario) != false) {

                    Funcoes::resposta(true, "Usuário deletado com sucesso. A página será atualizada, aguarde..", 200, [
                        "redirecionar" => URL . "/admin/usuarios"
                    ]);
                }
            }
        } else {

            $usuarios = $this->usuario_model->listar_todos_usuarios();

            $dados = ["usuarios" => $usuarios];


            $this->view('admin/usuarios/index', (object) $dados);
        }
    }

    // Comentários
    public function comentarios($requisicao = null, $id_comentario = null)
    {
        if ($requisicao == 'aprovar') {

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $id_comentario = (int) $id_comentario;

                if ($this->comentario_model->aprovar($id_comentario) != false) {

                    Funcoes::resposta(true, "Comentário aprovado com sucesso. A página será atualizada, aguarde..", 200, [
                        "redirecionar" => URL . "/admin/comentarios"
                    ]);
                }
            }
        } else if ($requisicao == 'deletar') {

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $id_comentario = (int) $id_comentario;

                if ($this->comentario_model->deletar($id_comentario) != false) {

                    Funcoes::resposta(true, "Comentário deletado com sucesso. A página será atualizada, aguarde..", 200, [
                        "redirecionar" => URL . "/admin/comentarios"
                    ]);
                }
            }
        } else {

            $comentarios = $this->comentario_model->lista_todos_comentarios();
            $dados = ['comentarios' => $comentarios];

            $this->view('admin/comentarios/index', (object) $dados);
        }
    }


    // Contato
    public function contatos($requisicao = null, $id_contato = null)
    {
        if ($requisicao == 'visto') {

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $id_contato = (int) $id_contato;

                if ($this->contato_model->atualizar($id_contato) != false) {

                    Funcoes::resposta(true, "Mensagem atualizada com sucesso. A página será atualizada, aguarde..", 200, [
                        "redirecionar" => URL . "/admin/contatos"
                    ]);
                }
            }
        } else if ($requisicao == 'deletar') {

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $id_contato = (int) $id_contato;

                if ($this->contato_model->deletar($id_contato) != false) {

                    Funcoes::resposta(true, "Mensagem deletada com sucesso. A página será atualizada, aguarde..", 200, [
                        "redirecionar" => URL . "/admin/contatos"
                    ]);
                }
            }
        } else {

            $contatos = $this->contato_model->listar_todos_contatos();

            $dados = ['contatos' => $contatos];


            $this->view('admin/contatos/index', (object) $dados);
        }
    }



    // Eventos
    public function eventos($requisicao = null, $id_evento = null)
    {
        if ($requisicao == 'cadastrar') {

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {

                // $categorias = $this->categoria_model->listar_todas_categorias();
                // $dados = ['categorias' => $categorias];

                $this->view('admin/eventos/cadastrar');
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

                $dados = [
                    'titulo' => trim($dados['titulo']),
                    'texto' => trim($dados['texto']),
                    'atracao' => trim(Funcoes::protegerStringAtaqueXSS($dados['atracao'])),
                    'data' => trim(Funcoes::protegerStringAtaqueXSS($dados['data'])),
                    'local' => trim(Funcoes::protegerStringAtaqueXSS($dados['local'])),
                    'video_id_youtube' => trim(Funcoes::protegerStringAtaqueXSS($dados['video_id_youtube'])),
                    'usuario_id' => trim(Funcoes::protegerStringAtaqueXSS($dados['usuario_id'])),
                    'status' => trim(Funcoes::protegerStringAtaqueXSS($dados['status']))
                ];

                if (Funcoes::valida_campo($dados['titulo'])) {

                    Funcoes::resposta(false, "Campo título em branco", 400);

                } else if (Funcoes::valida_campo($dados['texto'])) {

                    Funcoes::resposta(false, "Campo texto em branco", 400);

                } else if (Funcoes::valida_campo($dados['data'])) {

                    Funcoes::resposta(false, "Campo data em branco", 400);

                } else if (Funcoes::valida_campo($dados['local'])) {

                    Funcoes::resposta(false, "Campo local em branco", 400);

                } else if (Funcoes::valida_campo($dados['video_id_youtube'])) {

                    Funcoes::resposta(false, "Campo video youtube em branco", 400);

                } else if (Funcoes::valida_campo($dados['video_id_youtube'])) {

                    Funcoes::resposta(false, "Campo video youtube em branco", 400);

                } else if (Funcoes::valida_campo($dados['status'])) {

                    Funcoes::resposta(false, "Campo status em branco", 400);
                }

                // Inicia a imagem como nula
                // Se nenhum arquivo for enviado, ela permanecerá assim
                $dados['imagem_evento'] = null;

                // Verifica se um arquivo foi enviado e se não há erros
                // (error 0 significa que o upload foi bem-sucedido até aqui)
                if (isset($_FILES['imagem_evento']) && $_FILES['imagem_evento']['error'] == 0) {

                    // 1. Define o caminho absoluto para sua pasta 'public'
                    $caminho_projeto = parse_url(URL, PHP_URL_PATH);
                    $caminho_public_abs = $_SERVER['DOCUMENT_ROOT'] . $caminho_projeto . DIRECTORY_SEPARATOR . 'public';

                    // 2. Instancia a classe com o caminho base
                    $upload = new Upload($caminho_public_abs);

                    // 3. Executa o upload
                    $uploadOk = $upload->arquivo(
                        $_FILES['imagem_evento'],
                        Funcoes::slug($dados['titulo']),
                        'imagens'
                    );

                    if ($uploadOk) {
                        // Se o upload deu certo, pega o nome do novo arquivo
                        $dados['imagem_evento'] = $upload->getResultado();
                    } else {
                        // Se falhou, mostra o erro e para a execução
                        Funcoes::resposta(false, $upload->getErro(), 400);
                        exit;
                    }
                }

                // Instanciando a classe evento com os dados válidos
                $evento = new EventoCadastro($dados);

                if ($this->evento_model->armazenar($evento->dados_array())) {

                    Funcoes::resposta(true, "Evento cadastrado com sucesso. Você esta sendo redirecionado, aguarde..", 200, [
                        "redirecionar" => URL . "/admin/eventos"
                    ]);

                } else {

                    Funcoes::resposta(false, "Não foi possível cadastrar o evento, por favor tente novamente mais tarde", 400);
                }
            }
        } else if ($requisicao == 'editar') {

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {

                $evento = $this->evento_model->busca_evento_id($id_evento);

                $dados = ["evento" => $evento];


                $this->view('admin/eventos/editar', (object) $dados);
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

                $dados = [
                    'titulo' => trim($dados['titulo']),
                    'texto' => trim($dados['texto']),
                    'atracao' => trim(Funcoes::protegerStringAtaqueXSS($dados['atracao'])),
                    'data' => trim(Funcoes::protegerStringAtaqueXSS($dados['data'])),
                    'local' => trim(Funcoes::protegerStringAtaqueXSS($dados['local'])),
                    'video_id_youtube' => trim(Funcoes::protegerStringAtaqueXSS($dados['video_id_youtube'])),
                    'usuario_id' => trim(Funcoes::protegerStringAtaqueXSS($dados['usuario_id'])),
                    'status' => trim(Funcoes::protegerStringAtaqueXSS($dados['status']))
                ];

                if (Funcoes::valida_campo($dados['titulo'])) {

                    Funcoes::resposta(false, "Campo título em branco", 400);

                } else if (Funcoes::valida_campo($dados['texto'])) {

                    Funcoes::resposta(false, "Campo texto em branco", 400);

                } else if (Funcoes::valida_campo($dados['data'])) {

                    Funcoes::resposta(false, "Campo data em branco", 400);

                } else if (Funcoes::valida_campo($dados['local'])) {

                    Funcoes::resposta(false, "Campo local em branco", 400);

                } else if (Funcoes::valida_campo($dados['video_id_youtube'])) {

                    Funcoes::resposta(false, "Campo video youtube em branco", 400);

                } else if (Funcoes::valida_campo($dados['video_id_youtube'])) {

                    Funcoes::resposta(false, "Campo video youtube em branco", 400);

                } else if (Funcoes::valida_campo($dados['status'])) {

                    Funcoes::resposta(false, "Campo status em branco", 400);
                }

                $evento = $this->evento_model->busca_evento_id($id_evento);

                // Gerando o slug atualizado
                $dados['slug'] = Funcoes::slug($dados['titulo']) . '-' . date("Y-m-d");

                // Verificando se houve alteração no titulo ou no texto
                $dados['titulo'] ? Funcoes::protegerStringAtaqueXSS($dados['titulo']) : $evento->titulo;
                $dados['texto'] ? Funcoes::sanitizarHtmlComPurifier($dados['texto']) : $evento->texto;

                // Pegando a data e hora atual
                $dados['data_edicao'] = date("Y-m-d h:i:s");

                // ALTERANDO IMAGEM DO EVENTO
                // Verifica se um novo arquivo foi enviado
                if (isset($_FILES['imagem_evento']) && $_FILES['imagem_evento']['error'] == 0) {

                    // 1. Define o caminho absoluto para sua pasta 'public'
                    $caminho_projeto = parse_url(URL, PHP_URL_PATH); // Extrai '/ritualfm'
                    $caminho_public_abs = $_SERVER['DOCUMENT_ROOT'] . $caminho_projeto . DIRECTORY_SEPARATOR . 'public';

                    // 2. Instancia a classe com o caminho base
                    $upload = new Upload($caminho_public_abs);

                    // 3. Executa o upload. Passe o nome do arquivo antigo para que a classe o delete.
                    $uploadOk = $upload->arquivo(
                        $_FILES['imagem_evento'],
                       $dados['slug'],
                        'imagens',
                        $evento->imagem_evento // <--- O NOME DO ARQUIVO ANTIGO VAI AQUI
                    );

                    if ($uploadOk) {
                        // Se o upload deu certo, pega o nome do novo arquivo e o salva no banco
                        $dados['imagem_evento'] = $upload->getResultado();
                    } else {
                        // Se falhou, mostra o erro e para a execução
                        Funcoes::resposta(false, $upload->getErro(), 400, [
                            "redirecionar" => URL . "/admin/eventos/editar/{$id_evento}"
                        ]);
                        exit;
                    }
                } else {
                    // Se nenhum novo arquivo foi enviado, mantém a imagem antiga no banco.
                    $dados['imagem_evento'] = $evento->imagem_evento;
                }

                if ($this->evento_model->atualizar($dados, $id_evento)) {

                    Funcoes::resposta(true, "Evento atualizado com sucesso. Você esta sendo redirecionado, aguarde..", 200, [
                        "redirecionar" => URL . "/admin/eventos"
                    ]);
                } else {

                    Funcoes::resposta(false, "Não foi possível atualizar o evento, por favor tente novamente mais tarde", 400);
                }
            }
        } else if ($requisicao == 'deletar') {

            $id_evento = (int) $id_evento;

            if ($this->evento_model->deletar($id_evento) != false) {

                Funcoes::resposta(true, "Evento deletado com sucesso. A página será atualizada, aguarde..", 200, [
                    "redirecionar" => URL . "/admin/eventos"
                ]);
            }
        } else {

            $eventos = $this->evento_model->listar_todos_eventos();

            $dados = ["eventos" => $eventos];

            $this->view('admin/eventos/index', (object) $dados);
        }
    }



    // Musicas
    public function musicas($requisicao = null, $id_musica = null)
    {
        if ($requisicao == 'cadastrar') {

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {

                $categorias = $this->categoria_model->listar_todas_categorias();
                $dados = ['categorias' => $categorias];

                $this->view('admin/musicas/cadastrar', (object) $dados);
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

                $dados = [
                    'titulo' => trim($dados['titulo']),
                    'subtitulo' => trim(Funcoes::protegerStringAtaqueXSS($dados['subtitulo'])),
                    'texto' => trim($dados['texto']),
                    'artista' => trim(Funcoes::protegerStringAtaqueXSS($dados['artista'])),
                    'album' => trim(Funcoes::protegerStringAtaqueXSS($dados['album'])),
                    'lancamento' => trim(Funcoes::protegerStringAtaqueXSS($dados['lancamento'])),
                    'player_id' => trim(Funcoes::protegerStringAtaqueXSS($dados['player_id'])),
                    'categoria_id' => trim(Funcoes::protegerStringAtaqueXSS($dados['categoria_id'])),
                    'usuario_id' => trim(Funcoes::protegerStringAtaqueXSS($dados['usuario_id'])),
                    'status' => trim(Funcoes::protegerStringAtaqueXSS($dados['status']))
                ];

                if (Funcoes::valida_campo($dados['titulo'])) {

                    Funcoes::resposta(false, "Campo título em branco", 400);

                } else if (Funcoes::valida_campo($dados['subtitulo'])) {

                    Funcoes::resposta(false, "Campo subtítulo em branco", 400);

                } else if (Funcoes::valida_campo($dados['texto'])) {

                    Funcoes::resposta(false, "Campo texto em branco", 400);

                } else if (Funcoes::valida_campo($dados['artista'])) {

                    Funcoes::resposta(false, "Campo artista em branco", 400, $dados);

                } else if (Funcoes::valida_campo($dados['album'])) {

                    Funcoes::resposta(false, "Campo album em branco", 400, $dados);

                } else if (Funcoes::valida_campo($dados['lancamento'])) {

                    Funcoes::resposta(false, "Campo lançamento em branco", 400, $dados);

                } else if (Funcoes::valida_campo($dados['player_id'])) {

                    Funcoes::resposta(false, "Campo player em branco", 400, $dados);

                } else if (Funcoes::valida_campo($dados['categoria_id'])) {

                    Funcoes::resposta(false, "Campo categoria em branco", 400, $dados);

                } else if (Funcoes::valida_campo($dados['status'])) {

                    Funcoes::resposta(false, "Campo status em branco", 400, $dados);

                }

                // Inicia a imagem como nula
                // Se nenhum arquivo for enviado, ela permanecerá assim
                $dados['imagem_musica'] = null;

                // Verifica se um arquivo foi enviado e se não há erros
                // (error 0 significa que o upload foi bem-sucedido até aqui)
                if (isset($_FILES['imagem_musica']) && $_FILES['imagem_musica']['error'] == 0) {

                    // 1. Define o caminho absoluto para sua pasta 'public'
                    $caminho_projeto = parse_url(URL, PHP_URL_PATH);
                    $caminho_public_abs = $_SERVER['DOCUMENT_ROOT'] . $caminho_projeto . DIRECTORY_SEPARATOR . 'public';

                    // 2. Instancia a classe com o caminho base
                    $upload = new Upload($caminho_public_abs);

                    // 3. Executa o upload
                    $uploadOk = $upload->arquivo(
                        $_FILES['imagem_musica'],
                        Funcoes::slug($dados['titulo']),
                        'imagens'
                    );

                    if ($uploadOk) {
                        // Se o upload deu certo, pega o nome do novo arquivo
                        $dados['imagem_musica'] = $upload->getResultado();

                    } else {
                        // Se falhou, mostra o erro e para a execução
                        Funcoes::resposta(false, $upload->getErro(), 400);
                        exit;
                    }
                }

                // Instanciando a classe música com os dados válidos
                $musica = new MusicaCadastro($dados);

                if ($this->musica_model->armazenar($musica->dados_array())) {

                    Funcoes::resposta(true, "Conteúdo de música cadastrado com sucesso. Você esta sendo redirecionado, aguarde..", 200, [
                        "redirecionar" => URL . "/admin/musicas"
                    ]);
                } else {

                    Funcoes::resposta(false, "Não foi possível cadastrar o conteúdo de música, por favor tente novamente mais tarde", 400);
                }
            }
        } else if ($requisicao == 'editar') {

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {

                $musica = $this->musica_model->busca_musica_id($id_musica);
                $categorias = $this->categoria_model->listar_todas_categorias();

                $dados = ["musica" => $musica, "categorias" => $categorias];


                $this->view('admin/musicas/editar', (object) $dados);
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

                $dados = [
                    'titulo' => trim($dados['titulo']),
                    'subtitulo' => trim(Funcoes::protegerStringAtaqueXSS($dados['subtitulo'])),
                    'texto' => trim($dados['texto']),
                    'artista' => trim(Funcoes::protegerStringAtaqueXSS($dados['artista'])),
                    'album' => trim(Funcoes::protegerStringAtaqueXSS($dados['album'])),
                    'lancamento' => trim(Funcoes::protegerStringAtaqueXSS($dados['lancamento'])),
                    'player_id' => trim(Funcoes::protegerStringAtaqueXSS($dados['player_id'])),
                    'categoria_id' => trim(Funcoes::protegerStringAtaqueXSS($dados['categoria_id'])),
                    'usuario_id' => trim(Funcoes::protegerStringAtaqueXSS($dados['usuario_id'])),
                    'status' => trim(Funcoes::protegerStringAtaqueXSS($dados['status']))
                ];

                 if (Funcoes::valida_campo($dados['titulo'])) {

                    Funcoes::resposta(false, "Campo título em branco", 400);

                } else if (Funcoes::valida_campo($dados['subtitulo'])) {

                    Funcoes::resposta(false, "Campo subtítulo em branco", 400, $dados);

                } else if (Funcoes::valida_campo($dados['texto'])) {

                    Funcoes::resposta(false, "Campo texto em branco", 400);

                } else if (Funcoes::valida_campo($dados['artista'])) {

                    Funcoes::resposta(false, "Campo artista em branco", 400, $dados);

                } else if (Funcoes::valida_campo($dados['album'])) {

                    Funcoes::resposta(false, "Campo album em branco", 400, $dados);

                } else if (Funcoes::valida_campo($dados['lancamento'])) {

                    Funcoes::resposta(false, "Campo lançamento em branco", 400, $dados);

                } else if (Funcoes::valida_campo($dados['player_id'])) {

                    Funcoes::resposta(false, "Campo player id em branco", 400, $dados);

                } else if (Funcoes::valida_campo($dados['categoria_id'])) {

                    Funcoes::resposta(false, "Campo categoria em branco", 400, $dados);

                } else if (Funcoes::valida_campo($dados['status'])) {

                    Funcoes::resposta(false, "Campo status em branco", 400, $dados);

                }
                
                $musica = $this->musica_model->busca_musica_id($id_musica);

                // Gerando o slug atualizado
                $dados['slug'] = Funcoes::slug($dados['titulo']) . '-' . date("Y-m-d");

                // Verificando se houve alteração no titulo ou no texto
                $dados['titulo'] ? Funcoes::protegerStringAtaqueXSS($dados['titulo']) : $musica->titulo;
                $dados['texto'] ? Funcoes::sanitizarHtmlComPurifier($dados['texto']) : $musica->texto;

                // Pegando a data e hora atual
                $dados['data_edicao'] = date("Y-m-d h:i:s");

                // ALTERANDO IMAGEM DO MÚSICA
                // Verifica se um novo arquivo foi enviado
                if (isset($_FILES['imagem_musica']) && $_FILES['imagem_musica']['error'] == 0) {

                    // 1. Define o caminho absoluto para sua pasta 'public'
                    $caminho_projeto = parse_url(URL, PHP_URL_PATH); // Extrai '/ritualfm'
                    $caminho_public_abs = $_SERVER['DOCUMENT_ROOT'] . $caminho_projeto . DIRECTORY_SEPARATOR . 'public';

                    // 2. Instancia a classe com o caminho base
                    $upload = new Upload($caminho_public_abs);

                    // 3. Executa o upload. Passe o nome do arquivo antigo para que a classe o delete.
                    $uploadOk = $upload->arquivo(
                        $_FILES['imagem_musica'],
                        $dados['slug'],
                        'imagens',
                        $musica->imagem_musica // <--- O NOME DO ARQUIVO ANTIGO VAI AQUI
                    );

                    if ($uploadOk) {
                        // Se o upload deu certo, pega o nome do novo arquivo e o salva no banco
                        $dados['imagem_musica'] = $upload->getResultado();
                    } else {
                        // Se falhou, mostra o erro e para a execução
                        Funcoes::resposta(false, $upload->getErro(), 400, [
                            "redirecionar" => URL . "/admin/musicas/editar/{$id_musica}"
                        ]);
                        exit;
                    }
                } else {
                    // Se nenhum novo arquivo foi enviado, mantém a imagem antiga no banco.
                    $dados['imagem_musica'] = $musica->imagem_musica;
                }

                if ($this->musica_model->atualizar($dados, $id_musica)) {

                    Funcoes::resposta(true, "Conteúdo de música atualizado com sucesso. Você esta sendo redirecionado, aguarde..", 200, [
                        "redirecionar" => URL . "/admin/musicas"
                    ]);
                    
                } else {

                    Funcoes::resposta(false, "Não foi possível atualizar o conteúdo de música, por favor tente novamente mais tarde", 400);
                }
            }
        } else if ($requisicao == 'deletar') {

            $id_musica = (int) $id_musica;

            if ($this->musica_model->deletar($id_musica) != false) {

                Funcoes::resposta(true, "Conteúdo de música deletado com sucesso. A página será atualizada, aguarde..", 200, [
                    "redirecionar" => URL . "/admin/musicas"
                ]);
            }
        } else {

            $musicas = $this->musica_model->listar_todas_musicas();
            $categorias = $this->categoria_model->listar_todas_categorias();

            $dados = ["musicas" => $musicas, "categorias" => $categorias];


            $this->view('admin/musicas/index', (object) $dados);
        }
    }



    // Sobre
    public function sobre($requisicao = null, $id_sobre = null)
    {
        if ($requisicao == 'cadastrar') {

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {

                $this->view('admin/sobre/cadastrar');
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

                $dados = [
                    'titulo' => trim($dados['titulo']),
                    'texto' => trim($dados['texto']),
                    'usuario_id' => trim(Funcoes::protegerStringAtaqueXSS($dados['usuario_id'])),
                    'status' => trim(Funcoes::protegerStringAtaqueXSS($dados['status']))
                ];

                if (Funcoes::valida_campo($dados['titulo'])) {

                    Funcoes::resposta(false, "Campo título em branco", 400);

                } else if (Funcoes::valida_campo($dados['texto'])) {

                    Funcoes::resposta(false, "Campo texto em branco", 400);

                } else if (Funcoes::valida_campo($dados['status'])) {

                    Funcoes::resposta(false, "Campo status em branco", 400);

                }

                // Url amigável
                $dados['slug'] = Funcoes::slug($dados['titulo']);

                // Passa o conteúdo pela sanatização
                $dados['texto'] = Funcoes::sanitizarHtmlComPurifier($dados['texto']);
                $dados['titulo'] = Funcoes::protegerStringAtaqueXSS($dados['titulo']);

                if ($this->sobre_model->armazenar($dados)) {

                    Funcoes::resposta(true, "Conteúdo de Sobre cadastrado com sucesso. Você esta sendo redirecionado, aguarde..", 200, [
                        "redirecionar" => URL . "/admin/sobre"
                    ]);
                } else {

                    Funcoes::resposta(false, "Não foi possível cadastrar o conteúdo de Sobre, por favor tente novamente mais tarde", 400);
                }
            }
        } else if ($requisicao == 'editar') {

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {

                $sobre = $this->sobre_model->busca_sobre_id($id_sobre);

                $dados = ["sobre" => $sobre];

                $this->view('admin/sobre/editar', (object) $dados);
            }


            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

                $dados = [
                    'titulo' => trim($dados['titulo']),
                    'texto' => trim($dados['texto']),
                    'usuario_id' => trim(Funcoes::protegerStringAtaqueXSS($dados['usuario_id'])),
                    'status' => trim(Funcoes::protegerStringAtaqueXSS($dados['status']))
                ];

                if (Funcoes::valida_campo($dados['titulo'])) {

                    Funcoes::resposta(false, "Campo título em branco", 400);

                } else if (Funcoes::valida_campo($dados['texto'])) {

                    Funcoes::resposta(false, "Campo texto em branco", 400);

                } else if (Funcoes::valida_campo($dados['status'])) {

                    Funcoes::resposta(false, "Campo status em branco", 400);

                }

                $sobre = $this->sobre_model->busca_sobre_id($id_sobre);

                // Gerando o slug atualizado
                $dados['slug'] = Funcoes::slug($dados['titulo']);

                // Verificando se houve alteração no titulo ou no texto
                $dados['titulo'] ? Funcoes::protegerStringAtaqueXSS($dados['titulo']) : $sobre->titulo;
                $dados['texto'] ? Funcoes::sanitizarHtmlComPurifier($dados['texto']) : $sobre->texto;

                // Pegando a data e hora atual
                $dados['data_edicao'] = date("Y-m-d h:i:s");

                if ($this->sobre_model->atualizar($dados, $id_sobre)) {

                    Funcoes::resposta(true, "Conteúdo de Sobre-nós atualizado com sucesso. Você esta sendo redirecionado, aguarde..", 200, [
                        "redirecionar" => URL . "/admin/sobre"
                    ]);
                } else {

                    Funcoes::resposta(false, "Não foi possível atualizar o conteúdo de Sobre-nós, por favor tente novamente mais tarde", 400);
                }
            }
        } else if ($requisicao == 'deletar') {

            $id_sobre = (int) $id_sobre;

            if ($this->sobre_model->deletar($id_sobre) != false) {

                Funcoes::resposta(true, "Conteúdo de Sobre deletado com sucesso. Você esta sendo redirecionado, aguarde..", 200, [
                    "redirecionar" => URL . "/admin/sobre"
                ]);
            }
        } else {

            $sobre = $this->sobre_model->listar_todos_sobre();

            $dados = ["sobres" => $sobre];

            $this->view('admin/sobre/index', (object) $dados);
        }
    }


    // Termos
    public function termos($requisicao = null, $id_termo = null)
    {
        if ($requisicao == 'cadastrar') {

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {

                $this->view('admin/termos/cadastrar');
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

                $dados = [
                    'titulo' => trim($dados['titulo']),
                    'texto' => trim($dados['texto']),
                    'usuario_id' => trim(Funcoes::protegerStringAtaqueXSS($dados['usuario_id'])),
                    'status' => trim(Funcoes::protegerStringAtaqueXSS($dados['status']))
                ];

                if (Funcoes::valida_campo($dados['titulo'])) {

                    Funcoes::resposta(false, "Campo título em branco", 400);

                } else if (Funcoes::valida_campo($dados['texto'])) {

                    Funcoes::resposta(false, "Campo texto em branco", 400);

                } else if (Funcoes::valida_campo($dados['status'])) {

                    Funcoes::resposta(false, "Campo status em branco", 400);

                }

                // Url amigável
                $dados['slug'] = Funcoes::slug($dados['titulo']);

                // Passa o conteúdo pela sanatização
                $dados['texto'] = Funcoes::sanitizarHtmlComPurifier($dados['texto']);
                $dados['titulo'] = Funcoes::protegerStringAtaqueXSS($dados['titulo']);

                if ($this->termo_model->armazenar($dados)) {

                    Funcoes::resposta(true, "Conteúdo de Termo e Condições cadastrado com sucesso. Você esta sendo redirecionado, aguarde..", 200, [
                        "redirecionar" => URL . "/admin/termos"
                    ]);
                } else {

                    Funcoes::resposta(false, "Não foi possível cadastrar o conteúdo de Termo e Condições, por favor tente novamente mais tarde", 400);
                }
            }
        } else if ($requisicao == 'editar') {

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {

                $termo = $this->termo_model->busca_termo_id($id_termo);

                $dados = ["termo" => $termo];

                $this->view('admin/termos/editar', (object) $dados);
            }


            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

                $dados = [
                    'titulo' => trim($dados['titulo']),
                    'texto' => trim($dados['texto']),
                    'usuario_id' => trim(Funcoes::protegerStringAtaqueXSS($dados['usuario_id'])),
                    'status' => trim(Funcoes::protegerStringAtaqueXSS($dados['status']))
                ];

                if (Funcoes::valida_campo($dados['titulo'])) {

                    Funcoes::resposta(false, "Campo título em branco", 400);

                } else if (Funcoes::valida_campo($dados['texto'])) {

                    Funcoes::resposta(false, "Campo texto em branco", 400);

                } else if (Funcoes::valida_campo($dados['status'])) {

                    Funcoes::resposta(false, "Campo status em branco", 400);

                }

                $termo = $this->termo_model->busca_termo_id($id_termo);

                // Gerando o slug atualizado
                $dados['slug'] = Funcoes::slug($dados['titulo']);

                // Verificando se houve alteração no titulo ou no texto
                $dados['titulo'] ? Funcoes::protegerStringAtaqueXSS($dados['titulo']) : $termo->titulo;
                $dados['texto'] ? Funcoes::sanitizarHtmlComPurifier($dados['texto']) : $termo->texto;

                // Pegando a data e hora atual
                $dados['data_edicao'] = date("Y-m-d h:i:s");

                if ($this->termo_model->atualizar($dados, $id_termo)) {

                    Funcoes::resposta(true, "Conteúdo de Termos e Condições atualizado com sucesso. Você esta sendo redirecionado, aguarde..", 200, [
                        "redirecionar" => URL . "/admin/termos"
                    ]);
                } else {

                    Funcoes::resposta(false, "Não foi possível atualizar o conteúdo de Termos e Condições, por favor tente novamente mais tarde", 400);
                }
            }
        } else if ($requisicao == 'deletar') {

            $id_termo = (int) $id_termo;

            if ($this->termo_model->deletar($id_termo) != false) {

                Funcoes::resposta(true, "Conteúdo de Termos e Condições deletado com sucesso. Você esta sendo redirecionado, aguarde..", 200, [
                    "redirecionar" => URL . "/admin/termos"
                ]);
            }
        } else {

            $termo = $this->termo_model->listar_todos_termos();

            $dados = ["termos" => $termo];

            $this->view('admin/termos/index', (object) $dados);
        }
    }



    // Anuncie
    public function anuncie($requisicao = null, $id_anuncie = null)
    {
        if ($requisicao == 'cadastrar') {

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {

                $this->view('admin/anuncie/cadastrar');
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

                $dados = [
                    'titulo' => trim($dados['titulo']),
                    'texto' => trim($dados['texto']),
                    'endereco' => trim(Funcoes::protegerStringAtaqueXSS($dados['endereco'])),
                    'email' => trim(Funcoes::protegerStringAtaqueXSS($dados['email'])),
                    'telefone' => trim(Funcoes::protegerStringAtaqueXSS($dados['telefone'])),
                    'nome' => trim(Funcoes::protegerStringAtaqueXSS($dados['nome'])),
                    'cargo' => trim(Funcoes::protegerStringAtaqueXSS($dados['cargo'])),
                    'usuario_id' => trim(Funcoes::protegerStringAtaqueXSS($dados['usuario_id'])),
                    'status' => trim(Funcoes::protegerStringAtaqueXSS($dados['status']))
                ];

                if (Funcoes::valida_campo($dados['titulo'])) {

                    Funcoes::resposta(false, "Campo título em branco", 400);

                } else if (Funcoes::valida_campo($dados['texto'])) {

                    Funcoes::resposta(false, "Campo texto em branco", 400);

                } else if (Funcoes::valida_campo($dados['endereco'])) {

                    Funcoes::resposta(false, "Campo endereço em branco", 400);

                } else if (Funcoes::valida_campo($dados['email'])) {

                    Funcoes::resposta(false, "Campo email em branco", 400);

                } else if (Funcoes::valida_campo($dados['telefone'])) {

                    Funcoes::resposta(false, "Campo telefone em branco", 400);

                } else if (Funcoes::valida_campo($dados['nome'])) {

                    Funcoes::resposta(false, "Campo nome em branco", 400);

                } else if (Funcoes::valida_campo($dados['cargo'])) {

                    Funcoes::resposta(false, "Campo cargo em branco", 400);

                } else if (Funcoes::valida_campo($dados['status'])) {

                    Funcoes::resposta(false, "Campo status em branco", 400);

                }

                // Url amigável
                $dados['slug'] = Funcoes::slug($dados['titulo']);

                // Passa o conteúdo pela sanatização
                $dados['texto'] = Funcoes::sanitizarHtmlComPurifier($dados['texto']);
                $dados['titulo'] = Funcoes::protegerStringAtaqueXSS($dados['titulo']);

                if ($this->anuncie_model->armazenar($dados)) {

                    Funcoes::resposta(true, "Conteúdo de Anuncie cadastrado com sucesso. Você esta sendo redirecionado, aguarde..", 200, [
                        "redirecionar" => URL . "/admin/anuncie"
                    ]);
                } else {

                    Funcoes::resposta(false, "Não foi possível cadastrar o conteúdo de Anuncie, por favor tente novamente mais tarde", 400);
                }
            }
        } else if ($requisicao == 'editar') {

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {

                $anuncie = $this->anuncie_model->busca_anuncie_id($id_anuncie);

                $dados = ["anuncie" => $anuncie];

                $this->view('admin/anuncie/editar', (object) $dados);
            }


            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

                $dados = [
                    'titulo' => trim($dados['titulo']),
                    'texto' => trim($dados['texto']),
                    'endereco' => trim(Funcoes::protegerStringAtaqueXSS($dados['endereco'])),
                    'email' => trim(Funcoes::protegerStringAtaqueXSS($dados['email'])),
                    'telefone' => trim(Funcoes::protegerStringAtaqueXSS($dados['telefone'])),
                    'nome' => trim(Funcoes::protegerStringAtaqueXSS($dados['nome'])),
                    'cargo' => trim(Funcoes::protegerStringAtaqueXSS($dados['cargo'])),
                    'usuario_id' => trim(Funcoes::protegerStringAtaqueXSS($dados['usuario_id'])),
                    'status' => trim(Funcoes::protegerStringAtaqueXSS($dados['status']))
                ];

                if (Funcoes::valida_campo($dados['titulo'])) {

                    Funcoes::resposta(false, "Campo título em branco", 400);

                } else if (Funcoes::valida_campo($dados['texto'])) {

                    Funcoes::resposta(false, "Campo texto em branco", 400);

                } else if (Funcoes::valida_campo($dados['endereco'])) {

                    Funcoes::resposta(false, "Campo endereço em branco", 400);

                } else if (Funcoes::valida_campo($dados['email'])) {

                    Funcoes::resposta(false, "Campo email em branco", 400);

                } else if (Funcoes::valida_campo($dados['telefone'])) {

                    Funcoes::resposta(false, "Campo telefone em branco", 400);

                } else if (Funcoes::valida_campo($dados['nome'])) {

                    Funcoes::resposta(false, "Campo nome em branco", 400);

                } else if (Funcoes::valida_campo($dados['cargo'])) {

                    Funcoes::resposta(false, "Campo cargo em branco", 400);

                } else if (Funcoes::valida_campo($dados['status'])) {

                    Funcoes::resposta(false, "Campo status em branco", 400);

                }

                $anuncie = $this->anuncie_model->busca_anuncie_id($id_anuncie);

                // Gerando o slug atualizado
                $dados['slug'] = Funcoes::slug($dados['titulo']);

                // Verificando se houve alteração no titulo ou no texto
                $dados['titulo'] ? Funcoes::protegerStringAtaqueXSS($dados['titulo']) : $anuncie->titulo;
                $dados['texto'] ? Funcoes::sanitizarHtmlComPurifier($dados['texto']) : $anuncie->texto;
                $dados['endereco'] ? Funcoes::sanitizarHtmlComPurifier($dados['endereco']) : $anuncie->endereco;
                $dados['email'] ? Funcoes::sanitizarHtmlComPurifier($dados['email']) : $anuncie->email;
                $dados['telefone'] ? Funcoes::sanitizarHtmlComPurifier($dados['telefone']) : $anuncie->telefone;
                $dados['nome'] ? Funcoes::sanitizarHtmlComPurifier($dados['nome']) : $anuncie->nome;
                $dados['cargo'] ? Funcoes::sanitizarHtmlComPurifier($dados['cargo']) : $anuncie->cargo;

                // Pegando a data e hora atual
                $dados['data_edicao'] = date("Y-m-d h:i:s");

                if ($this->anuncie_model->atualizar($dados, $id_anuncie)) {

                    Funcoes::resposta(true, "Conteúdo de Anuncie atualizado com sucesso. Você esta sendo redirecionado, aguarde..", 200, [
                        "redirecionar" => URL . "/admin/anuncie"
                    ]);
                } else {

                    Funcoes::resposta(false, "Não foi possível atualizar o conteúdo de Anuncie, por favor tente novamente mais tarde", 400);
                }
            }
        } else if ($requisicao == 'deletar') {

            $id_anuncie = (int) $id_anuncie;

            if ($this->anuncie_model->deletar($id_anuncie) != false) {

                Funcoes::resposta(true, "Conteúdo de Anuncie deletado com sucesso. Você esta sendo redirecionado, aguarde..", 200, [
                    "redirecionar" => URL . "/admin/anuncie"
                ]);
            }
        } else {

            $anuncie = $this->anuncie_model->listar_todos_anuncie();

            $dados = ["anuncies" => $anuncie];

            $this->view('admin/anuncie/index', (object) $dados);
        }
    }


    // Redes sociais
    public function sociais($requisicao = null, $id_social = null)
    {
        if ($requisicao == 'cadastrar') {

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {

                $this->view('admin/sociais/cadastrar');
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

                $dados = [
                    'nome' => trim(Funcoes::protegerStringAtaqueXSS($dados['nome'])),
                    'link' => trim(Funcoes::protegerStringAtaqueXSS($dados['link'])),
                    'icone' => trim($dados['icone']),
                    'altura' => trim($dados['altura']),
                    'largura' => trim($dados['largura']),
                    'usuario_id' => trim(Funcoes::protegerStringAtaqueXSS($dados['usuario_id'])),
                    'status' => trim(Funcoes::protegerStringAtaqueXSS($dados['status']))
                ];

                if (Funcoes::valida_campo($dados['nome'])) {

                    Funcoes::resposta(false, "Campo nome em branco", 400);

                } else if (Funcoes::valida_campo($dados['link'])) {

                    Funcoes::resposta(false, "Campo link em branco", 400);

                } else if (Funcoes::valida_campo($dados['status'])) {

                    Funcoes::resposta(false, "Campo status em branco", 400);

                }

                // Url amigável
                $dados['slug'] = Funcoes::slug($dados['nome']);

                if ($this->social_model->armazenar($dados)) {

                    Funcoes::resposta(true, "Conteúdo de Redes Sociais cadastrado com sucesso. Você esta sendo redirecionado, aguarde..", 200, [
                        "redirecionar" => URL . "/admin/sociais"
                    ]);
                } else {

                    Funcoes::resposta(false, "Não foi possível cadastrar o conteúdo de Redes Sociais, por favor tente novamente mais tarde", 400);
                }
            }
        } else if ($requisicao == 'editar') {

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {

                $sociais = $this->social_model->busca_sociais_id($id_social);

                $dados = ["sociais" => $sociais];

                $this->view('admin/sociais/editar', (object) $dados);
            }


            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

                $dados = [
                    'nome' => trim(Funcoes::protegerStringAtaqueXSS($dados['nome'])),
                    'link' => trim(Funcoes::protegerStringAtaqueXSS($dados['link'])),
                    'icone' => trim($dados['icone']),
                    'altura' => trim($dados['altura']),
                    'largura' => trim($dados['largura']),
                    'usuario_id' => trim(Funcoes::protegerStringAtaqueXSS($dados['usuario_id'])),
                    'status' => trim(Funcoes::protegerStringAtaqueXSS($dados['status']))
                ];

                if (Funcoes::valida_campo($dados['nome'])) {

                    Funcoes::resposta(false, "Campo nome em branco", 400);

                } else if (Funcoes::valida_campo($dados['link'])) {

                    Funcoes::resposta(false, "Campo link em branco", 400);

                } else if (Funcoes::valida_campo($dados['status'])) {

                    Funcoes::resposta(false, "Campo status em branco", 400);

                }

                $sociais = $this->social_model->busca_sociais_id($id_social);

                // Gerando o slug atualizado
                $dados['slug'] = Funcoes::slug($dados['nome']);

                // Verificando se houve alteração...
                $dados['nome'] ? Funcoes::protegerStringAtaqueXSS($dados['nome']) : $sociais->nome;
                $dados['link'] ? Funcoes::sanitizarHtmlComPurifier($dados['link']) : $sociais->link;
                $dados['icone'] ? $dados['icone'] : $sociais->icone;
                $dados['altura'] ? $dados['altura'] : $sociais->altura;
                $dados['largura'] ? $dados['largura'] : $sociais->largura;
                $dados['usuario_id'] ? Funcoes::protegerStringAtaqueXSS($dados['usuario_id']) : $sociais->usuario_id;
                $dados['status'] ? Funcoes::protegerStringAtaqueXSS($dados['status']) : $sociais->status;
                $dados['slug'] ? $dados['slug'] : $sociais->slug;

                // Pegando a data e hora atual
                $dados['data_edicao'] = date("Y-m-d h:i:s");

                if ($this->social_model->atualizar($dados, $id_social)) {

                    Funcoes::resposta(true, "Conteúdo de Redes Sociais atualizado com sucesso. Você esta sendo redirecionado, aguarde..", 200, [
                        "redirecionar" => URL . "/admin/sociais"
                    ]);
                } else {

                    Funcoes::resposta(false, "Não foi possível atualizar o conteúdo de Redes Sociais, por favor tente novamente mais tarde", 400);
                }
            }
        } else if ($requisicao == 'deletar') {

            $id_social = (int) $id_social;

            if ($this->social_model->deletar($id_social) != false) {

                Funcoes::resposta(true, "Conteúdo de Redes Sociais deletado com sucesso. Você esta sendo redirecionado, aguarde..", 200, [
                    "redirecionar" => URL . "/admin/sociais"
                ]);
            }
        } else {

            $sociais = $this->social_model->listar_todos_sociais();

            $dados = ["sociais" => $sociais];

            $this->view('admin/sociais/index', (object) $dados);
        }
    }


    // Notificações
    public function notificacoes($requisicao = null, $id_notificacao = null)
    {
        if ($requisicao == 'cadastrar') {

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {

                $this->view('admin/notificacoes/cadastrar');
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

                $dados = [
                    'titulo' => trim($dados['titulo']),
                    'texto' => trim($dados['texto']),
                    'usuario_id' => trim(Funcoes::protegerStringAtaqueXSS($dados['usuario_id'])),
                    'status' => trim(Funcoes::protegerStringAtaqueXSS($dados['status']))
                ];

                if (Funcoes::valida_campo($dados['titulo'])) {

                    Funcoes::resposta(false, "Campo título em branco", 400);

                } else if (Funcoes::valida_campo($dados['texto'])) {

                    Funcoes::resposta(false, "Campo texto em branco", 400);

                } else if (Funcoes::valida_campo($dados['status'])) {

                    Funcoes::resposta(false, "Campo status em branco", 400);

                }

                // Url amigável
                $dados['slug'] = Funcoes::slug($dados['titulo']);

                // Passa o conteúdo pela sanatização
                $dados['texto'] = Funcoes::sanitizarHtmlComPurifier($dados['texto']);
                $dados['titulo'] = Funcoes::protegerStringAtaqueXSS($dados['titulo']);

                if ($this->notificacao_model->armazenar($dados)) {

                    Funcoes::resposta(true, "Mensagem de Notificação cadastrada com sucesso. Você esta sendo redirecionado, aguarde..", 200, [
                        "redirecionar" => URL . "/admin/notificacoes"
                    ]);
                } else {

                    Funcoes::resposta(false, "Não foi possível cadastrar a Mensagem de Notificação, por favor tente novamente mais tarde", 400);
                }
            }
        } else if ($requisicao == 'editar') {

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {

                $notificacao = $this->notificacao_model->busca_notificacao_id($id_notificacao);

                $dados = ["notificacao" => $notificacao];

                $this->view('admin/notificacoes/editar', (object) $dados);
            }


            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

                $dados = [
                    'titulo' => trim($dados['titulo']),
                    'texto' => trim($dados['texto']),
                    'usuario_id' => trim(Funcoes::protegerStringAtaqueXSS($dados['usuario_id'])),
                    'status' => trim(Funcoes::protegerStringAtaqueXSS($dados['status']))
                ];

                if (Funcoes::valida_campo($dados['titulo'])) {

                    Funcoes::resposta(false, "Campo título em branco", 400);

                } else if (Funcoes::valida_campo($dados['texto'])) {

                    Funcoes::resposta(false, "Campo texto em branco", 400);

                } else if (Funcoes::valida_campo($dados['status'])) {

                    Funcoes::resposta(false, "Campo status em branco", 400);

                }

                $notificacao = $this->notificacao_model->busca_notificacao_id($id_notificacao);

                // Gerando o slug atualizado
                $dados['slug'] = Funcoes::slug($dados['titulo']);

                // Verificando se houve alteração no titulo ou no texto
                $dados['titulo'] ? Funcoes::protegerStringAtaqueXSS($dados['titulo']) : $notificacao->titulo;
                $dados['texto'] ? Funcoes::sanitizarHtmlComPurifier($dados['texto']) : $notificacao->texto;
                $dados['usuario_id'] ? Funcoes::protegerStringAtaqueXSS($dados['usuario_id']) : $notificacao->usuario_id;
                $dados['status'] ? Funcoes::protegerStringAtaqueXSS($dados['status']) : $notificacao->status;

                // Pegando a data e hora atual
                $dados['data_edicao'] = date("Y-m-d h:i:s");

                if ($this->notificacao_model->atualizar($dados, $id_notificacao)) {

                    Funcoes::resposta(true, "Mensagem de Notificação atualizada com sucesso. Você esta sendo redirecionado, aguarde..", 200, [
                        "redirecionar" => URL . "/admin/notificacoes"
                    ]);
                } else {

                    Funcoes::resposta(false, "Não foi possível atualizar a Mensagem de Notificação, por favor tente novamente mais tarde", 400);
                }
            }
        } else if ($requisicao == 'deletar') {

            $id_notificacao = (int) $id_notificacao;

            if ($this->notificacao_model->deletar($id_notificacao) != false) {

                Funcoes::resposta(true, "Mensagem de Notificação deletada com sucesso. Você esta sendo redirecionado, aguarde..", 200, [
                    "redirecionar" => URL . "/admin/notificacoes"
                ]);
            }
        } else {

            $notificacoes = $this->notificacao_model->listar_todos_notificacao();

            $dados = ["notificacoes" => $notificacoes];

            $this->view('admin/notificacoes/index', (object) $dados);
        }
    }
}
