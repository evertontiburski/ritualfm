<div id="container-nav" class="fadeIn">
    <div class="contact-container">
        <div class="contact-info">
            <h1>Contato</h1>
            <p>RITUAL FM<br>São Paulo - Brasil <br>
                <a class="link-underline" href="mailto:info@ritualfm.com">contato@ritualfm.com</a>
            </p>
        </div>

        <div class="contact-data">
            <div class="contact-line"></div>
            <div class="contact-line">
                <span>Anuncios e patrocinios</span>
                <a class="transition" href="mailto:publicidade@ritualfm.com">publicidade@ritualfm.com</a>
            </div>
            <div class="contact-line">
                <span>Comunicados de imprensa</span>
                <div>
                    <a class="transition" href="mailto:info@ritualfm.com">info@ritualfm.com</a>
                </div>
            </div>
            <div class="contact-line">
                <span>Oportunidades</span>
                <a class="transition" href="mailto:rh@ritualfm.com">rh@ritualfm.com</a>
            </div>
            <div class="contact-line">
                <span>Editor-chefe</span>
                <a class="transition" href="mailto:robinson.m@ritualfm.com">robinson.m@ritualfm.com</a>
            </div>
        </div>
        <div class="write-text">
            <h2>Escreva<br>para nós</h2>
        </div>

        <div class="contact-write">
            <div class="form-wrapper">
            <form class="comment" method="post" action="<?= URL ?>/contato/enviar">
                
                <p class="form-subtitle">Preencha os campos abaixo para nos enviar uma mensagem.</p>

                <div class="form-group">
                    <input type="text" name="nome" id="nome" class="form-input" placeholder=" " required>
                    <label for="nome" class="form-label">Nome</label>
                </div>

                <div class="form-group">
                    <input type="email" name="email" id="email" class="form-input" placeholder=" " required>
                    <label for="email" class="form-label">E-mail</label>
                </div>

                <div class="form-group">
                    <textarea name="mensagem" id="mensagem" class="form-input" placeholder=" " required></textarea>
                    <label for="mensagem" class="form-label">Mensagem</label>
                </div>

                <input class="form-button" type="submit" value="Enviar Mensagem">

                <div class="resposta"></div>

            </form>
            </div>
        </div>
    </div>
</div>