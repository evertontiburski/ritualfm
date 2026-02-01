<?php

class Rotas
{
    // Atributos da classe com valores padrão
    // $controlador: nome do controlador a ser chamado (ex: Paginas)
    // $metodo: método do controlador a ser executado (ex: index)
    // $parametros: parâmetros que serão passados ao método
    private $controlador = 'Home';
    private $metodo = 'index';
    private $parametros = [];

    public function __construct()
    {
        // Pega a URL atual, se existir. Caso contrário, define um array com índice 0 (evita erro)
        $url = $this->url() ? $this->url() : [0];

        // Verifica se o primeiro segmento da URL corresponde a um controlador existente no diretório
        if (file_exists('../app/Controllers/' . ucwords($url[0]) . '.php')) {
            // Define o controlador com base na URL
            $this->controlador = ucwords($url[0]);

            // Remove o índice 0 do array $url, já que foi usado para definir o controlador
            unset($url[0]);
        }

        // Inclui o arquivo do controlador
        require_once '../app/Controllers/' . $this->controlador . '.php';

        // Instancia o controlador
        $this->controlador = new $this->controlador;

        // Verifica se há um segundo segmento na URL (possível método)
        if (isset($url[1])) {
            // Verifica se o método existe dentro do controlador
            if (method_exists($this->controlador, $url[1])) {
                // Define o método a ser executado
                $this->metodo = $url[1];

                // Remove o índice 1 do array $url
                unset($url[1]);
            }
        }

        // O que restou da URL são considerados parâmetros e são passados ao método
        // Se não houver parâmetros, será um array vazio
        $this->parametros = $url ? array_values($url) : [];

        // Executa o método do controlador, passando os parâmetros (se houver)
        call_user_func_array([$this->controlador, $this->metodo], $this->parametros);
    }

    // Função responsável por capturar e tratar a URL
    private function url()
    {
        // Captura a URL enviada pelo .htaccess (RewriteRule redireciona tudo para index.php?url=...)
        $url = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL); // Limpa caracteres ilegais

        if (isset($url)) {
            // Remove espaços do início/fim e barras extras no final
            $url = trim(rtrim($url, '/'));

            // Divide a URL em partes, retornando como array
            return explode('/', $url);
        }
    }
}
