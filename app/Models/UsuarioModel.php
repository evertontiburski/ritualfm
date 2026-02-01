<?php

class UsuarioModel {

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function armazenar($dados)
    {
        $this->db->query("INSERT INTO usuarios(nome, email, senha, status, level) VALUES (:nome, :email, :senha, :status, :level)");

        $this->db->bind("nome", $dados['nome']);
        $this->db->bind("email", $dados['email']);
        $this->db->bind("senha", $dados['senha']);
        $this->db->bind("status", $dados['status']);
        $this->db->bind("level", $dados['level']);

        return $this->db->executa() ? true : false;
    }

    public function atualizar($dados, int $id_usuario)
    {
        $this->db->query("UPDATE usuarios SET 
            nome                = :nome,
            email               = :email,
            senha               = :senha,
            status              = :status,
            level               = :level,
            atualizado_em       = :atualizado_em
            WHERE id_usuario    = :id_usuario");

        $this->db->bind("nome", $dados['nome']);
        $this->db->bind("email", $dados['email']);
        $this->db->bind("senha", $dados['senha']);
        $this->db->bind("status", $dados['status']);
        $this->db->bind("level", $dados['level']);
        $this->db->bind("atualizado_em", $dados['atualizado_em']);
        $this->db->bind("id_usuario", $id_usuario);

        return $this->db->executa() ? true : false;
    }

    public function consulta_email($email)
    {
        $this->db->query("SELECT email FROM usuarios WHERE email = :email");

        $this->db->bind("email", $email);

        return $this->db->resultado() ? true : false;
    }

    public function deletar($id_usuario)
    {
        $this->db->query("DELETE FROM usuarios WHERE id_usuario = :id_usuario");

        $this->db->bind("id_usuario", $id_usuario);

        if ($this->db->executa()) {

            return true;

        } else {

            return false;
        }
    }

    public function login($email, $senha)
    {
        $this->db->query("SELECT * FROM usuarios WHERE email = :email");

        $this->db->bind("email", $email);

        if($this->db->resultado()) {

            $resultado = $this->db->resultado();

            if(Funcoes::validarSenha($senha, $resultado->senha)) {

                return $resultado;
            }
            
        }
        
        return false;
    }

    public function consulta_status_usuario($status)
    {
        $this->db->query("SELECT status FROM usuarios WHERE status = :status");

        $this->db->bind("status", $status);

        return $this->db->resultado() ? true : false;
    }

    public function listar_todos_usuarios()
    {
        $this->db->query("SELECT * FROM usuarios");

        return $this->db->resultados();
    }

    public function busca_usuario_id($id_usuario)
    {
        $this->db->query("SELECT * FROM usuarios WHERE id_usuario = :id_usuario");

        $this->db->bind("id_usuario", $id_usuario);

        return $this->db->resultado();
    }

    public function busca_usuario_email($email)
    {
        $this->db->query("SELECT * FROM usuarios WHERE email = :email");

        $this->db->bind("email", $email);

        return $this->db->resultado();
    }

    public function senha_temporaria($senha_temporaria, $id_usuario)
    {
        $this->db->query("UPDATE usuarios SET  senha_temporaria = :senha_temporaria WHERE id_usuario = :id_usuario LIMIT 1");

        $this->db->bind("senha_temporaria", $senha_temporaria);
        $this->db->bind("id_usuario", $id_usuario);

        return $this->db->executa() ? true : false;
    }

    // Exclusivo para uso do usuário no site (esqueci-a-minha-senha)
    public function tokenEsqueciMinhaSenha(array $dados)
    {
        try {
            $this->db->query("INSERT INTO tokens_esqueci_minha_senha (token, email, data_expiracao) VALUES (:token, :email, :data_expiracao)");

            $this->db->bind("token", $dados['token']);
            $this->db->bind("email", $dados['email']);
            $this->db->bind("data_expiracao", $dados['data_expiracao']);

            return $this->db->executa();
            
        } catch (PDOException $error) {

            // Retorna false se ocorrer um erro
            $error->getMessage();

            return false;
        }
    }

    public function listar_qtd_usuarios()
    {
        $this->db->query("SELECT COUNT(*) FROM usuarios");

        return $this->db->resultados();
    }

}