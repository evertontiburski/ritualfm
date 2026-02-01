$(document).on('submit', '.admin-form, .user-form, .comment, .form-delete, .comment-form', function (e) {

    let form = $(this);

    e.preventDefault();

    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: new FormData(this),
        dataType: 'json',
        contentType: false,
        processData: false,

        success: function (resposta) {
            var responseDiv = $('#resposta').length ? $('#resposta') : $('#form-resposta');
            responseDiv.text(resposta.message).show();

            if (resposta.success) {

                // Se a resposta contiver o nome do usuário, atualize o topo
                if (resposta.data && resposta.data.usuario_nome) {
                    const userName = resposta.data.usuario_nome;
                    const dashboardUrl = resposta.data.redirecionar; // URL do dashboard

                    // Recria o HTML do menu do usuário no estado "logado"
                    const newUserMenuHTML = `
                <a href="#" class="ajax-link">
                    <div class="menu_ico">
                        <svg width="100%" height="100%" viewBox="0 3 24 24" aria-label="Person">
                            <g fill="none" fill-rule="evenodd">
                                <path fill="none" d="M0 0h24v24H0z"></path>
                                <path d="M14 8a2 2 0 10-4 0 2 2 0 004 0zm2 0a4 4 0 11-8 0 4 4 0 018 0zM7 19a1 1 0 01-2 0 7 7 0 0114 0 1 1 0 01-2 0 5 5 0 00-10 0z" fill="currentColor"></path>
                            </g>
                        </svg>
                    </div>
                    <a href="${dashboardUrl}" class="ajax-link">Olá, ${userName}!</a>
                </a>
            `;

                    // Substitui o conteúdo do container pelo novo HTML
                    $('#user-menu-container').html(newUserMenuHTML);
                }

                // Lógica de redirecionamento que já existia
                if (resposta.data && resposta.data.redirecionar) {
                    loadPage(resposta.data.redirecionar);
                } else {
                    form[0].reset();
                }
            }
        },
        error: function (xhr, status, error) {

            let mensagem = 'Erro desconhecido';

            try {

                const resposta = JSON.parse(xhr.responseText);

                mensagem = resposta.message || mensagem;

            } catch (e) {

                mensagem = 'Erro ao processar resposta do servidor';
            }

            $('#resposta').text(mensagem);
        }
    });
});
