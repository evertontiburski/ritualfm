<?php

class Database {

    // Variáveis privadas que armazenam os dados de conexão com o banco de dados
    private $servidor = SERVIDOR;            // Constante com o endereço do servidor (ex: localhost)
    private $porta = PORTA;                  // Constante com a porta do banco (ex: 3306 para MySQL)
    private $usuario = USUARIO;              // Constante com o nome do usuário do banco
    private $senha = SENHA;                  // Constante com a senha do banco
    private $nome_do_banco = BANCO_DE_DADOS; // Constante com o nome do banco de dados
    private $conn;                           // Armazena a conexão PDO
    private $stmt;                           // Armazena o statement preparado

    public function __construct()
    {
        // Define opções para a conexão PDO
        $opcoes = [
            PDO::ATTR_PERSISTENT => true,               // Habilita conexão persistente (reutiliza a conexão existente)
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION // Lança exceções em caso de erro
        ];

        try {
            // Cria uma nova conexão PDO usando as informações definidas acima
            $this->conn = new PDO(
                "mysql:host=$this->servidor;port=$this->porta;dbname=$this->nome_do_banco",
                $this->usuario,
                $this->senha,
                $opcoes
            );

        } catch(\PDOException $erro) {
            // Em caso de erro, exibe a mensagem e encerra o script
            echo $erro->getMessage();
            exit(0);
        }
    }

    // Prepara a SQL (deve ser chamada antes de bind e execute)
    public function query($sql) {
        $this->stmt = $this->conn->prepare($sql);
    }

    // Faz o bind dos parâmetros na query preparada
    public function bind($parametro, $valor, $tipo = null) {
        // Se o tipo não for informado, determina automaticamente
        if (is_null($tipo)) {
            switch(true) {
                case is_int($valor):
                    $tipo = PDO::PARAM_INT;
                    break;
                case is_bool($valor):
                    $tipo = PDO::PARAM_BOOL;
                    break;
                case is_null($valor):
                    $tipo = PDO::PARAM_NULL;
                    break;
                default:
                    $tipo = PDO::PARAM_STR;
            }
        }

        // Associa o valor ao parâmetro
        $this->stmt->bindValue($parametro, $valor, $tipo);
    }

    // Executa a query preparada
    public function executa() {
        return $this->stmt->execute();
    }

    // Retorna um único resultado (como objeto)
    public function resultado() {
        $this->executa();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    // Retorna todos os resultados (como array de objetos)
    public function resultados() {
        $this->executa();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Retorna o número de linhas afetadas pela última execução
    public function totalResultados() {
        return $this->stmt->rowCount();
    }

    // Retorna o ID do último registro inserido
    public function ultimoIdInserido() {
        return $this->conn->lastInsertId();
    }
}
