<div class="admin-container">
    <div class="admin-layout">
        <aside class="admin-sidebar">
            <?php include '../app/Views/admin/menu.php'; ?>
        </aside>

        <main class="admin-content">
            <div class="admin-content-header">
                <div>
                    <h2>Editar post</h2>
                    <p class="subtitle">Lembre-se que o Título, e Subtítulo suporta até 255 caracteres.</p>
                </div>
                <div class="header-actions">

                    <a href="<?= URL . '/admin/posts' ?>" class="btn btn-create ajax-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon-action">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                        <span>Voltar</span>
                    </a>
                    </a>
                </div>
            </div>

            <div class="admin-content-box">

                <form class="admin-form" method="post" action="<?= URL ?>/admin/posts/editar/<?= $dados->post->id_post ?>" enctype="multipart/form-data">

                    <div class="form-group" data-component="file-input">

                        <input type="file"
                            name="imagem_post"
                            id="imagem_post"
                            class="input-file-hidden"
                            accept=".png, .jpeg, .jpg"
                            data-role="file-input-target">

                        <label for="imagem_post" class="custom-file-upload">
                            <span class="btn btn-primary file-button">
                                <?php
                                // Lógica do botão (não muda)
                                if ($dados->post->id_post) {
                                    echo 'Alterar imagem';
                                } else {
                                    echo 'Escolher imagem';
                                }
                                ?>
                            </span>

                            <span class="file-name"
                                data-role="file-input-display"
                                data-original-text="<?php
                                                    // Lógica para definir o texto original que o JS usará
                                                    if ($dados->post->id_post && $dados->post->imagem_post) {
                                                        echo htmlspecialchars($dados->post->imagem_post);
                                                    } elseif ($dados->post->id_post && $dados->post->imagem_post == null) {
                                                        echo 'Post sem imagem';
                                                    } else {
                                                        echo 'Nenhuma imagem selecionada';
                                                    }
                                                    ?>">
                                <?php
                                // Lógica para exibir o texto inicial (exatamente a mesma de cima)
                                if ($dados->post->id_post && $dados->post->imagem_post) {
                                    echo htmlspecialchars($dados->post->imagem_post);
                                } elseif ($dados->post->id_post && $dados->post->imagem_post == null) {
                                    echo 'Post sem imagem';
                                } else {
                                    echo 'Nenhuma imagem selecionada';
                                }
                                ?>
                            </span>
                        </label>

                    </div>

                    <div class="form-group">
                        <label for="titulo">Título</label>
                        <input type="text" name="titulo" id="titulo" class="form-control" value="<?= $dados->post ? $dados->post->titulo : "" ?>"><br>
                    </div>
                    <div class="form-group">
                        <label for="subtitulo">Subtítulo</label>
                        <input type="text" name="subtitulo" id="subtitulo" class="form-control" value="<?= $dados->post ? $dados->post->subtitulo : "" ?>"><br>
                    </div>

                    <div class="form-group">
                        <label>Texto do post</label>
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
                                <?= $dados->post ? $dados->post->texto : ""; ?>
                            </div>
                        </div>
                        <textarea name="texto" id="texto" class="form-control" style="display: none;"></textarea>
                    </div>


                    <div class="form-row">

                        <div class="form-group">
                            <label for="categoria_id">Categoria</label>
                            <select name="categoria_id" id="categoria_id" class="form-control">

                                <option disabled selected>Selecione a Categoria</option>

                                <?php foreach ($dados->categorias as $categoria): ?>
                                    <option value="<?= $categoria->id_categoria ?>"<?php if ($dados->post->categoria_id == $categoria->id_categoria): ?>selected<?php endif; ?>><?= $categoria->titulo ?></option>
                                <?php endforeach; ?>

                            </select>
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option disabled selected>Selecione o Status</option>
                                <option value="1" <?php if ($dados->post->status == '1'): ?> selected <?php endif; ?>>Ativado</option>
                                <option value="0" <?php if ($dados->post->status == '0'): ?> selected <?php endif; ?>>Desativado</option>
                            </select>
                        </div>

                    </div>

                    <input type="hidden" name="usuario_id" id="usuario_id" value="<?= $_SESSION['usuario_id'] ?>">

                    <div class="form-actions">
                        <div class="resposta ajax-response"></div>
                        <button type="submit" class="btn btn-primary ajax-link">Editar Post</button>
                    </div>

                </form>

            </div>
        </main>
    </div>
</div>