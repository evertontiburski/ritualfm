<?php

class UsuarioCadastro {

    // Propriedades
    private string $nome;
    private string $email;
    private string $senha;
    private int $status;
    private int $level;


    // Método construtor que é executado quando a classe é instanciada
    public function __construct(array $dados = [])
    {
        // Define status e level padrão para novos usuários
        $this->status = $this->status ?? 0;
        $this->level = $this->level ?? 0;

        if(!empty($dados)) {
            $this->preencher($dados);
        }
    }


    // Método/função da classe para preencher os dados automaticamente
    public function preencher(array $dados) {
        $this->setNome($dados['nome'] ?? '');
        $this->setEmail($dados['email'] ?? '');
        $this->setSenha($dados['senha'] ?? '');
        $this->setStatus($dados['status'] ?? 0);
        $this->setLevel($dados['level'] ?? 0);
    }


    // Getters e Setters
    public function getNome(): string
    {
        return $this->nome;
    }

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getSenha(): string
    {
        return $this->senha;
    }

    public function setSenha(string $senha): void
    {
        $this->senha = Funcoes::gerarSenha($senha);
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setLevel(int $level): void
    {
        $this->level = $level;
    }


    // Método retornando os dados como array para salvar no banco de dados
    public function dados_array(): array
    {
        return [
            'nome' => $this->nome,
            'email' => $this->email,
            'senha' => $this->senha,
            'status' => $this->status,
            'level' => $this->level
        ];
    }

}