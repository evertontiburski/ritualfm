<div class="admin-container">
    <div class="admin-layout">
        <aside class="admin-sidebar">
            <?php include '../app/Views/admin/menu.php'; ?>
        </aside>

        <main class="admin-content">
            <div class="admin-content-header">
                <div>
                    <h2>Editar Anuncie</h2>
                    <p class="subtitle">Lembre-se que o Título, suporta até 255 caracteres.</p>
                </div>
                <div class="header-actions">
                    <a href="<?= URL . '/admin/anuncie' ?>" class="btn btn-create ajax-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon-action">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                        <span>Voltar</span>
                    </a>
                </div>
            </div>

            <div class="admin-content-box">

                <form class="admin-form" method="post" action="<?= URL ?>/admin/anuncie/editar/<?= $dados->anuncie->id_anuncie ?>" enctype="multipart/form-data">

                    <div class="form-group">
                        <label for="titulo">Título</label>
                        <input type="text" name="titulo" id="titulo" class="form-control" value="<?= $dados->anuncie ? $dados->anuncie->titulo : "" ?>"><br>
                    </div>

                    <div class="form-group">
                        <label>Texto</label>
                        <div class="editor-container">
                            <div class="toolbar">
                                <div class="toolbar-group">
                                    <button type="button" class="comando-editor" data-comando="undo" title="Desfazer"><i class="fas fa-undo"></i></button>
                                    <button type="button" class="comando-editor" data-comando="redo" title="Refazer"><i class="fas fa-redo"></i></button>
                                </div>
                                <div class="toolbar-group">
                                    <select class="comando-formato">
                                        <option value="p">Parágrafo</option>
                                        <option value="h1">Título 1</option>
                                        <option value="h2">Título 2</option>
                                        <option value="h3">Título 3</option>
                                    </select>
                                    <select class="comando-fonte">
                                        <option value="Arial">Arial</option>
                                        <option value="Verdana">Verdana</option>
                                        <option value="Times New Roman">Times New Roman</option>
                                    </select>
                                </div>
                                <div class="toolbar-group">
                                    <button type="button" class="comando-editor" data-comando="bold" title="Negrito"><i class="fas fa-bold"></i></button>
                                    <button type="button" class="comando-editor" data-comando="italic" title="Itálico"><i class="fas fa-italic"></i></button>
                                    <button type="button" class="comando-editor" data-comando="underline" title="Sublinhado"><i class="fas fa-underline"></i></button>
                                </div>
                                <div class="toolbar-group">
                                    <button type="button" class="comando-editor" data-comando="justifyLeft" title="Alinhar à Esquerda"><i class="fas fa-align-left"></i></button>
                                    <button type="button" class="comando-editor" data-comando="justifyCenter" title="Centralizar"><i class="fas fa-align-center"></i></button>
                                    <button type="button" class="comando-editor" data-comando="justifyRight" title="Alinhar à Direita"><i class="fas fa-align-right"></i></button>
                                </div>
                                <div class="toolbar-group">
                                    <button type="button" class="comando-editor" data-comando="insertOrderedList" title="Lista Ordenada"><i class="fas fa-list-ol"></i></button>
                                    <button type="button" class="comando-editor" data-comando="insertUnorderedList" title="Lista Não Ordenada"><i class="fas fa-list-ul"></i></button>
                                </div>
                                <div class="toolbar-group">
                                    <select id="comando-espacamento" title="Espaçamento entre linhas">
                                        <option value="1">Espaçamento 1.0</option>
                                        <option value="1.5" selected>Espaçamento 1.5</option>
                                        <option value="2">Espaçamento 2.0</option>
                                        <option value="2.5">Espaçamento 2.5</option>
                                    </select>
                                </div>
                            </div>
                            <div id="editor" contenteditable="true">
                                <?= $dados->anuncie ? $dados->anuncie->texto : "" ?>
                            </div>
                        </div>
                        <textarea name="texto" id="texto" class="form-control" style="display: none;"></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="endereco">Endereço</label>
                            <input type="text" name="endereco" id="endereco" class="form-control" value="<?= $dados->anuncie ? $dados->anuncie->endereco : "" ?>">
                        </div>

                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="email" name="email" id="email" class="form-control" value="<?= $dados->anuncie ? $dados->anuncie->email : "" ?>">
                        </div>

                        <div class="form-group">
                            <label for="telefone">Telefone</label>
                            <input type="phone" name="telefone" id="telefone" class="form-control" value="<?= $dados->anuncie ? $dados->anuncie->telefone : "" ?>">
                        </div>

                        <div class="form-group">
                            <label for="nome">Nome</label>
                            <input type="text" name="nome" id="nome" class="form-control" value="<?= $dados->anuncie ? $dados->anuncie->nome : "" ?>">
                        </div>

                        <div class="form-group">
                            <label for="cargo">Cargo</label>
                            <input type="text" name="cargo" id="cargo" class="form-control" value="<?= $dados->anuncie ? $dados->anuncie->cargo : "" ?>">
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option disabled selected>Selecione o Status</option>
                                <option value="1" <?php if ($dados->anuncie->status == '1'): ?> selected <?php endif; ?>>Ativado</option>
                                <option value="0" <?php if ($dados->anuncie->status == '0'): ?> selected <?php endif; ?>>Desativado</option>
                            </select>
                        </div>

                    </div>

                    <input type="hidden" name="usuario_id" id="usuario_id" value="<?= $_SESSION['usuario_id'] ?>">

                    <div class="form-actions">
                        <div class="resposta ajax-response"></div>
                        <button type="submit" class="btn btn-primary ajax-link">Editar Anuncie</button>
                    </div>

                </form>

            </div>
        </main>
    </div>
</div>