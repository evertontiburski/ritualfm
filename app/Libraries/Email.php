<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

// Usar o __DIR__ para garantir que o caminho seja sempre relativo ao arquivo Email.php
require __DIR__ . '/phpmailer/src/PHPMailer.php';
require __DIR__ . '/phpmailer/src/SMTP.php';
require __DIR__ . '/phpmailer/src/Exception.php';

class Email
{
    private PHPMailer $mail;
    private array $anexos;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);

        //$this->mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $this->mail->isSMTP();
        $this->mail->Host       = 'mail.ritualfm.com';
        $this->mail->SMTPAuth   = true;
        $this->mail->Username   = 'noreply@ritualfm.com';
        $this->mail->Password   = 'Macacodev01*';
        $this->mail->SMTPSecure = 'ssl';
        $this->mail->Port       = 465;

        $this->mail->setLanguage('pt_br');
        $this->mail->CharSet    = 'UTF-8';
        $this->mail->isHTML(true);

        $this->anexos = [];
    }

    public function criar(string $assunto, $conteudo, string $destinatarioEmail, string $destinatarioNome, ?string $responderEmail = null, ?string $responderNome = null): static
    {
        $this->mail->Subject = $assunto;
        $this->mail->Body    = $conteudo;

        $this->mail->addAddress($destinatarioEmail, $destinatarioNome);

        if($responderEmail !== null && $responderNome != null) {
            $this->mail->addReplyTo($responderEmail, $responderNome);
        }

        return $this;
    }

    public function enviar(string $remetenteEmail, string $remetenteNome): bool
    {
        try {
            $this->mail->setFrom($remetenteEmail, $remetenteNome);

            // Percorrendo os anexos
            foreach($this->anexos as $anexo) {
                $this->mail->addAttachment($anexo['caminho'], $anexo['nome']);
            }

            $this->mail->send();

            return true;

        } catch (\Exception $ex) {
            // echo 'Erro ao enviar email: ' . $ex->getMessage();
            echo "Não foi possível enviar a mensagem. Erro do Mailer: {$this->mail->$ex}";
            return false;
        }
    }

    public function anexar(string $caminhoArquivo, ?string $nomeArquivo = null): static
    {
        $nomeArquivo = $nomeArquivo ?? basename($caminhoArquivo);

        $this->anexos[] = [
            'caminho' => $caminhoArquivo,
            'nome' => $nomeArquivo
        ];

        return $this;
    }
}

