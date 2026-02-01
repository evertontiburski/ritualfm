<?php

class Upload
{
    private string $caminhoUploads;
    private string $caminhoMiniaturas;

    private array $arquivo;
    private string $nomeFinal;

    private ?string $resultado = null;
    private ?string $erro = null;

    /**
     * Inicia a classe de Upload.
     * @param string $caminhoPublicAbsoluto O caminho absoluto para a sua pasta 'public'.
     * Ex: 'C:/xampp/htdocs/ritualfm/public'
     */
    public function __construct(string $caminhoPublicAbsoluto)
    {
        // Define os caminhos principais baseados no caminho público absoluto
        $this->caminhoUploads = $caminhoPublicAbsoluto . DIRECTORY_SEPARATOR . 'uploads';
        $this->caminhoMiniaturas = $this->caminhoUploads . DIRECTORY_SEPARATOR . 'miniatura';

        // Garante que os diretórios de upload e de miniatura existam
        if (!is_dir($this->caminhoUploads)) {
            mkdir($this->caminhoUploads, 0755, true);
        }
        if (!is_dir($this->caminhoMiniaturas)) {
            mkdir($this->caminhoMiniaturas, 0755, true);
        }
    }

    public function getResultado(): ?string
    {
        return $this->resultado;
    }

    public function getErro(): ?string
    {
        return $this->erro;
    }

    /**
     * Processa o upload de um arquivo, incluindo a exclusão de arquivos antigos.
     *
     * @param array $arquivo O array $_FILES['seu_input']
     * @param string|null $nomeBase O nome base para o arquivo, sem extensão (ex: slug do título)
     * @param string $subDiretorio A subpasta dentro de 'uploads' (ex: 'imagens')
     * @param string|null $nomeArquivoAntigo O nome do arquivo antigo (com extensão) a ser deletado.
     * @param integer $tamanhoMaxMB O tamanho máximo do arquivo em Megabytes.
     * @return boolean True se o upload foi bem-sucedido, false caso contrário.
     */
    public function arquivo(array $arquivo, ?string $nomeBase, string $subDiretorio = 'imagens', ?string $nomeArquivoAntigo = null, int $tamanhoMaxMB = 2): bool
    {
        $this->arquivo = $arquivo;
        $nomeBase = $nomeBase ?? pathinfo($this->arquivo['name'], PATHINFO_FILENAME);

        // 1. Validação do arquivo
        if (!$this->_validarArquivo($tamanhoMaxMB)) {
            return false;
        }

        // 2. Se um nome de arquivo antigo foi fornecido, deleta os arquivos correspondentes
        if ($nomeArquivoAntigo) {
            $this->_deletarArquivosAntigos($subDiretorio, $nomeArquivoAntigo);
        }

        // 3. Prepara o novo diretório e nome de arquivo
        $this->_criarSubDiretorio($subDiretorio);
        $this->_renomearArquivo($nomeBase, $subDiretorio);
        
        // 4. Move o novo arquivo e cria a miniatura
        if ($this->_moverArquivo($subDiretorio)) {
            $this->resultado = $this->nomeFinal;
            return true;
        }

        return false;
    }

    /**
     * Deleta o arquivo principal antigo e sua respectiva miniatura.
     */
    private function _deletarArquivosAntigos(string $subDiretorio, string $nomeArquivoAntigo): void
    {
        // Caminho para o arquivo principal antigo
        $caminhoArquivoAntigo = $this->caminhoUploads . DIRECTORY_SEPARATOR . $subDiretorio . DIRECTORY_SEPARATOR . $nomeArquivoAntigo;
        if (file_exists($caminhoArquivoAntigo)) {
            unlink($caminhoArquivoAntigo);
        }

        // Caminho para a miniatura antiga
        $caminhoMiniaturaAntiga = $this->caminhoMiniaturas . DIRECTORY_SEPARATOR . $nomeArquivoAntigo;
        if (file_exists($caminhoMiniaturaAntiga)) {
            unlink($caminhoMiniaturaAntiga);
        }
    }

