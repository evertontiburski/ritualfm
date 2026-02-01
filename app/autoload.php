<?php

// Função de autoload para carregar automaticamente classes PHP sem precisar de "require" manual
spl_autoload_register(function ($classe) {

    // Define os diretórios onde o sistema vai procurar pelas classes
    $diretorios = [
        'Libraries', // Diretório onde ficam bibliotecas/classes externas ou reutilizáveis
        'Helpers'    // Diretório onde ficam funções auxiliares ou utilitárias
    ];

    // Percorre cada diretório definido acima
    foreach ($diretorios as $diretorio) {

        // Monta o caminho completo do arquivo da classe, com base no nome da classe e no diretório atual
        $arquivo = (__DIR__ . DIRECTORY_SEPARATOR . $diretorio . DIRECTORY_SEPARATOR . $classe . '.php');

        // Verifica se o arquivo da classe existe no caminho montado
        if (file_exists($arquivo)) {
            // Se o arquivo existir, ele é incluído (carregado) no código
            require_once($arquivo);
        }
    }
});
