<div class="admin-container">
    <div class="admin-layout">
        <aside class="admin-sidebar">
            <?php include '../app/Views/admin/menu.php'; ?>
        </aside>

        <main class="admin-content">
            <div class="admin-content-header">
                <div>
                    <h2>Editar usuário</h2>
                    <p class="subtitle">Lembre-se que qualquer alteração realizada aqui, vai impactar diretamente o cadastro do usuário.</p>
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

                <form class="admin-form" method="post" action="<?= URL ?>/admin/usuarios/editar/<?= $dados->usuario->id_usuario ?>">

                    <div class="form-group">
                        <label for="nome">Nome</label>
                        <input type="text" name="nome" id="nome" class="form-control" value="<?= $dados->usuario ? $dados->usuario->nome : "" ?>"><br>
                    </div>

                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" name="email" id="email" class="form-control" value="<?= $dados->usuario ? $dados->usuario->email : "" ?>"><br>
                    </div>

                    <div class="form-group">
                        <label for="cadastrado_em">Data Cadastro</label>
                        <input type="date" name="cadastrado_em" id="cadastrado_em" class="form-control" value="<?= $dados->usuario ? Funcoes::dataHtml($dados->usuario->cadastrado_em) : "" ?>"><br>
                    </div>

                    <div class="form-group">
                        <label for="atualizado_em">Data Atualização</label>
                        <input type="date" name="atualizado_em" id="atualizado_em" class="form-control" value="<?= $dados->usuario ? Funcoes::dataHtml($dados->usuario->atualizado_em) : "" ?>"><br>
                    </div>

                    <div class="form-group">
                        <label for="senha">Senha</label>
                        <input type="password" name="senha" id="senha" class="form-control" placeholder="Você pode definir uma nova senha para o usuário, se não deixe em branco."><br>
                    </div>


                    <div class="form-row">

                        <div class="form-group">
                            <label for="level">Level</label>
                            <select name="level" id="level" class="form-control">
                                <option disabled selected>Selecione o Level</option>
                                <option value="0" <?php if ($dados->usuario->level == '0'): ?> selected <?php endif; ?>>Sem level</option>
                                <option value="1" <?php if ($dados->usuario->level == '1'): ?> selected <?php endif; ?>>Moderador</option>
                                <option value="2" <?php if ($dados->usuario->level == '2'): ?> selected <?php endif; ?>>Editor</option>
                                <option value="3" <?php if ($dados->usuario->level == '3'): ?> selected <?php endif; ?>>Administrador</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option disabled selected>Selecione o Status</option>
                                <option value="1" <?php if ($dados->usuario->status == '1'): ?> selected <?php endif; ?>>Ativado</option>
                                <option value="0" <?php if ($dados->usuario->status == '0'): ?> selected <?php endif; ?>>Desativado</option>
                            </select>
                        </div>

                    </div>

                    <input type="hidden" name="usuario_id" id="usuario_id" value="<?= $_SESSION['usuario_id'] ?>">

                    <div class="form-actions">
                        <div class="resposta ajax-response"></div>
                        <button type="submit" class="btn btn-primary ajax-link">Editar Usuário</button>
                    </div>

                </form>

            </div>
        </main>
    </div>
</div>