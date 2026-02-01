<?php

// Autoloader do HTML Purifier.
// O caminho deve ser relativo ao arquivo onde o formulário é processado.
require_once __DIR__ . '/../Libraries/htmlpurifier/library/HTMLPurifier.auto.php';

class Funcoes
{
    /**
     * Função segura para sanitizar HTML usando a biblioteca HTML Purifier.
     *
     * @param string $htmlSujo O HTML vindo do usuário (ex: $_POST['texto']).
     * @return string O HTML limpo e seguro.
     */
    public static function sanitizarHtmlComPurifier($htmlSujo)
    {
        if (empty($htmlSujo)) {
            return '';
        }

        $config = HTMLPurifier_Config::createDefault();

        // Configuração da sua lista branca de tags e atributos permitidos
        $config->set('HTML.Allowed', 'p[style],strong,b,em,i,u,h1[style],h2[style],h3[style],ul,ol,li[style],br,div[style],a[href|title|target]');

        // Lista branca de propriedades CSS permitidas (essencial para segurança)
        $config->set('CSS.AllowedProperties', 'text-align,color,background-color,font-weight,line-height');

        // Configurações de segurança para links
        $config->set('HTML.Nofollow', true); // Adiciona rel="nofollow"
        $config->set('HTML.TargetBlank', true); // Garante rel="noopener noreferrer" para target="_blank"

        $purifier = new HTMLPurifier($config);

        return $purifier->purify($htmlSujo);
    }

    // Proteção contra ataques XSS
    public static function protegerStringAtaqueXSS($stringSemProtecao)
    {
        $string = htmlspecialchars($stringSemProtecao, ENT_QUOTES, 'UTF-8');

        return $string;
    }

    // Faz a validação de um e-mail
    public static function validarEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    // Limpa números
    public static function limparNumero(string $numero): string
    {
        // Exemplo: Recebe: 026.476.380-71, Retorna 02647638071.
        return preg_replace('/[^0-9]/', '', $numero);
    }

    // Valida string com letras, números, pontos e espaços
    public static function validaStringComNumerosEPontos(string $string): bool
    {
        // Aceita também números de 0-9 e pontos
        if (preg_match('/^([áÁàÀãÃâÂéÉèÈêÊíÍìÌóÓòÒõÕôÔúÚùÙçÇaA-zZ0-9.]+)+((\s[áÁàÀãÃâÂéÉèÈêÊíÍìÌóÓòÒõÕôÔúÚùÙçÇaA-zZ0-9.]+)+)?$/', $string)) {

            // Retorna true se a string for válida
            return true;
        } else {

            // Retorna false se a string não seguir o padrão
            return false;
        }
    }

    // Valida string
    public static function validaString(string $string): bool
    {
        // Verifica se a string segue o padrão da expressão regular
        if (preg_match('/^([áÁàÀãÃâÂéÉèÈêÊíÍìÌóÓòÒõÕôÔúÚùÙçÇaA-zZ]+)+((\s[áÁàÀãÃâÂéÉèÈêÊíÍìÌóÓòÒõÕôÔúÚùÙçÇaA-zZ]+)+)?$/', $string)) {

            // Retorna true se a string for válida (apenas letras com ou sem acentos e espaços)
            return true;
        } else {

            // Retorna false se a string não seguir o padrão
            return false;
        }
    }

    // Gerando senhas seguras encriptado com ARGON2ID
    public static function gerarSenha(string $senha): string
    {
        return password_hash($senha, PASSWORD_ARGON2ID);
    }

    //Verificando senha encriptado com ARGON2ID
    public static function validarSenha(string $senha, string $hash): bool
    {
        return password_verify($senha, $hash);
    }

    // Gera senha aleatória com tamanho de 64, neste estilo: 0519d8f92bbf0be54508888b4575b9b2c4f4d202afda63f071ae668a460784d1
    public static function gerarSenhaTemporaria(int $tamanho = 32): string
    {
        return bin2hex(random_bytes($tamanho));
    }

