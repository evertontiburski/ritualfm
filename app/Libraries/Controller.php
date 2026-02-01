<?php

class Controller
{

    // Carrega os modelos
    public function model($model)
    {

        require_once '../app/Models/' . $model . '.php';

        return new $model;
    }

    // Carrega as views
    public function view($view, $dados = [])
    {
        // Verifica se é uma requisição AJAX enviada pelo nosso script
        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        $viewFile = '../app/Views/' . $view . '.php';

        if (file_exists($viewFile)) {

            // Se NÃO for AJAX, carrega o topo da página
            if (!$isAjax) {
                require_once '../app/Views/topo.php';
            }

            // Carrega o "cenário" (o conteúdo principal)
            require_once $viewFile;

            // Se NÃO for AJAX, carrega o rodapé
            if (!$isAjax) {
                require_once '../app/Views/rodape.php';
            }
        } else {
            
            die('Erro: A view "' . $view . '" não existe no caminho esperado.');
        }
    }
}
