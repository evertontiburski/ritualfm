<?php
$totalUsuarios = $dados->usuarios_qtd[0]->{'COUNT(*)'} ?? 0;
$totalPosts = $dados->posts_qtd[0]->{'COUNT(*)'} ?? 0;
$totalComentarios = $dados->comentarios_qtd[0]->{'COUNT(*)'} ?? 0;
$totalCategorias = $dados->categorias_qtd[0]->{'COUNT(*)'} ?? 0;

// --- Dados para o Gráfico de Atividade Recente (Posts nos últimos 7 dias) ---
$postsPorDia = array_fill(0, 7, 0); // Cria um array com 7 dias, todos com valor 0
$diasDaSemana = [];
$hoje = new DateTime();
$hoje->setTime(23, 59, 59);

for ($i = 6; $i >= 0; $i--) {
    $diasDaSemana[] = date('d/m', strtotime("-$i days"));
}

foreach ($dados->posts as $post) {
    $dataPost = new DateTime($post->data_criacao);
    $diferenca = $hoje->diff($dataPost)->days;

    if ($diferenca >= 0 && $diferenca < 7) {
        $indice = 6 - $diferenca; // 0 = 6 dias atrás, 6 = hoje
        $postsPorDia[$indice]++;
    }
}
?>
<div class="admin-container">
    <div class="admin-layout">
        <aside class="admin-sidebar">
            <?php include '../app/Views/admin/menu.php'; ?>
        </aside>

        <main class="admin-content">
            <div class="admin-content-header">
                <div>
                    <h2>Painel Administrativo</h2>
                    <p class="subtitle">Visão geral do sistema, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>.</p>
                </div>
            </div>

            <div class="admin-status-grid">
                <div class="admin-card">
                    <h3>Usuários Cadastrados</h3>
                    <div class="admin-card-content">
                        <span class="admin-card-number"><?= $totalUsuarios ?></span>
                        <div class="admin-card-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M15 14s1 0 1-1-1-4-6-4-6 3-6 4 1 1 1 1h10zm-9.995-.944v-.002.002zM3.022 13h9.956a.274.274 0 0 0 .014-.002l.008-.002c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664a1.05 1.05 0 0 0 .022.004zM8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="admin-card">
                    <h3>Posts Publicados</h3>
                    <div class="admin-card-content">
                        <span class="admin-card-number"><?= $totalPosts ?></span>
                        <div class="admin-card-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M15.5 2H.5a.5.5 0 0 0 0 1h15a.5.5 0 0 0 0-1zM5 4.5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 4.5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0zM.5 14a.5.5 0 0 1 0-1h15a.5.5 0 0 1 0 1H.5z" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="admin-card">
                    <h3>Comentários Totais</h3>
                    <div class="admin-card-content">
                        <span class="admin-card-number"><?= $totalComentarios ?></span>
                        <div class="admin-card-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M2.678 11.894a1 1 0 0 1 .287.801 10.97 10.97 0 0 1-.398 2c1.395-.323 2.247-.697 2.634-.893a1 1 0 0 1 .71-.074A8.06 8.06 0 0 0 8 14c3.996 0 7-2.582 7-5.5S11.996 3 8 3 1 5.582 1 8.5c0 .642.172 1.254.464 1.794a1 1 0 0 1-.074.71z" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="admin-card">
                    <h3>Categorias</h3>
                    <div class="admin-card-content">
                        <span class="admin-card-number"><?= $totalCategorias ?></span>
                        <div class="admin-card-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M2 1a1 1 0 0 0-1 1v4.586a1 1 0 0 0 .293.707l7 7a1 1 0 0 0 1.414 0l4.586-4.586a1 1 0 0 0 0-1.414l-7-7A1 1 0 0 0 6.586 1H2zm4 3.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="dashboard-section-grid-graphics">
                <div class="admin-content-box chart-box">
                    <h3>Atividade Recente (Novos Posts)</h3>
                    <div id="recent-activity-chart"></div>
                </div>
            </div>

            <div class="dashboard-section-grid">
                <div class="admin-content-box">
                    <h3>Últimos Comentários</h3>
                    <ul class="activity-list">
                        <?php if (empty($dados->comentarios)): ?>
                            <li>Nenhum comentário recente.</li>
                        <?php else: ?>
                            <?php foreach (array_slice($dados->comentarios, 0, 4) as $comentario): ?>
                                <li>
                                    <strong><?= htmlspecialchars($comentario->nome) ?></strong> em "<?= htmlspecialchars($comentario->titulo) ?>":
                                    <span>"<?= htmlspecialchars(mb_strimwidth($comentario->comentario, 0, 50, "...")) ?>"</span>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="admin-content-box">
                    <h3>Últimos Usuários Cadastrados</h3>
                    <ul class="activity-list">
                        <?php if (empty($dados->usuarios)): ?>
                            <li>Nenhum usuário recente.</li>
                        <?php else: ?>
                            <?php foreach (array_slice($dados->usuarios, 0, 4) as $usuario): ?>
                                <li>
                                    <strong><?= htmlspecialchars($usuario->nome) ?></strong>
                                    <span>(<?= htmlspecialchars($usuario->email) ?>)</span>
                                    - em <?= Funcoes::dataFormatada($usuario->cadastrado_em) ?>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
    const dadosAtividade = <?= json_encode($postsPorDia); ?>;
    const labelsAtividade = <?= json_encode($diasDaSemana); ?>;
</script>