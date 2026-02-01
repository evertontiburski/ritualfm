<?php

class NotificacaoModel {

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function armazenar($dados)
    {
        $this->db->query("INSERT INTO notificacoes (titulo, texto, usuario_id, status, slug) VALUES (:titulo, :texto, :usuario_id, :status, :slug)");

        $this->db->bind("titulo", $dados['titulo']);
        $this->db->bind("texto", $dados['texto']);
        $this->db->bind("usuario_id", $dados['usuario_id']);
        $this->db->bind("status", $dados['status']);
        $this->db->bind("slug", $dados['slug']);

        return $this->db->executa() ? true : false;
    }

    public function atualizar($dados, int $id_notificacao)
    {
        $this->db->query("UPDATE notificacoes SET 
            titulo              = :titulo,
            texto              = :texto,
            data_edicao    = :data_edicao,
            usuario_id      = :usuario_id,
            status      = :status,
            slug               = :slug
            WHERE id_notificacao  = :id_notificacao");

        $this->db->bind("titulo", $dados['titulo']);
        $this->db->bind("texto", $dados['texto']);
        $this->db->bind("data_edicao", $dados['data_edicao']);
        $this->db->bind("usuario_id", $dados['usuario_id']);
        $this->db->bind("status", $dados['status']);
        $this->db->bind("slug", $dados['slug']);
        $this->db->bind("id_notificacao", $id_notificacao);

        return $this->db->executa() ? true : false;
    }

    public function deletar($id_notificacao)
    {
        $this->db->query("DELETE FROM notificacoes WHERE id_notificacao = :id_notificacao");

        $this->db->bind("id_notificacao", $id_notificacao);

        if($this->db->executa()) {

            return true;

        } else {

            return false;
        }
    }

    public function listar_todos_notificacao()
    {
        $this->db->query("SELECT * FROM notificacoes ORDER BY data_criacao DESC");

        return $this->db->resultados();
    }

    public function listar_notificacao_dashboard()
    {
        $this->db->query("SELECT * FROM notificacoes WHERE status = 1 ORDER BY data_criacao DESC LIMIT 5");

        return $this->db->resultados();
    }

    public function busca_notificacao_id($id_notificacao)
    {
        $this->db->query("SELECT * FROM notificacoes WHERE id_notificacao = :id_notificacao");

        $this->db->bind("id_notificacao", $id_notificacao);

        return $this->db->resultado();
    }

    public function busca_notificacao_slug($slug)
    {
        $this->db->query("SELECT * FROM notificacoes WHERE slug = :slug");

        $this->db->bind("slug", $slug);

        return $this->db->resultado();
    }

    public function listar_qtd_notificacao()
    {
        $this->db->query("SELECT COUNT(*) as total FROM notificacoes");

        return $this->db->resultados();
    }

}