    // Função estática que verifica se os campos obrigatórios foram preenchidos
    // Parâmetros:
    // - $dados: array com os dados recebidos, geralmente de um formulário
    // - $campos_obrigatorios: array onde a chave é o nome do campo e o valor é a mensagem de erro
    // Retorna o array $dados com mensagens de erro adicionadas se campos obrigatórios estiverem vazios
    public static function verificarCamposObrigatorio(array $dados, array $campos_obrigatorios): array
    {
        // Percorre todos os campos obrigatórios
        foreach ($campos_obrigatorios as $campo => $mesagem) {

            // Verifica se o campo está vazio nos dados recebidos
            if (empty($dados[$campo])) {

                // Se estiver vazio, adiciona uma nova chave no array de dados com a mensagem de erro
                // Exemplo: se o campo for 'email', cria 'email_erro' => 'Mensagem de erro'
                $dados[$campo . '_erro'] = $mesagem;
            }
        }

        // Retorna o array de dados atualizado (com ou sem erros)
        return $dados;
    }

    // Repostas em json
    public static function resposta(bool $success, string $message, int $codigo = 0, $data = null): void
    {
        http_response_code($codigo);

        header("Content-Type: application/json");

        echo json_encode([
            "success" => $success,
            "message" => $message,
            "codigo" => $codigo,
            "data" => $data
        ]);

        exit();
    }

