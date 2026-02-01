<div class="admin-container">
    <div class="admin-layout">
        <aside class="admin-sidebar">
            <?php include '../app/Views/admin/menu.php'; ?>
        </aside>

        <main class="admin-content">
            <div class="admin-content-header">
                <div>
                    <h2>Lista de Usuários</h2>
                    <p class="subtitle">Gerencie todos os usuários cadastrados em seu site.</p>
                </div>
                <div class="header-actions">
                    <a href="<?= URL . '/admin/usuarios/cadastrar' ?>" class="btn btn-create ajax-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon-action">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>Cadastrar Usuário</span>
                    </a>
                </div>
            </div>

            <div class="admin-content-box" style="background: none; padding: 0;">
                <span id="resposta" class="ajax-response"></span>

                <div class="user-grid">
                    <?php if (isset($dados) && !empty($dados->usuarios)): ?>
                        <?php foreach ($dados->usuarios as $usuario): ?>
                            <div class="user-card">
                                <div class="user-card-header">
                                    <img src="<?= URL . '/public/images/icons/icon_120x120.png' ?>" alt="Avatar do usuário" class="user-avatar">
                                    <div class="user-identity">
                                        <strong><?= htmlspecialchars($usuario->nome) ?></strong>
                                        <a href="mailto:<?= htmlspecialchars($usuario->email) ?>"><?= htmlspecialchars($usuario->email) ?></a>
                                    </div>
                                    <?php if ($usuario->level != 3): ?>

                                        <span class="user-role-badge" style="background:#747474"> Membro </span>

                                    <?php else: ?>

                                        <span class="user-role-badge"> Admin </span>

                                    <?php endif; ?>
                                </div>

                                <div class="user-card-info">
                                    <div class="info-item">
                                        <span>Cadastrado em:</span>
                                        <strong><?= $usuario->cadastrado_em ? Funcoes::dataFormatada($usuario->cadastrado_em) : '-'; ?></strong>
                                    </div>
                                    <div class="info-item">
                                        <span>Última Edição:</span>
                                        <strong><?= $usuario->atualizado_em ? Funcoes::dataFormatada($usuario->atualizado_em) : '-'; ?></strong>
                                    </div>
                                </div>

                                <div class="user-card-actions">
                                    <a href="<?= URL . '/admin/usuarios/detalhes/' . $usuario->id_usuario ?>" class="btn btn-sm btn-view ajax-link" target="_blank">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z" />
                                            <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z" />
                                        </svg>
                                    </a>
                                    <a href="<?= URL . '/admin/usuarios/editar/' . $usuario->id_usuario ?>" class="btn btn-sm btn-edit ajax-link">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                                        </svg>
                                    </a>
                                    <form class="form-delete" action="<?= URL . '/admin/usuarios/deletar/' . $usuario->id_usuario ?>" method="post">
                                        <a type="submit" class="btn btn-sm btn-danger ajax-link"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                                            </svg>
                                        </a>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="no-results-card">
                            <p>Nenhum usuário encontrado.</p>
                            <a href="<?= URL . '/admin/usuarios/cadastrar' ?>" class="btn btn-primary ajax-link">Cadastrar Primeiro Usuário</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>

    </div>
</div>