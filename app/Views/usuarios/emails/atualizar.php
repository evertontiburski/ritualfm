<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .button { color: #FF0000 !important; padding: 12px 24px; border-radius: 24px; text-decoration: none; font-family: Arial, sans-serif; font-size: 14px; line-height: 24px; font-weight: 500; text-transform: uppercase; display: inline-block; }
        a { text-decoration: none; }
    </style>
</head>
<body>
    <table width="100%" bgcolor="#f4f4f4" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <table bgcolor="#ffffff" width="600" border="0" cellpadding="20" cellspacing="0">
                    <tr>
                        <td>
                            <p style="font-size: 16px; font-weight: 400; line-height: 26px; color: #333333;">Olá <strong><?= $usuario['nome']; ?></strong>,</p>
                            <p style="font-size: 16px; font-weight: 400; line-height: 26px; color: #333333;">Você esqueceu a sua senha?</p>
                            <p style="font-size: 16px; font-weight: 400; line-height: 26px; color: #333333;">Não se preocupe, clique no botão abaixo e defina uma nova senha.</p>
                            
                            <!-- Botão de ação -->
                            <table align="center" border="0" cellpadding="0" cellspacing="0" style="margin-top: 50px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ url('minha-conta/atualizar-senha/'~usuario.senha_temporaria) }}" class="button">Redefinir senha</a>
                                    </td>
                                </tr>
                            </table>
                            <table align="center" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center">
                                        <p style="font-size: 16px; font-weight: 400; line-height: 26px; color: #333333; margin-top: 20px;">Ou acesse pelo link</p>
                                    </td>
                                </tr>
                            </table>
                            <table align="center" border="0" cellpadding="0" cellspacing="0" style="margin-bottom: 50px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ url('minha-conta/atualizar-senha/'~usuario.senha_temporaria) }}" style="font-size: 16px; font-weight: 400; line-height: 26px; color: #333333; text-decoration: underline;">Redefinir senha</a>
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="font-size: 16px; font-weight: 400; line-height: 26px; color: #333333;">Ah, lembre-se que este acesso para redefinir senha é válido por 24 horas a partir do recebimento deste e-mail. Passado esse tempo, por favor, peça de novo para redefinir sua senha!</p>
                            <p style="font-size: 16px; font-weight: 400; line-height: 26px; color: #333333; margin-bottom: 30px;">Se tiver alguma dúvida ou precisar de ajuda, entre em contato conosco pelo e-mail <a href="mailto:suporte@notificabrasil.com.br" style="color: #333333; text-decoration: underline;">suporte@notificabrasil.com.br</a>.</p>
                            
                            <!-- Assinatura -->
                            <p style="font-size: 16px; font-weight: 400; line-height: 26px; color: #333333;">Atenciosamente,<br>Equipe {{ constant('SITE_NOME') }}</p>
                            
                            <!-- Rodapé com links -->
                            <p style="text-align: center; font-size: 12px; color: #666666; margin-top: 30px;">
                                <a href="{{ url('/politica-de-privacidade') }}" style="color: #666666; text-decoration: underline;">política de privacidade</a> | 
                                <a href="{{ url('/sobre-nos') }}" style="color: #666666; text-decoration: underline;">sobre nós</a> | 
                                <a href="{{ url('/termos-de-uso') }}" style="color: #666666; text-decoration: underline;">termos de uso</a>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
