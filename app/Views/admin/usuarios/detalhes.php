<div class="admin-container">
    <div class="admin-layout">
        <aside class="admin-sidebar">
            <?php include '../app/Views/admin/menu.php'; ?>
        </aside>

        <main class="admin-content">
            <div class="admin-content-header">
                <div>
                    <h2>Detalhes do Usuário</h2>
                    <p class="subtitle">Todas as informações do usuário que esta armazenado no sistema.</p>
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

                <form class="admin-form">

                    <?php foreach ($dados as $usuario): ?>

                        <div class="form-group">
                            <label for="nome">Nome</label>
                            <input type="text" name="nome" id="nome" class="form-control" value="<?= $usuario->nome ?>" disabled>
                        </div>

                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="text" name="email" id="email" class="form-control" value="<?= $usuario->email ?>" disabled>
                        </div>

                        <div class="form-group">
                            <label for="email">Data Cadastro</label>
                            <input type="date" name="email" id="email" class="form-control" value="<?= Funcoes::dataHtml($usuario->cadastrado_em) ?>" disabled>
                        </div>

                        <div class="form-group">
                            <label for="email">Data Atualização</label>
                            <input type="date" name="email" id="email" class="form-control" value="<?= Funcoes::dataHtml($usuario->atualizado_em) ?>" disabled>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="level">Level</label>
                                <select name="level" id="level" class="form-control" disabled>
                                    <option value="0" <?php if ($usuario->level == '0'): ?> selected <?php endif; ?>>Sem level</option>
                                    <option value="1" <?php if ($usuario->level == '1'): ?> selected <?php endif; ?>>Moderador</option>
                                    <option value="2" <?php if ($usuario->level == '2'): ?> selected <?php endif; ?>>Editor</option>
                                    <option value="3" <?php if ($usuario->level == '3'): ?> selected <?php endif; ?>>Administrador</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control" disabled>
                                    <option disabled selected>Selecione o Status</option>
                                    <option value="0" <?php if ($usuario->status == '0'): ?> selected <?php endif; ?>>Desativado</option>
                                    <option value="1" <?php if ($usuario->status == '1'): ?> selected <?php endif; ?>>Ativado</option>
                                </select>
                            </div>
                        </div>

                    <?php endforeach; ?>

                </form>

            </div>
        </main>
    </div>
</div>