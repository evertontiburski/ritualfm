<?php

class PublicidadeModel {

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function armazenar($dados)
    {
        $this->db->query("INSERT INTO publicidades
        (titulo, subtitulo, texto, status, categoria_id, usuario_id, slug, imagem_publicidade) VALUES 
        (:titulo, :subtitulo, :texto, :status, :categoria_id, :usuario_id, :slug, :imagem_publicidade)");

        $this->db->bind("titulo",               $dados['titulo']);
        $this->db->bind("subtitulo",            $dados['subtitulo']);
        $this->db->bind("texto",                $dados['texto']);
        $this->db->bind("status",               $dados['status']);
        $this->db->bind("categoria_id",         $dados['categoria_id']);
        $this->db->bind("usuario_id",           $dados['usuario_id']);
        $this->db->bind("slug",                 $dados['slug']);
        $this->db->bind("imagem_publicidade",   $dados['imagem_publicidade']);

        return $this->db->executa() ? true : false;
    }

    public function atualizar($dados, int $id_publicidade)
    {
        $this->db->query("UPDATE publicidades SET 
            titulo                  = :titulo,
            subtitulo               = :subtitulo,
            texto                   = :texto,
            status                  = :status,
            data_edicao             = :data_edicao,
            categoria_id            = :categoria_id,
            usuario_id              = :usuario_id,
            slug                    = :slug,
            imagem_publicidade      = :imagem_publicidade
            WHERE id_publicidade    = :id_publicidade");

        $this->db->bind("titulo",               $dados['titulo']);
        $this->db->bind("subtitulo",            $dados['subtitulo']);
        $this->db->bind("texto",                $dados['texto']);
        $this->db->bind("status",               $dados['status']);
        $this->db->bind("data_edicao",          $dados['data_edicao']);
        $this->db->bind("categoria_id",         $dados['categoria_id']);
        $this->db->bind("usuario_id",           $dados['usuario_id']);
        $this->db->bind("slug",                 $dados['slug']);
        $this->db->bind("imagem_publicidade",   $dados['imagem_publicidade']);
        $this->db->bind("id_publicidade",       $id_publicidade);

        return $this->db->executa() ? true : false;
    }

    public function deletar($id_publicidade)
    {
        $this->db->query("DELETE FROM publicidades WHERE id_publicidade = :id_publicidade");

        $this->db->bind("id_publicidade", $id_publicidade);

        if ($this->db->executa()) {

            return true;

        } else {

            return false;
        }
    }

    public function listar_todas_publicidades()
    {
        $this->db->query("SELECT * FROM publicidades ORDER BY data_criacao DESC");

        return $this->db->resultados();
    }

    public function busca_publicidade_id($id_publicidade)
    {
        $this->db->query("SELECT * FROM publicidades WHERE id_publicidade = :id_publicidade");

        $this->db->bind("id_publicidade", $id_publicidade);

        return $this->db->resultado();
    }

    public function busca_publicidade_slug($slug)
    {
        $this->db->query("SELECT * FROM publicidades WHERE slug = :slug");

        $this->db->bind("slug", $slug);

        return $this->db->resultado();
    }

    public function busca_publicidade_titulo($titulo)
    {
        $this->db->query("SELECT * FROM publicidades WHERE titulo = :titulo");

        $this->db->bind("titulo", $titulo);

        return $this->db->resultado() ? true : false;
    }

    public function listar_qtd_publicidades()
    {
        $this->db->query("SELECT COUNT(*) FROM publicidades");

        return $this->db->resultados();
    }
}