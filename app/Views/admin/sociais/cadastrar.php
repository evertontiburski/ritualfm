<div class="admin-container">
    <div class="admin-layout">
        <aside class="admin-sidebar">
            <?php include '../app/Views/admin/menu.php'; ?>
        </aside>

        <main class="admin-content">
            <div class="admin-content-header">
                <div>
                    <h2>Cadastrar Nova Rede Social</h2>
                    <p class="subtitle">Lembre-se que o Nome, e Link suporta até 255 caracteres.</p>
                </div>
                <div class="header-actions">
                    <a href="<?= URL . '/admin/sociais' ?>" class="btn btn-create ajax-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon-action">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                        <span>Voltar</span>
                    </a>
                </div>
            </div>

            <div class="admin-content-box">

                <form class="admin-form" method="post" action="<?= URL ?>/admin/sociais/cadastrar" enctype="multipart/form-data">

                    <div class="form-group">
                        <label for="nome">Nome da Rede Social</label>
                        <input type="text" name="nome" id="nome" class="form-control" placeholder="Exemplo: Facebook">
                    </div>

                    <div class="form-group">
                        <label for="link">Link da sua Rede Social</label>
                        <input type="text" name="link" id="link" class="form-control" placeholder="Exemplo: https://www.facebook.com/minha-pagina">
                    </div>

                    <div class="form-group">
                        <label for="icone">Ícone</label>
                        <input type="text" name="icone" id="icone" class="form-control" placeholder="Exemplo: <path d='seu código do ícone svg vai aqui'></path>">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="altura">Altura</label>
                            <input type="text" name="altura" id="altura" class="form-control" placeholder="Exemplo: height='o valor da altura vai aqui'">
                        </div>

                        <div class="form-group">
                            <label for="largura">Largura</label>
                            <input type="text" name="largura" id="largura" class="form-control" placeholder="Exemplo: width='o valor da largura vai aqui'">
                        </div>
                    </div>

                    <div class="form-group" style="max-width: 510px;">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option disabled selected>Selecione o Status</option>
                            <option value="1">Ativado</option>
                            <option value="0">Desativado</option>
                        </select>
                    </div>

                    <input type="hidden" name="usuario_id" id="usuario_id" value="<?= $_SESSION['usuario_id'] ?>">

                    <div class="form-actions">
                        <span class="resposta ajax-response"></span>
                        <button type="submit" class="btn btn-primary ajax-link">Cadastrar Rede Social</button>
                    </div>

                </form>

            </div>
        </main>
    </div>
</div>