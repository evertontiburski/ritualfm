<?php

class ContatoModel {

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function armazenar($dados)
    {
        $this->db->query("INSERT INTO contatos (nome, email, mensagem) VALUES (:nome, :email, :mensagem)");

        $this->db->bind("nome",     $dados['nome']);
        $this->db->bind("email",    $dados['email']);
        $this->db->bind("mensagem", $dados['mensagem']);

        return $this->db->executa() ? true : false;
    }

    public function atualizar(int $id_contato)
    {
        $this->db->query("UPDATE contatos SET status = 1 WHERE id_contato  = :id_contato");

        $this->db->bind("id_contato",   $id_contato);

        return $this->db->executa() ? true : false;
    }

    public function deletar($id_contato)
    {
        $this->db->query("DELETE FROM contatos WHERE id_contato = :id_contato");

        $this->db->bind("id_contato", $id_contato);

        if ($this->db->executa()) {

            return true;

        } else {

            return false;
        }
    }

    public function listar_todos_contatos()
    {
        $this->db->query("SELECT * FROM contatos ORDER BY data_criacao DESC");

        return $this->db->resultados();
    }


    public function busca_contato_id($id_contato)
    {
        $this->db->query("SELECT * FROM contatos WHERE id_contato = :id_contato");

        $this->db->bind("id_contato", $id_contato);

        return $this->db->resultado();
    }

    public function listar_qtd_contatos()
    {
        $this->db->query("SELECT COUNT(*) FROM contatos");

        return $this->db->resultados();
    }

}