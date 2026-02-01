<?php

class EventoModel {

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function armazenar($dados)
    {
        $this->db->query("INSERT INTO eventos
        (titulo, texto, atracao, data, local, imagem_evento, video_id_youtube, usuario_id, status, slug) VALUES 
        (:titulo, :texto, :atracao, :data, :local, :imagem_evento, :video_id_youtube, :usuario_id, :status, :slug)");

        $this->db->bind("titulo",           $dados['titulo']);
        $this->db->bind("texto",        $dados['texto']);
        $this->db->bind("atracao",          $dados['atracao']);
        $this->db->bind("data",             $dados['data']);
        $this->db->bind("local",            $dados['local']);
        $this->db->bind("imagem_evento",    $dados['imagem_evento'] ?? null);
        $this->db->bind("video_id_youtube", $dados['video_id_youtube']);
        $this->db->bind("usuario_id",       $dados['usuario_id']);
        $this->db->bind("status",           $dados['status']);
        $this->db->bind("slug",             $dados['slug']);

        return $this->db->executa() ? true : false;
    }

    public function atualizar($dados, int $id_evento)
    {
        $this->db->query("UPDATE eventos SET 
            titulo              = :titulo,
            texto           = :texto,
            atracao             = :atracao,
            data                = :data,
            local               = :local,
            imagem_evento       = :imagem_evento,
            video_id_youtube    = :video_id_youtube,
            data_edicao         = :data_edicao,
            usuario_id          = :usuario_id,
            status              = :status,
            slug                = :slug
            WHERE id_evento  = :id_evento");

        $this->db->bind("titulo",           $dados['titulo']);
        $this->db->bind("texto",        $dados['texto']);
        $this->db->bind("atracao",          $dados['atracao']);
        $this->db->bind("data",             $dados['data']);
        $this->db->bind("local",            $dados['local']);
        $this->db->bind("imagem_evento",    $dados['imagem_evento']);
        $this->db->bind("video_id_youtube", $dados['video_id_youtube']);
        $this->db->bind("data_edicao",      $dados['data_edicao']);
        $this->db->bind("usuario_id",       $dados['usuario_id']);
        $this->db->bind("status",           $dados['status']);
        $this->db->bind("slug",             $dados['slug']);
        $this->db->bind("id_evento",        $id_evento);

        return $this->db->executa() ? true : false;
    }

    public function deletar($id_evento)
    {
        $this->db->query("DELETE FROM eventos WHERE id_evento = :id_evento");

        $this->db->bind("id_evento", $id_evento);

        if ($this->db->executa()) {

            return true;

        } else {

            return false;
        }
    }

    public function listar_todos_eventos()
    {
        $this->db->query("SELECT * FROM eventos ORDER BY data_criacao DESC");

        return $this->db->resultados();
    }

    public function listar_evento_home()
    {
        $this->db->query("SELECT * FROM eventos WHERE status = 1 ORDER BY data_criacao DESC");

        return $this->db->resultados();
    }

    public function busca_evento_id($id_evento)
    {
        $this->db->query("SELECT * FROM eventos WHERE id_evento = :id_evento");

        $this->db->bind("id_evento", $id_evento);

        return $this->db->resultado();
    }

    public function busca_evento_slug($slug)
    {
        $this->db->query("SELECT * FROM eventos WHERE slug = :slug");

        $this->db->bind("slug", $slug);

        return $this->db->resultado();
    }

    public function busca_evento_titulo($titulo)
    {
        $this->db->query("SELECT * FROM eventos WHERE titulo = :titulo");

        $this->db->bind("titulo", $titulo);

        return $this->db->resultado() ? true : false;
    }

    public function listar_qtd_eventos()
    {
        $this->db->query("SELECT COUNT(*) FROM eventos");

        return $this->db->resultados();
    }
}