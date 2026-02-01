<?php

class ComentarioModel
{

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function armazenar($dados)
    {
        $this->db->query("INSERT INTO comentarios(comentario, status, usuario_id, post_id) VALUES (:comentario, :status, :usuario_id, :post_id)");

        $this->db->bind("comentario", $dados['comentario']);
        $this->db->bind("status", $dados['status']);
        $this->db->bind("usuario_id", $dados['usuario_id']);
        $this->db->bind("post_id", $dados['post_id']);

        return $this->db->executa() ? true : false;
    }

    public function aprovar(int $id_comentario)
    {
        $this->db->query("UPDATE comentarios SET status = 1 WHERE id_comentario = :id_comentario");

        $this->db->bind("id_comentario", $id_comentario);

        return $this->db->executa() ? true : false;
    }

    public function lista_todos_comentarios()
    {
        $this->db->query("SELECT 
        p.titulo,
        p.slug,
        c.id_comentario, 
        c.comentario, 
        c.data_comentario,
        c.status,
        c.post_id,
        c.qtd_denuncia, 
        c.qtd_gostei, 
        c.qtd_nao_gostei,
        u.nome FROM 
            comentarios c
        INNER JOIN 
            usuarios u ON c.usuario_id = u.id_usuario
        INNER JOIN
            posts p ON c.post_id = p.id_post
        ORDER BY c.data_comentario DESC");

        return $this->db->resultados();
    }

    public function deletar($id_comentario)
    {
        $this->db->query("DELETE FROM comentarios WHERE id_comentario = :id_comentario");

        $this->db->bind("id_comentario", $id_comentario);

        if ($this->db->executa()) {

            return true;
        } else {

            return false;
        }
    }

    public function lista_comentarios_por_post($id_post)
    {
        $this->db->query("SELECT comentarios.id_comentario, 
            comentarios.comentario, 
            comentarios.data_comentario,
            comentarios.status,
            comentarios.qtd_denuncia, 
            comentarios.qtd_gostei, 
            comentarios.qtd_nao_gostei,
            usuarios.nome AS usuario
            FROM comentarios
            INNER JOIN usuarios ON comentarios.usuario_id = usuarios.id_usuario
            WHERE comentarios.post_id = :id_post AND comentarios.status = 1 ORDER BY comentarios.id_comentario DESC");

        $this->db->bind("id_post", $id_post);

        return $this->db->resultados();
    }

    public function listar_qtd_comentarios()
    {
        $this->db->query("SELECT COUNT(*) FROM comentarios");

        return $this->db->resultados();
    }


    public function listar_comentarios_por_usuario($id_usuario)
    {
        $this->db->query("SELECT
        u.id_usuario,
        u.nome AS nome_do_usuario,
        p.titulo AS titulo_do_post,
        p.slug,
        c.id_comentario,
        c.comentario,
        c.data_comentario
        FROM
            comentarios AS c
        JOIN
            usuarios AS u ON c.usuario_id = u.id_usuario
        JOIN
            posts AS p ON c.post_id = p.id_post
        WHERE u.id_usuario = :id_usuario AND
            c.status = 1 AND u.status = 1 AND p.status = 1
        ORDER BY
        u.nome, c.data_comentario DESC");

        $this->db->bind("id_usuario", $id_usuario);

        return $this->db->resultados();
    }


    public function comentario_pertence_ao_usuario($id_comentario, $id_usuario)
    {
        $this->db->query("SELECT id_comentario FROM comentarios WHERE id_comentario = :id_comentario AND usuario_id = :id_usuario");

       $this->db->bind("id_comentario", $id_comentario);
       $this->db->bind("id_usuario", $id_usuario);

       return $this->db->resultado() ? true : false;

    }


    public function desativando_comentario_usuario(int $id_comentario)
    {
        $this->db->query("UPDATE comentarios SET status = 0 WHERE id_comentario = :id_comentario");

        $this->db->bind("id_comentario", $id_comentario);

        return $this->db->executa() ? true : false;
    }

    public function listar_qtd_comentario_usuario($id_usuario)
    {
        $this->db->query("SELECT COUNT(*) as total FROM comentarios WHERE usuario_id = :id_usuario AND status = 1");

        $this->db->bind("id_usuario", $id_usuario);

        return $this->db->resultados();
    }
    
}
