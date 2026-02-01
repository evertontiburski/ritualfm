<?php

class TermoModel {

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function armazenar($dados)
    {
        $this->db->query("INSERT INTO termos (titulo, texto, usuario_id, status, slug) VALUES (:titulo, :texto, :usuario_id, :status, :slug)");

        $this->db->bind("titulo", $dados['titulo']);
        $this->db->bind("texto", $dados['texto']);
        $this->db->bind("usuario_id", $dados['usuario_id']);
        $this->db->bind("status", $dados['status']);
        $this->db->bind("slug", $dados['slug']);

        return $this->db->executa() ? true : false;
    }

    public function atualizar($dados, int $id_termo)
    {
        $this->db->query("UPDATE termos SET 
            titulo              = :titulo,
            texto              = :texto,
            data_edicao    = :data_edicao,
            usuario_id      = :usuario_id,
            status      = :status,
            slug               = :slug
            WHERE id_termo  = :id_termo");

        $this->db->bind("titulo", $dados['titulo']);
        $this->db->bind("texto", $dados['texto']);
        $this->db->bind("data_edicao", $dados['data_edicao']);
        $this->db->bind("usuario_id", $dados['usuario_id']);
        $this->db->bind("status", $dados['status']);
        $this->db->bind("slug", $dados['slug']);
        $this->db->bind("id_termo", $id_termo);

        return $this->db->executa() ? true : false;
    }

    public function deletar($id_termo)
    {
        $this->db->query("DELETE FROM termos WHERE id_termo = :id_termo");

        $this->db->bind("id_termo", $id_termo);

        if($this->db->executa()) {

            return true;

        } else {

            return false;
        }
    }

    public function listar_todos_termos()
    {
        $this->db->query("SELECT * FROM termos");

        return $this->db->resultados();
    }

    public function listar_termos_home()
    {
        $this->db->query("SELECT * FROM termos WHERE status = 1 LIMIT 1");

        return $this->db->resultados();
    }

    public function busca_termo_id($id_termo)
    {
        $this->db->query("SELECT * FROM termos WHERE id_termo = :id_termo");

        $this->db->bind("id_termo", $id_termo);

        return $this->db->resultado();
    }

    public function busca_termo_slug($slug)
    {
        $this->db->query("SELECT * FROM termos WHERE slug = :slug");

        $this->db->bind("slug", $slug);

        return $this->db->resultado();
    }

    public function listar_qtd_termos()
    {
        $this->db->query("SELECT COUNT(*) FROM termos");

        return $this->db->resultados();
    }

}