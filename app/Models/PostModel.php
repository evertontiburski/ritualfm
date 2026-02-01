<?php

class PostModel
{

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function armazenar($dados)
    {
        $this->db->query("INSERT INTO posts
        (titulo, subtitulo, texto, status, categoria_id, usuario_id, slug, imagem_post) VALUES 
        (:titulo, :subtitulo, :texto, :status, :categoria_id, :usuario_id, :slug, :imagem_post)");

        $this->db->bind("titulo",       $dados['titulo']);
        $this->db->bind("subtitulo",    $dados['subtitulo']);
        $this->db->bind("texto",        $dados['texto']);
        $this->db->bind("status",       $dados['status']);
        $this->db->bind("categoria_id", $dados['categoria_id']);
        $this->db->bind("usuario_id",   $dados['usuario_id']);
        $this->db->bind("slug",         $dados['slug']);
        $this->db->bind("imagem_post",  $dados['imagem_post'] ?? null);

        return $this->db->executa() ? true : false;
    }

    public function atualizar($dados, int $id_post)
    {
        $this->db->query("UPDATE posts SET 
            titulo              = :titulo,
            subtitulo           = :subtitulo,
            texto               = :texto,
            status              = :status,
            data_edicao         = :data_edicao,
            categoria_id        = :categoria_id,
            usuario_id          = :usuario_id,
            slug                = :slug,
            imagem_post         = :imagem_post
            WHERE id_post  = :id_post");

        $this->db->bind("titulo",       $dados['titulo']);
        $this->db->bind("subtitulo",    $dados['subtitulo']);
        $this->db->bind("texto",        $dados['texto']);
        $this->db->bind("status",       $dados['status']);
        $this->db->bind("data_edicao",  $dados['data_edicao']);
        $this->db->bind("categoria_id", $dados['categoria_id']);
        $this->db->bind("usuario_id",   $dados['usuario_id']);
        $this->db->bind("slug",         $dados['slug']);
        $this->db->bind("imagem_post",  $dados['imagem_post']);
        $this->db->bind("id_post",      $id_post);

        return $this->db->executa() ? true : false;
    }

    public function deletar($id_post)
    {
        $this->db->query("DELETE FROM posts WHERE id_post = :id_post");

        $this->db->bind("id_post", $id_post);

        if ($this->db->executa()) {

            return true;
        } else {

            return false;
        }
    }

    public function listar_todos_posts()
    {
        $this->db->query("SELECT * FROM posts ORDER BY data_criacao DESC");

        return $this->db->resultados();
    }

    // Em implementação...
    public function listar_posts_paginados($limit = 5, $offset = 0)
    {
        // Não esquecer de criar um índice para a coluna data_criacao para otimizar a ordenação
        $this->db->query("SELECT id_post, titulo, subtitulo, slug, imagem_post, data_criacao FROM posts ORDER BY data_criacao DESC LIMIT :limit OFFSET :offset");

        $this->db->bind(':limit', $limit);
        $this->db->bind(':offset', $offset);

        return $this->db->resultados();
    }

    public function listar_post_home()
    {
        $this->db->query("SELECT * FROM posts WHERE status = 1 ORDER BY data_criacao DESC LIMIT 5");

        return $this->db->resultados();
    }

    public function listar_post_lista()
    {
        $this->db->query("SELECT * FROM posts WHERE status = 1 ORDER BY data_criacao DESC LIMIT 4");

        return $this->db->resultados();
    }

    public function busca_post_id($id_post)
    {
        $this->db->query("SELECT * FROM posts WHERE id_post = :id_post");

        $this->db->bind("id_post", $id_post);

        return $this->db->resultado();
    }

    public function busca_post_slug($slug)
    {
        $this->db->query("SELECT * FROM posts WHERE slug = :slug");

        $this->db->bind("slug", $slug);

        return $this->db->resultado();
    }

    public function busca_post_titulo($titulo)
    {
        $this->db->query("SELECT * FROM posts WHERE titulo = :titulo");

        $this->db->bind("titulo", $titulo);

        return $this->db->resultado() ? true : false;
    }

    public function listar_qtd_posts()
    {
        $this->db->query("SELECT COUNT(*) FROM posts");

        return $this->db->resultados();
    }
}
