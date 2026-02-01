<?php

class SobreModel {

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function armazenar($dados)
    {
        $this->db->query("INSERT INTO sobre (titulo, texto, usuario_id, status, slug) VALUES (:titulo, :texto, :usuario_id, :status, :slug)");

        $this->db->bind("titulo", $dados['titulo']);
        $this->db->bind("texto", $dados['texto']);
        $this->db->bind("usuario_id", $dados['usuario_id']);
        $this->db->bind("status", $dados['status']);
        $this->db->bind("slug", $dados['slug']);

        return $this->db->executa() ? true : false;
    }

    public function atualizar($dados, int $id_sobre)
    {
        $this->db->query("UPDATE sobre SET 
            titulo              = :titulo,
            texto              = :texto,
            data_edicao    = :data_edicao,
            usuario_id      = :usuario_id,
            status      = :status,
            slug               = :slug
            WHERE id_sobre  = :id_sobre");

        $this->db->bind("titulo", $dados['titulo']);
        $this->db->bind("texto", $dados['texto']);
        $this->db->bind("data_edicao", $dados['data_edicao']);
        $this->db->bind("usuario_id", $dados['usuario_id']);
        $this->db->bind("status", $dados['status']);
        $this->db->bind("slug", $dados['slug']);
        $this->db->bind("id_sobre", $id_sobre);

        return $this->db->executa() ? true : false;
    }

    public function deletar($id_sobre)
    {
        $this->db->query("DELETE FROM sobre WHERE id_sobre = :id_sobre");

        $this->db->bind("id_sobre", $id_sobre);

        if($this->db->executa()) {

            return true;

        } else {

            return false;
        }
    }

    public function listar_todos_sobre()
    {
        $this->db->query("SELECT * FROM sobre");

        return $this->db->resultados();
    }

    public function listar_sobre_home()
    {
        $this->db->query("SELECT * FROM sobre WHERE status = 1 LIMIT 1");

        return $this->db->resultados();
    }

    public function busca_sobre_id($id_sobre)
    {
        $this->db->query("SELECT * FROM sobre WHERE id_sobre = :id_sobre");

        $this->db->bind("id_sobre", $id_sobre);

        return $this->db->resultado();
    }

    public function busca_sobre_slug($slug)
    {
        $this->db->query("SELECT * FROM sobre WHERE slug = :slug");

        $this->db->bind("slug", $slug);

        return $this->db->resultado();
    }

    public function listar_qtd_sobre()
    {
        $this->db->query("SELECT COUNT(*) FROM sobre");

        return $this->db->resultados();
    }

}