    // Pegando o tipo de requisição
    public static function requisicao()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST' &&
            isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    // Pegando o tipo de requisição, APENAS PARA NAVEGAÇÃO EM LINKS
    public static function navegacaoAjax(): bool
    {
        // Verifica se é uma requisição GET feita pelo nosso JavaScript de navegação
        return $_SERVER['REQUEST_METHOD'] === 'GET' &&
            isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    // Url amigavel
    public static function slug($titulo)
    {
        // 1. Transliteração: Converte caracteres acentuados e especiais para seus equivalentes ASCII
        // Ex: 'á', 'ç', 'ñ' viram 'a', 'c', 'n'
        $url = iconv('UTF-8', 'ASCII//TRANSLIT', $titulo);

        // 2. Converte para minúsculas
        $url = strtolower($url);

        // 3. Remove caracteres que a transliteração pode ter deixado (como ~, ', ^)
        $url = preg_replace('/[~^´`\'"]/', '', $url);

        // 4. A REGRA PRINCIPAL: Substitui qualquer caracter que NÃO SEJA (^) letra (a-z), 
        // número (0-9) ou hífen (-) por um hífen
        // Isso limpa espaços, parênteses, vírgulas, pontos, etc
        $url = preg_replace('/[^a-z0-9-]+/', '-', $url);

        // 5. Remove hífens duplicados que podem ter sido gerados no passo anterior
        // Ex: "dois--hifens" vira "dois-hifens"
        $url = preg_replace('/-+/', '-', $url);

        // 6. Remove hífens do início e do fim da string
        // Ex: "-meu-slug-" vira "meu-slug"
        $url = trim($url, '-');

        return $url;
    }

    // função original
    // public static function redirecionar($url = null): void
    // {
    //     header('HTTP/1.1 302 Found');

    //     $local = ($url ? URL . '/' . $url : URL);

    //     header("Location: {$local}");
    //     exit;
    // }

    public static function redirecionar($url = null): void
    {
        // Verifica se a requisição foi feita via AJAX pelo nosso script
        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        $local = ($url ? URL . '/' . $url : URL);

        // Se for uma requisição AJAX, envia uma resposta JSON com a instrução de redirecionamento
        if ($isAjax) {
            self::resposta(true, 'Redirecionando...', 200, [
                'redirecionar' => $local
            ]);
        } else {
            // Se for uma requisição normal, faz o redirecionamento padrão via header
            header('HTTP/1.1 302 Found');
            header("Location: {$local}");
            exit;
        }
    }

    /**
     * Conta o tempo decorrido de uma data.
     * @param string $data Recebe uma data para formatar, exemplo: 2023-10-20 9:07:28.
     * @return string Retorna uma string formatada, exemplo: há 1 hora, há 1 semana, há 1 mês, há 1 ano.
     */

    public static function contarTempo(string $data): string
    {
        $agora = date(strtotime(date('Y-m-d H:i:s')));
        $tempo = strtotime($data);
        $diferenca = $agora - $tempo;

        $segundos = $diferenca;
        $minutos = round($diferenca / 60);
        $horas = round($diferenca / 3600);
        $dias = round($diferenca / 86400);
        $semanas = round($diferenca / 604800);
        $meses = round($diferenca / 2419200);
        $anos = round($diferenca / 29030400);

        if ($segundos <= 60) {
            return 'agora';
        } else if ($minutos <= 60) {
            return $minutos == 1 ? 'há 1 minuto' : 'há ' . $minutos . ' minutos';
        } else if ($horas <= 24) {
            return $horas == 1 ? 'há 1 hora' : 'há ' . $horas . ' horas';
        } else if ($dias <= 7) {
            return $dias == 1 ? 'ontem' : 'há ' . $dias . ' dias';
        } else if ($semanas <= 4) {
            return $semanas == 1 ? 'há 1 semana' : 'há ' . $semanas . ' semanas';
        } else if ($meses <= 12) {
            return $meses == 1 ? 'há 1 mês' : 'há ' . $meses . ' meses';
        } else {
            return $anos == 1 ? 'há 1 ano' : 'há ' . $anos . ' anos';
        }
    }


    /**
     * Exibe a data atual completa e formatada.
     * @return string Retorna uma string com dia da semana, data, mês e ano, exemplo: quinta-feira, 23 de novembro de 2023
     */

    public static function dataAtual(): string
    {
        $diaMes = date('d');
        $diaSemana = date('w');
        $mes = date('n') - 1;
        $ano = date('Y');

        $nomeDiasDaSemana = ['domingo', 'segunda-feira', 'terça-feira', 'quarta-feira', 'quinta-feira', 'sexta-feira', 'sabádo'];

        $nomesDosMeses = ['janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'];

        $dataFormatada = $nomeDiasDaSemana[$diaSemana] . ', ' . $diaMes . ' de ' . $nomesDosMeses[$mes] . ' de ' . $ano;

        // Resultado: quinta-feira, 23 de novembro de 2023

        return $dataFormatada;
    }


    /**
     * Exibe a data atual completa e formatada.
     * @return string Retorna uma string com dia da semana, data, mês e ano, exemplo: quinta-feira, 23 de novembro de 2023
    */
    public static function data_por_extenso(string $data): string
    {
        // Converter a string de data de entrada para um timestamp, strtotime() é para formatos como 'YYYY-MM-DD'
        $timestamp = strtotime($data);

        // Extrair as partes da data USANDO o timestamp criado
        $diaMes = date('d', $timestamp);
        $diaSemana = date('w', $timestamp); // 0 (domingo) a 6 (sábado)
        $mes = date('n', $timestamp) - 1;   // 1 (jan) a 12 (dez) -> subtrai 1 para o array
        $ano = date('Y', $timestamp);

        $nomeDiasDaSemana = ['domingo', 'segunda-feira', 'terça-feira', 'quarta-feira', 'quinta-feira', 'sexta-feira', 'sábado'];
        $nomesDosMeses = ['janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'];

        $dataFormatada = $nomeDiasDaSemana[$diaSemana] . ', ' . $diaMes . ' de ' . $nomesDosMeses[$mes] . ' de ' . $ano;

        // Resultado: quinta-feira, 23 de novembro de 2023
        return $dataFormatada;
    }


    // Retorna a data formatada AAAA/MM/DD usando o type date no formulário html.
    public static function dataHtml($data_banco)
    {
        $data_string = new DateTime($data_banco);

        $data_formatada = $data_string->format('Y-m-d');

        return $data_formatada;
    }

    // Retorna a data formatada DD/MM/AAAA.
    public static function dataFormatada($data_banco)
    {
        $data_string = new DateTime($data_banco);

        $data_formatada = $data_string->format('d/m/Y');

        return $data_formatada;
    }

    public static function valida_campo($campo_valor)
    {
        return !(isset($campo_valor) && trim($campo_valor) !== '');
    }

    // Gera senha aleatória com tamanho de 64, neste estilo: 0519d8f92bbf0be54508888b4575b9b2c4f4d202afda63f071ae668a460784d1
    public static function senha(int $tamanho = 32)
    {
        return bin2hex(random_bytes($tamanho));
    }

    // Gera tokens aleatórios com tamanho de 32, neste estilo: fca2072de8b10b265ccfca2e89b2603e
    public static function gerarToken(int $tamanho = 16)
    {
        return bin2hex(random_bytes($tamanho));
    }
}
