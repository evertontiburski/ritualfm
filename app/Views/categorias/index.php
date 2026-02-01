<div class="interface">
    <?php foreach ($dados as $categoria): ?>
        <div class="post-container">
            <h2><?= $categoria->titulo ?></h2>
            <p><?= $categoria->texto ?></p>
        </div>
    <?php endforeach; ?>
</div>