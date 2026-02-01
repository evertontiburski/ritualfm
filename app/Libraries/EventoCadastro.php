<?php

class EventoCadastro {

    // Propriedades
    private string $titulo;
    private string $texto;
    private string $atracao;
    private string $data;
    private string $local;
    private string $video_id_youtube;
    private int $status;
    private int $usuario_id;
    private string $slug;
    private string $imagem_evento;


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
        $this->setTexto($dados['texto'] ?? '');
        $this->setAtracao($dados['atracao'] ?? '');
        $this->setData($dados['data'] ?? '');
        $this->setLocal($dados['local'] ?? '');
        $this->setImagem_evento($dados['imagem_evento'] ?? '');
        $this->setVideo_id_youtube($dados['video_id_youtube'] ?? '');
        $this->setUsuario_id($dados['usuario_id'] ?? '');
        $this->setStatus($dados['status'] ?? '');
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

    public function getTexto(): string
    {
        return $this->texto;
    }

    public function setTexto(string $texto): void
    {
        // Tratando o texto com o HTMLPurifier
        $this->setTexto(Funcoes::sanitizarHtmlComPurifier($texto));
    }

    public function getAtracao(): string
    {
        return $this->atracao;
    }

    public function setAtracao(string $atracao): void
    {
        $this->atracao = $atracao;
    }

    public function getData(): string
    {
        return $this->data;
    }

    public function setData(string $data): void
    {
        $this->data = $data;
    }

    public function getLocal(): string
    {
        return $this->local;
    }

    public function setLocal(string $local): void
    {
        $this->local = $local;
    }

    public function getVideo_id_youtube(): string
    {
        return $this->video_id_youtube;
    }

    public function setVideo_id_youtube(string $video_id_youtube): void
    {
        $this->video_id_youtube = $video_id_youtube;
    }

    public function getUsuario_id(): int
    {
        return $this->usuario_id;
    }

    public function setUsuario_id(int $usuario_id): void
    {
        $this->usuario_id = $usuario_id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getImagem_evento(): string
    {
        return $this->imagem_evento;
    }

    public function setImagem_evento(string $imagem_evento): void
    {
        $this->imagem_evento = $imagem_evento;
    }


    // Método retornando os dados como array para salvar no banco de dados
    public function dados_array(): array
    {
        return [
            'titulo' => $this->getTitulo(),
            'texto' => $this->getTexto(),
            'atracao' => $this->getAtracao(),
            'data' => $this->getData(),
            'local' => $this->getLocal(),
            'imagem_evento' => $this->getImagem_evento(),
            'video_id_youtube' => $this->getVideo_id_youtube(),
            'usuario_id' => $this->getUsuario_id(),
            'status' => $this->getStatus(),
            'slug' => $this->getSlug()
        ];
    }

}