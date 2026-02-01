<div class="admin-container">
    <div class="admin-layout">
        <aside class="admin-sidebar">
            <?php include '../app/Views/admin/menu.php'; ?>
        </aside>

        <main class="admin-content">
            <div class="admin-content-header">
                <div>
                    <h2>Editar evento</h2>
                    <p class="subtitle">Lembre-se que o Título, e os demais campo suportam até 255 caracteres.</p>
                </div>
                <div class="header-actions">
                    <a href="<?= URL . '/admin/eventos' ?>" class="btn btn-create ajax-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon-action">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                        <span>Voltar</span>
                    </a>
                </div>
            </div>

            <div class="admin-content-box">

                <form class="admin-form" method="post" action="<?= URL ?>/admin/eventos/editar/<?= $dados->evento->id_evento ?>" enctype="multipart/form-data">

                    <div class="form-group" data-component="file-input">

                        <input type="file"
                            name="imagem_evento"
                            id="imagem_evento"
                            class="input-file-hidden"
                            accept=".png, .jpeg, .jpg"
                            data-role="file-input-target">

                        <label for="imagem_evento" class="custom-file-upload">
                            <span class="btn btn-primary file-button">
                                <?php
                                // Lógica do botão (não muda)
                                if ($dados->evento->id_evento) {
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
                                                    if ($dados->evento->id_evento && $dados->evento->imagem_evento) {
                                                        echo htmlspecialchars($dados->evento->imagem_evento);
                                                    } elseif ($dados->evento->id_evento && $dados->evento->imagem_evento == null) {
                                                        echo 'Evento sem imagem';
                                                    } else {
                                                        echo 'Nenhuma imagem selecionada';
                                                    }
                                                    ?>">
                                <?php
                                // Lógica para exibir o texto inicial (exatamente a mesma de cima)
                                if ($dados->evento->id_evento && $dados->evento->imagem_evento) {
                                    echo htmlspecialchars($dados->evento->imagem_evento);
                                } elseif ($dados->evento->id_evento && $dados->evento->imagem_evento == null) {
                                    echo 'Evento sem imagem';
                                } else {
                                    echo 'Nenhuma imagem selecionada';
                                }
                                ?>
                            </span>
                        </label>

                    </div>

                    <div class="form-group">
                        <label for="titulo">Título</label>
                        <input type="text" name="titulo" id="titulo" class="form-control" value="<?= $dados->evento ? $dados->evento->titulo : "" ?>"><br>
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
                                <?= $dados->evento ? $dados->evento->texto : "" ?>
                            </div>
                        </div>
                        <textarea name="texto" id="texto" class="form-control" style="display: none;"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="atracao">Atração</label>
                        <input type="text" name="atracao" id="atracao" class="form-control" value="<?= $dados->evento ? $dados->evento->atracao : "" ?>">
                    </div>

                    <div class="form-group">
                        <label for="data">Data</label>
                        <input type="date" name="data" id="data" class="form-control" value="<?= $dados->evento ? (new DateTime($dados->evento->data))->format('Y-m-d') : "" ?>">
                    </div>

                    <div class="form-group">
                        <label for="local">Local</label>
                        <input type="local" name="local" id="local" class="form-control" value="<?= $dados->evento ? $dados->evento->local : "" ?>">
                    </div>

                    <div class="form-group">
                        <label for="video_id_youtube">Video id youtube</label>
                        <input type="video_id_youtube" name="video_id_youtube" id="video_id_youtube" class="form-control" value="<?= $dados->evento ? $dados->evento->video_id_youtube : "" ?>">
                    </div>

                    <div class="form-row">

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option disabled selected>Selecione o Status</option>
                                <option value="1" <?php if ($dados->evento->status == '1'): ?> selected <?php endif; ?>>Ativado</option>
                                <option value="0" <?php if ($dados->evento->status == '0'): ?> selected <?php endif; ?>>Desativado</option>
                            </select>
                        </div>

                    </div>

                    <input type="hidden" name="usuario_id" id="usuario_id" value="<?= $_SESSION['usuario_id'] ?>">

                    <div class="form-actions">
                        <div class="resposta ajax-response"></div>
                        <button type="submit" class="btn btn-primary ajax-link">Editar Evento</button>
                    </div>

                </form>

            </div>
        </main>
    </div>
</div>