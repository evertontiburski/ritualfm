<div id="container-nav" class="fadeIn">

    <div class="form-wrapper">

        <form class="user-form" method="post" action="<?= URL ?>/usuarios/recuperacao">

            <h2>Esqueceu a senha?</h2>
            <p class="form-subtitle">Insira seu e-mail para receber um link de redefinição de senha.</p>

            <div class="form-group">
                <input type="email" name="email" id="email" class="form-input" placeholder=" " required>
                <label for="email" class="form-label">E-mail</label>
            </div>

            <input class="form-button" type="submit" value="Enviar Link">

            <div class="resposta"></div>

        </form>

        <div class="form-links">
            <a href="<?= URL . '/usuarios/login' ?>" class="ajax-link">Já tem uma conta? <strong>Faça o login</strong></a>
            <span>•</span>
            <a href="<?= URL . '/usuarios/cadastrar' ?>" class="ajax-link">Não tem uma conta? <strong>Cadastre-se</strong></a>
        </div>

    </div>

</div>