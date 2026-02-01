<?php

class SocialModel {

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function armazenar($dados)
    {
        $this->db->query("INSERT INTO sociais (nome, link, icone, altura, largura, usuario_id, status, slug) VALUES (:nome, :link, :icone, :altura, :largura, :usuario_id, :status, :slug)");

        $this->db->bind("nome", $dados['nome']);
        $this->db->bind("link", $dados['link']);
        $this->db->bind("icone", $dados['icone']);
        $this->db->bind("altura", $dados['altura']);
        $this->db->bind("largura", $dados['largura']);
        $this->db->bind("usuario_id", $dados['usuario_id']);
        $this->db->bind("status", $dados['status']);
        $this->db->bind("slug", $dados['slug']);

        return $this->db->executa() ? true : false;
    }

    public function atualizar($dados, int $id_social)
    {
        $this->db->query("UPDATE sociais SET 
            nome              = :nome,
            link               = :link,
            icone               = :icone,
            altura               = :altura,
            largura               = :largura,
            usuario_id      = :usuario_id,
            data_edicao      = :data_edicao,
            status      = :status,
            slug      = :slug
            WHERE id_social  = :id_social");

        $this->db->bind("nome", $dados['nome']);
        $this->db->bind("link", $dados['link']);
        $this->db->bind("icone", $dados['icone']);
        $this->db->bind("altura", $dados['altura']);
        $this->db->bind("largura", $dados['largura']);
        $this->db->bind("usuario_id", $dados['usuario_id']);
        $this->db->bind("data_edicao", $dados['data_edicao']);
        $this->db->bind("status", $dados['status']);
        $this->db->bind("slug", $dados['slug']);
        $this->db->bind("id_social", $id_social);

        return $this->db->executa() ? true : false;
    }

    public function deletar($id_social)
    {
        $this->db->query("DELETE FROM sociais WHERE id_social = :id_social");

        $this->db->bind("id_social", $id_social);

        if($this->db->executa()) {

            return true;

        } else {

            return false;
        }
    }

    public function listar_todos_sociais()
    {
        $this->db->query("SELECT * FROM sociais");

        return $this->db->resultados();
    }

    public function listar_sociais_home()
    {
        $this->db->query("SELECT * FROM sociais WHERE status = 1 LIMIT 1");

        return $this->db->resultados();
    }

    public function busca_sociais_id($id_social)
    {
        $this->db->query("SELECT * FROM sociais WHERE id_social = :id_social");

        $this->db->bind("id_social", $id_social);

        return $this->db->resultado();
    }

    public function busca_sociais_slug($slug)
    {
        $this->db->query("SELECT * FROM sociais WHERE slug = :slug");

        $this->db->bind("slug", $slug);

        return $this->db->resultado();
    }

    public function listar_qtd_sociais()
    {
        $this->db->query("SELECT COUNT(*) FROM sociais");

        return $this->db->resultados();
    }

}