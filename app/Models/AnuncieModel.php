<?php

class AnuncieModel {

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function armazenar($dados)
    {
        $this->db->query("INSERT INTO anuncie (titulo, texto, endereco, email, telefone, nome, cargo, usuario_id, status, slug) VALUES (:titulo, :texto, :endereco, :email, :telefone, :nome, :cargo, :usuario_id, :status, :slug)");

        $this->db->bind("titulo", $dados['titulo']);
        $this->db->bind("texto", $dados['texto']);
        $this->db->bind("endereco", $dados['endereco']);
        $this->db->bind("email", $dados['email']);
        $this->db->bind("telefone", $dados['telefone']);
        $this->db->bind("nome", $dados['nome']);
        $this->db->bind("cargo", $dados['cargo']);
        $this->db->bind("usuario_id", $dados['usuario_id']);
        $this->db->bind("status", $dados['status']);
        $this->db->bind("slug", $dados['slug']);

        return $this->db->executa() ? true : false;
    }

    public function atualizar($dados, int $id_anuncie)
    {
        $this->db->query("UPDATE anuncie SET 
            titulo              = :titulo,
            texto              = :texto,
            endereco              = :endereco,
            email              = :email,
            telefone              = :telefone,
            nome              = :nome,
            cargo              = :cargo,
            data_edicao    = :data_edicao,
            usuario_id      = :usuario_id,
            status      = :status,
            slug               = :slug
            WHERE id_anuncie  = :id_anuncie");

        $this->db->bind("titulo", $dados['titulo']);
        $this->db->bind("texto", $dados['texto']);
        $this->db->bind("endereco", $dados['endereco']);
        $this->db->bind("email", $dados['email']);
        $this->db->bind("telefone", $dados['telefone']);
        $this->db->bind("nome", $dados['nome']);
        $this->db->bind("cargo", $dados['cargo']);
        $this->db->bind("data_edicao", $dados['data_edicao']);
        $this->db->bind("usuario_id", $dados['usuario_id']);
        $this->db->bind("status", $dados['status']);
        $this->db->bind("slug", $dados['slug']);
        $this->db->bind("id_anuncie", $id_anuncie);

        return $this->db->executa() ? true : false;
    }

    public function deletar($id_anuncie)
    {
        $this->db->query("DELETE FROM anuncie WHERE id_anuncie = :id_anuncie");

        $this->db->bind("id_anuncie", $id_anuncie);

        if($this->db->executa()) {

            return true;

        } else {

            return false;
        }
    }

    public function listar_todos_anuncie()
    {
        $this->db->query("SELECT * FROM anuncie");

        return $this->db->resultados();
    }

    public function listar_anuncie_home()
    {
        $this->db->query("SELECT * FROM anuncie WHERE status = 1 LIMIT 1");

        return $this->db->resultados();
    }

    public function busca_anuncie_id($id_anuncie)
    {
        $this->db->query("SELECT * FROM anuncie WHERE id_anuncie = :id_anuncie");

        $this->db->bind("id_anuncie", $id_anuncie);

        return $this->db->resultado();
    }

    public function busca_anuncie_slug($slug)
    {
        $this->db->query("SELECT * FROM anuncie WHERE slug = :slug");

        $this->db->bind("slug", $slug);

        return $this->db->resultado();
    }

    public function listar_qtd_anuncie()
    {
        $this->db->query("SELECT COUNT(*) FROM anuncie");

        return $this->db->resultados();
    }

}