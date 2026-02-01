<?php

class PostCadastro {

    // Propriedades
    private string $titulo;
    private string $subtitulo;
    private string $texto;
    private int $status;
    private int $categoria_id;
    private int $usuario_id;
    private string $slug;
    private string $imagem_post;


    // Método construtor que é executado quando a classe é instanciada
    public function __construct(array $dados = [])
    {
        if(!empty($dados)) {
            $this->preencher($dados);
        }
    }


    // Método/função da classe para preencher os dados automaticamente
    public function preencher(array $dados) {
        $this->setTitulo($dados['titulo'] ?? '');
        $this->setSubtitulo($dados['subtitulo'] ?? '');
        $this->setTexto($dados['texto'] ?? '');
        $this->setStatus($dados['status'] ?? '');
        $this->setCategoria_id($dados['categoria_id'] ?? '');
        $this->setUsuario_id($dados['usuario_id'] ?? '');
        $this->setImagem_post($dados['imagem_post'] ?? '');
    }


    // Getters e Setters
    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): void
    {
        // Gerando a url amigável / slug
        $this->setSlug(Funcoes::slug($titulo) . '-' . date("Y-m-d"));

        // E agora, protegendo o titulo para salvar no banco de dados
        $this->titulo = Funcoes::protegerStringAtaqueXSS($titulo);
    }

    public function getSubtitulo(): string
    {
        return $this->subtitulo;
    }

    public function setSubtitulo(string $subtitulo): void
    {
        $this->subtitulo = $subtitulo;
    }

    public function getTexto(): string
    {
        return $this->texto;
    }

    public function setTexto(string $texto): void
    {
        // Tratando o texto com o HTMLPurifier
        $this->setTexto(Funcoes::sanitizarHtmlComPurifier($texto));
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getCategoria_id(): int
    {
        return $this->categoria_id;
    }

    public function setCategoria_id(int $categoria_id): void
    {
        $this->categoria_id = $categoria_id;
    }

    public function getUsuario_id(): int
    {
        return $this->usuario_id;
    }

    public function setUsuario_id(int $usuario_id): void
    {
        $this->usuario_id = $usuario_id;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getImagem_post(): string
    {
        return $this->imagem_post;
    }

    public function setImagem_post(string $imagem_post): void
    {
        $this->imagem_post = $imagem_post;
    }

    // Método retornando os dados como array para salvar no banco de dados
    public function dados_array(): array
    {
        return [
            'titulo' => $this->getTitulo(),
            'subtitulo' => $this->getSubtitulo(),
            'texto' => $this->getTexto(),
            'status' => $this->getStatus(),
            'categoria_id' => $this->getCategoria_id(),
            'usuario_id' => $this->getUsuario_id(),
            'slug' => $this->getSlug(),
            'imagem_post' => $this->getImagem_post()
        ];
    }

}