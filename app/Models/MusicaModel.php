<?php

class MusicaModel {

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function armazenar($dados)
    {
        $this->db->query("INSERT INTO musicas
        (titulo, subtitulo, texto, artista, album, lancamento, player_id, categoria_id, usuario_id, imagem_musica, status, slug) VALUES 
        (:titulo, :subtitulo, :texto, :artista, :album, :lancamento, :player_id, :categoria_id, :usuario_id, :imagem_musica, :status, :slug)");

        $this->db->bind("titulo",           $dados['titulo']);
        $this->db->bind("subtitulo",           $dados['subtitulo']);
        $this->db->bind("texto",            $dados['texto']);
        $this->db->bind("artista",          $dados['artista']);
        $this->db->bind("album",            $dados['album']);
        $this->db->bind("lancamento",       $dados['lancamento']);
        $this->db->bind("player_id",       $dados['player_id']);
        $this->db->bind("categoria_id",       $dados['categoria_id']);
        $this->db->bind("usuario_id",       $dados['usuario_id']);
        $this->db->bind("status",           $dados['status']);
        $this->db->bind("slug",             $dados['slug']);
        $this->db->bind("imagem_musica",    $dados['imagem_musica'] ?? null);

        return $this->db->executa() ? true : false;
    }

    public function atualizar($dados, int $id_musica)
    {
        $this->db->query("UPDATE musicas SET 
            titulo          = :titulo,
            subtitulo     = :subtitulo,
            texto          = :texto,
            artista        = :artista,
            album         = :album,
            lancamento  = :lancamento,
            status          = :status,
            player_id      = :player_id,
            categoria_id = :categoria_id,
            usuario_id    = :usuario_id,
            data_edicao     = :data_edicao,
            slug                = :slug,
            imagem_musica   = :imagem_musica
            WHERE id_musica  = :id_musica");

        $this->db->bind("titulo",       $dados['titulo']);
        $this->db->bind("subtitulo",    $dados['subtitulo']);
        $this->db->bind("texto",        $dados['texto']);
        $this->db->bind("artista",       $dados['artista']);
        $this->db->bind("album",  $dados['album']);
        $this->db->bind("lancamento",  $dados['lancamento']);
        $this->db->bind("status",  $dados['status']);
        $this->db->bind("player_id",  $dados['player_id']);
        $this->db->bind("categoria_id",   $dados['categoria_id']);
        $this->db->bind("usuario_id",   $dados['usuario_id']);
        $this->db->bind("data_edicao",         $dados['data_edicao']);
        $this->db->bind("slug",         $dados['slug']);
        $this->db->bind("imagem_musica",  $dados['imagem_musica']);
        $this->db->bind("id_musica",      $id_musica);

        return $this->db->executa() ? true : false;
    }

    public function deletar($id_musica)
    {
        $this->db->query("DELETE FROM musicas WHERE id_musica = :id_musica");

        $this->db->bind("id_musica", $id_musica);

        if ($this->db->executa()) {

            return true;

        } else {

            return false;
        }
    }

    public function listar_todas_musicas()
    {
        $this->db->query("SELECT * FROM musicas ORDER BY data_criacao DESC");

        return $this->db->resultados();
    }

    public function listar_musica_home()
    {
        $this->db->query("SELECT * FROM musicas WHERE status = 1 ORDER BY data_criacao DESC");

        return $this->db->resultados();
    }

    public function busca_musica_id($id_musica)
    {
        $this->db->query("SELECT * FROM musicas WHERE id_musica = :id_musica");

        $this->db->bind("id_musica", $id_musica);

        return $this->db->resultado();
    }

    public function busca_musica_slug($slug)
    {
        $this->db->query("SELECT * FROM musicas WHERE slug = :slug");

        $this->db->bind("slug", $slug);

        return $this->db->resultado();
    }

    public function busca_musica_titulo($titulo)
    {
        $this->db->query("SELECT * FROM musicas WHERE titulo = :titulo");

        $this->db->bind("titulo", $titulo);

        return $this->db->resultado() ? true : false;
    }

    public function listar_qtd_musicas()
    {
        $this->db->query("SELECT COUNT(*) FROM musicas");

        return $this->db->resultados();
    }
}