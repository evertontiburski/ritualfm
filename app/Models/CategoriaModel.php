<?php

class CategoriaModel {

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function armazenar($dados)
    {
        $this->db->query("INSERT INTO categorias(titulo, texto, status, slug) VALUES (:titulo, :texto, :status, :slug)");

        $this->db->bind("titulo", $dados['titulo']);
        $this->db->bind("texto", $dados['texto']);
        $this->db->bind("status", $dados['status']);
        $this->db->bind("slug", $dados['slug']);

        return $this->db->executa() ? true : false;
    }

    public function atualizar($dados, int $id_categoria)
    {
        $this->db->query("UPDATE categorias SET 
            titulo              = :titulo,
            texto               = :texto,
            status              = :status,
            slug                = :slug
            WHERE id_categoria  = :id_categoria");

        $this->db->bind("titulo", $dados['titulo']);
        $this->db->bind("texto", $dados['texto']);
        $this->db->bind("status", $dados['status']);
        $this->db->bind("slug", $dados['slug']);
        $this->db->bind("id_categoria", $id_categoria);

        return $this->db->executa() ? true : false;
    }

    public function deletar($id_categoria)
    {
        $this->db->query("DELETE FROM categorias WHERE id_categoria = :id_categoria");

        $this->db->bind("id_categoria", $id_categoria);

        if($this->db->executa()) {

            return true;

        } else {

            return false;
        }
    }

    public function listar_todas_categorias()
    {
        $this->db->query("SELECT * FROM categorias");

        return $this->db->resultados();
    }

    public function listar_posts_categoria()
    {
        $this->db->query("SELECT
            c.*,
            COUNT(p.id_post) as post_count
        FROM
            categorias c
        LEFT JOIN
            posts p ON c.id_categoria = p.id_categoria
        GROUP BY
            c.id_categoria");

        return $this->db->resultados();
    }


    public function busca_categoria_id($id_categoria)
    {
        $this->db->query("SELECT * FROM categorias WHERE id_categoria = :id_categoria");

        $this->db->bind("id_categoria", $id_categoria);

        return $this->db->resultado();
    }

    public function busca_categoria_slug($slug)
    {
        $this->db->query("SELECT * FROM categorias WHERE slug = :slug");

        $this->db->bind("slug", $slug);

        return $this->db->resultado();
    }

    public function listar_qtd_categorias()
    {
        $this->db->query("SELECT COUNT(*) FROM categorias");

        return $this->db->resultados();
    }

}