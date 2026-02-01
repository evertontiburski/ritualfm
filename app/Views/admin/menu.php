<nav class="admin-menu-nav">
    <h3 class="admin-menu-title">Admin</h3>
    <a href="<?= URL . '/admin' ?>" class="admin-menu-item ajax-link">
        <svg xmlns="http://www.w3.org/2000/svg" class="admin-menu-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
            <polyline points="9 22 9 12 15 12 15 22"></polyline>
        </svg>
        <span>Início</span>
    </a>
    <a href="<?= URL . '/admin/posts' ?>" class="admin-menu-item ajax-link">
        <svg xmlns="http://www.w3.org/2000/svg" class="admin-menu-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
            <polyline points="14 2 14 8 20 8"></polyline>
            <line x1="16" y1="13" x2="8" y2="13"></line>
            <line x1="16" y1="17" x2="8" y2="17"></line>
            <polyline points="10 9 9 9 8 9"></polyline>
        </svg>
        <span>Posts</span>
    </a>
    <a href="<?= URL . '/admin/categorias' ?>" class="admin-menu-item ajax-link">
        <svg xmlns="http://www.w3.org/2000/svg" class="admin-menu-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
            <line x1="7" y1="7" x2="7.01" y2="7"></line>
        </svg>
        <span>Categorias</span>
    </a>
    <a href="<?= URL . '/admin/publicidades' ?>" class="admin-menu-item ajax-link">
        <svg xmlns="http://www.w3.org/2000/svg" class="admin-menu-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
        </svg>
        <span>Publicidades</span>
    </a>
    <a href="<?= URL . '/admin/usuarios' ?>" class="admin-menu-item ajax-link">
        <svg xmlns="http://www.w3.org/2000/svg" class="admin-menu-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
            <circle cx="9" cy="7" r="4" class="color-circle-icon-border-admin"></circle>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
        </svg>
        <span>Usuários</span>
    </a>
    <a href="<?= URL . '/admin/comentarios' ?>" class="admin-menu-item ajax-link">
        <svg xmlns="http://www.w3.org/2000/svg" class="admin-menu-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
        </svg>
        <span>Comentários</span>
    </a>
    <a href="<?= URL . '/admin/contatos' ?>" class="admin-menu-item ajax-link">
        <svg xmlns="http://www.w3.org/2000/svg" class="admin-menu-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
            <polyline points="22,6 12,13 2,6"></polyline>
        </svg>
        <span>Contatos</span>
    </a>
    <a href="<?= URL . '/admin/anuncie' ?>" class="admin-menu-item ajax-link">
        <svg xmlns="http://www.w3.org/2000/svg" class="admin-menu-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="1" x2="12" y2="23"></line>
            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
        </svg>
        <span>Anúncie</span>
    </a>
    <a href="<?= URL . '/admin/musicas' ?>" class="admin-menu-item ajax-link">
        <svg xmlns="http://www.w3.org/2000/svg" class="admin-menu-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 18V5l12-2v13"></path>
            <circle cx="6" cy="18" r="3" class="color-circle-icon-admin"></circle>
            <circle cx="18" cy="16" r="3" class="color-circle-icon-admin"></circle>
        </svg>
        <span>Conteúdo de músicas</span>
    </a>
    <a href="<?= URL . '/admin/eventos' ?>" class="admin-menu-item ajax-link">
        <svg xmlns="http://www.w3.org/2000/svg" class="admin-menu-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
            <line x1="16" y1="2" x2="16" y2="6"></line>
            <line x1="8" y1="2" x2="8" y2="6"></line>
            <line x1="3" y1="10" x2="21" y2="10"></line>
        </svg>
        <span>Eventos</span>
    </a>
    <a href="<?= URL . '/admin/termos' ?>" class="admin-menu-item ajax-link">
        <svg xmlns="http://www.w3.org/2000/svg" class="admin-menu-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
            <polyline points="13 2 13 9 20 9"></polyline>
        </svg>
        <span>Termos</span>
    </a>
    <a href="<?= URL . '/admin/sobre' ?>" class="admin-menu-item ajax-link">
        <svg xmlns="http://www.w3.org/2000/svg" class="admin-menu-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10" class="color-circle-icon-border-admin"></circle>
            <line x1="12" y1="16" x2="12" y2="12"></line>
            <line x1="12" y1="8" x2="12.01" y2="8"></line>
        </svg>
        <span>Sobre</span>
    </a>
    <a href="<?= URL . '/admin/sociais' ?>" class="admin-menu-item ajax-link">
        <svg xmlns="http://www.w3.org/2000/svg" class="admin-menu-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="18" cy="5" r="3" class="color-circle-icon-border-admin"></circle>
            <circle cx="6" cy="12" r="3" class="color-circle-icon-border-admin"></circle>
            <circle cx="18" cy="19" r="3" class="color-circle-icon-border-admin"></circle>
            <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
            <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
        </svg>
        <span>Sociais</span>
    </a>
    <a href="<?= URL . '/admin/notificacoes' ?>" class="admin-menu-item ajax-link">
        <svg xmlns="http://www.w3.org/2000/svg" class="admin-menu-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="22" y1="2" x2="11" y2="13"></line>
            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
        </svg>
        <span>Notificações interna</span>
    </a>
    <a href="<?= URL . '/dashboard' ?>" class="admin-menu-item active ajax-link">
        <svg xmlns="http://www.w3.org/2000/svg" class="admin-menu-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
            <polyline points="16 17 21 12 16 7"></polyline>
            <line x1="21" y1="12" x2="9" y2="12"></line>
        </svg>
        <span>Sair</span>
    </a>
</nav>