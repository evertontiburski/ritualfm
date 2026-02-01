<?php
header('Content-Type: application/json'); // Define o tipo de conteúdo da resposta como JSON

// Verifica se o método da requisição é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Método não permitido
    echo json_encode(['success' => false, 'message' => 'Método não permitido. Use POST.']);
    exit;
}

// Obtém o corpo da requisição (JSON)
$json_data = file_get_contents('php://input');

// Decodifica o JSON para um array associativo PHP
$data = json_decode($json_data, true);

// Verifica se o JSON foi decodificado corretamente
if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400); // Requisição inválida
    echo json_encode(['success' => false, 'message' => 'Erro ao decodificar JSON.']);
    exit;
}

// --- Validação e Sanitização dos Dados ---
$nome = filter_var(trim($data['nome'] ?? ''), FILTER_SANITIZE_STRING);
$assunto = filter_var(trim($data['assunto'] ?? ''), FILTER_SANITIZE_STRING);
$email_remetente = filter_var(trim($data['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$mensagem_corpo = filter_var(trim($data['mensagem'] ?? ''), FILTER_SANITIZE_STRING);
$estado = filter_var(trim($data['estado'] ?? 'Não informado'), FILTER_SANITIZE_STRING);
$telefone = filter_var(trim($data['telefone'] ?? 'Não informado'), FILTER_SANITIZE_STRING);

// Verifica campos obrigatórios e e-mail válido
if (empty($nome) || empty($assunto) || empty($email_remetente) || empty($mensagem_corpo)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Erro: Campos obrigatórios (Nome, Assunto, E-mail, Mensagem) faltando ou e-mail inválido.']);
    exit;
}

// --- Configuração do E-mail ---
$destinatario = 'contato@ritualfm.com'; // *** SUBSTITUA PELO SEU E-MAIL DE DESTINO ***
$titulo_email = "Contato via Site: " . $assunto;

// Corpo do E-mail
$corpo = "Nova mensagem recebida através do formulário de contato:\n\n";
$corpo .= "Nome: " . $nome . "\n";
$corpo .= "E-mail: " . $email_remetente . "\n";
$corpo .= "Estado: " . $estado . "\n";
$corpo .= "Telefone: " . $telefone . "\n";
$corpo .= "Assunto: " . $assunto . "\n\n";
$corpo .= "Mensagem:\n" . $mensagem_corpo . "\n";

// Cabeçalhos do E-mail
$headers = "From: " . $nome . " <" . $email_remetente . ">\r\n"; // Usa o e-mail do remetente no 'From'
$headers .= "Reply-To: " . $email_remetente . "\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// --- Envio do E-mail ---
// A função mail() do PHP depende da configuração do servidor (sendmail, postfix, etc.)
// Pode não funcionar em todos os ambientes de hospedagem sem configuração adicional.
if (mail($destinatario, $titulo_email, $corpo, $headers)) {
    echo json_encode(['success' => true, 'message' => 'E-mail enviado com sucesso!']);
} else {
    http_response_code(500); // Erro interno do servidor
    // Não exponha detalhes técnicos do erro ao cliente em produção
    error_log("Erro ao enviar e-mail de: $email_remetente com assunto: $assunto"); // Log do erro no servidor
    echo json_encode(['success' => false, 'message' => 'Erro interno do servidor ao tentar enviar o e-mail. Verifique a configuração do servidor de e-mail (mail()).']);
}

?>
