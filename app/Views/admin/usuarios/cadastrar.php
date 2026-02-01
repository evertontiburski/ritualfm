<div class="admin-container">
    <div class="admin-layout">
        <aside class="admin-sidebar">
            <?php include '../app/Views/admin/menu.php'; ?>
        </aside>

        <main class="admin-content">
            <div class="admin-content-header">
                <div>
                    <h2>Cadastrar Novo Usuário</h2>
                    <p class="subtitle">Cadastrar um novo usuário no sistema.</p>
                </div>
                <div class="header-actions">
                    <a href="<?= URL . '/admin/usuarios' ?>" class="btn btn-create ajax-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon-action">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                        <span>Voltar</span>
                    </a>
                </div>
            </div>

            <div class="admin-content-box">

                <form class="admin-form" method="post" action="<?= URL ?>/admin/usuarios/cadastrar">

                    <div class="form-group">
                        <label for="nome">Nome</label>
                        <input type="text" name="nome" id="nome" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="text" name="email" id="email" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="senha">Senha</label>
                        <input type="password" name="senha" id="senha" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="confirma_senha">Confirma Senha</label>
                        <input type="password" name="confirma_senha" id="confirma_senha" class="form-control">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="level">Level</label>
                            <select name="level" id="level" class="form-control">
                                <option disabled selected>Selecione o Level</option>
                                <option value="0">Sem level</option>
                                <option value="1">Moderador</option>
                                <option value="2">Editor</option>
                                <option value="3">Administrador</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option disabled selected>Selecione o Status</option>
                                <option value="1">Ativado</option>
                                <option value="0">Desativado</option>
                            </select>
                        </div>
                    </div>

                    <input type="hidden" name="usuario_id" id="usuario_id" value="<?= $_SESSION['usuario_id'] ?>">

                    <div class="form-actions">
                        <span class="resposta ajax-response"></span>
                        <button type="submit" class="btn btn-primary ajax-link">Cadastrar Usuário</button>
                    </div>

                </form>

            </div>
        </main>
    </div>
</div>