    /**
     * Valida o arquivo com base na extensão, tipo e tamanho.
     */
    private function _validarArquivo(int $tamanhoMaxMB): bool
    {
        $extensao = strtolower(pathinfo($this->arquivo['name'], PATHINFO_EXTENSION));
        $extensoesValidas = ['jpeg', 'jpg', 'png'];
        $tiposValidos = ['image/jpeg', 'image/jpg', 'image/png'];

        if (!in_array($extensao, $extensoesValidas)) {
            $this->erro = 'Extensão da imagem não permitida.';
            return false;
        }
        if (!in_array($this->arquivo['type'], $tiposValidos)) {
            $this->erro = 'Tipo de arquivo não permitido.';
            return false;
        }
        if ($this->arquivo['size'] > $tamanhoMaxMB * 1024 * 1024) {
            $this->erro = "O arquivo é muito grande; o limite é de {$tamanhoMaxMB}MB.";
            return false;
        }

        return true;
    }

    /**
     * Renomeia o arquivo para evitar duplicação.
     */
    private function _renomearArquivo(string $nomeBase, string $subDiretorio): void
    {
        $extensao = pathinfo($this->arquivo['name'], PATHINFO_EXTENSION);
        $this->nomeFinal = $nomeBase . '.' . $extensao;

        $caminhoCompleto = $this->caminhoUploads . DIRECTORY_SEPARATOR . $subDiretorio . DIRECTORY_SEPARATOR . $this->nomeFinal;
        
        if (file_exists($caminhoCompleto)) {
            $this->nomeFinal = $nomeBase . '-' . uniqid() . '.' . $extensao;
        }
    }

    /**
     * Cria a subpasta (ex: 'imagens') se ela não existir.
     */
    private function _criarSubDiretorio(string $subDiretorio): void
    {
        $caminho = $this->caminhoUploads . DIRECTORY_SEPARATOR . $subDiretorio;
        if (!is_dir($caminho)) {
            mkdir($caminho, 0755, true);
        }
    }

    /**
     * Move o arquivo do diretório temporário para o destino final.
     */
    private function _moverArquivo(string $subDiretorio): bool
    {
        $destino = $this->caminhoUploads . DIRECTORY_SEPARATOR . $subDiretorio . DIRECTORY_SEPARATOR . $this->nomeFinal;

        if (move_uploaded_file($this->arquivo['tmp_name'], $destino)) {
            $this->_criarMiniatura($destino);
            return true;
        }
        
        $this->erro = 'Erro final ao mover o arquivo para o servidor.';
        return false;
    }

    /**
     * Cria uma miniatura da imagem que acabou de ser salva.
     */
    private function _criarMiniatura(string $caminhoImagemOriginal, int $larguraMax = 398, int $alturaMax = 214): void
    {
        list($larguraOriginal, $alturaOriginal, $tipo) = getimagesize($caminhoImagemOriginal);

        $ratio = $larguraOriginal / $alturaOriginal;
        if ($larguraMax / $alturaMax > $ratio) {
            $larguraMax = $alturaMax * $ratio;
        } else {
            $alturaMax = $larguraMax / $ratio;
        }

        $imagemFinal = imagecreatetruecolor($larguraMax, $alturaMax);
        $imagemOriginal = null;

        switch ($tipo) {
            case IMAGETYPE_JPEG:
                $imagemOriginal = imagecreatefromjpeg($caminhoImagemOriginal);
                break;
            case IMAGETYPE_PNG:
                $imagemOriginal = imagecreatefrompng($caminhoImagemOriginal);
                imagealphablending($imagemFinal, false);
                imagesavealpha($imagemFinal, true);
                break;
            default:
                return;
        }

        imagecopyresampled($imagemFinal, $imagemOriginal, 0, 0, 0, 0, $larguraMax, $alturaMax, $larguraOriginal, $alturaOriginal);
        
        $destinoMiniatura = $this->caminhoMiniaturas . DIRECTORY_SEPARATOR . $this->nomeFinal;

        switch ($tipo) {
            case IMAGETYPE_JPEG:
                imagejpeg($imagemFinal, $destinoMiniatura, 85);
                break;
            case IMAGETYPE_PNG:
                imagepng($imagemFinal, $destinoMiniatura, 9);
                break;
        }
        
        imagedestroy($imagemOriginal);
        imagedestroy($imagemFinal);
    }
}