/**
 * Script principal do site
 * Versão modernizada com ES6+ e práticas atuais
 */

// Função principal com escopo isolado
(() => {
  // Elementos DOM frequentemente acessados
  const elements = {
    content: document.getElementById('main-nav'),
    menu: document.getElementById('main-menu'),
    miniPlayer: document.getElementById('mini_player'),
    coverSite: document.getElementById('cover-site')
  };

  // Título padrão para a página inicial
  const DEFAULT_HOME_TITLE = "RITUAL FM | INÍCIO";

  /**
   * Detecta a URL atual e retorna a seção correspondente
   * @param {string} path - Caminho a ser verificado (opcional, usa window.location.pathname se não fornecido)
   * @returns {Object} Objeto com informações da seção atual
   */
  const detectCurrentSection = (path) => {
    // Se path não for fornecido, usa o pathname atual
    const currentPath = path || window.location.pathname;
    // Remove barras finais e normaliza o caminho
    const normalizedPath = currentPath.replace(/\/+$/, '').replace(/https?:\/\/[^\/]+/, '');
    
    // Mapeamento de URLs para seções e títulos
    const sections = {
      '/': { id: null, title: 'Home', displayTitle: DEFAULT_HOME_TITLE },
      '/index2.php': { id: null, title: 'INÍCIO', displayTitle: DEFAULT_HOME_TITLE },
      '/noticias': { id: 'news', title: 'NOTÍCIAS', displayTitle: 'RITUAL FM | NOTÍCIAS' },
      '/eventos': { id: 'events', title: 'EVENTOS', displayTitle: 'RITUAL FM | EVENTOS' },
      '/musica': { id: 'music', title: 'MÚSICA', displayTitle: 'RITUAL FM | MÚSICA' }
      // Adicione outras páginas conforme necessário
    };
    
    // Retorna a seção correspondente ou null se não for uma seção específica
    return sections[normalizedPath] || null;
  };

  /**
   * Verifica se o caminho é da página inicial
   * @param {string} path - Caminho a verificar
   * @returns {boolean} Verdadeiro se for a página inicial
   */
  const isHomePage = (path) => {
    const normalizedPath = path.replace(/https?:\/\/[^\/]+/, '').replace(/\/+$/, '');
    return normalizedPath === '/' || normalizedPath === '/index2.php' || normalizedPath === '';
  };

  /**
   * Exibe o loader SVG animado
   */
  const showLoader = () => {
    if (!elements.content) return;
    
    elements.content.innerHTML = `
      <div id="load">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
          <circle r="15" cx="40" cy="100" style="fill:#FF0000; stroke:#FF0000; stroke-width:15;">
            <animate attributeName="opacity" calcMode="spline" dur="0.9" values="1;0;1;" 
                     keySplines=".5 0 .5 1;.5 0 .5 1" repeatCount="indefinite" begin="-.4"/>
          </circle>
          <circle r="15" cx="100" cy="100" style="fill:#FF0000; stroke:#FF0000; stroke-width:15;">
            <animate attributeName="opacity" calcMode="spline" dur="0.9" values="1;0;1;" 
                     keySplines=".5 0 .5 1;.5 0 .5 1" repeatCount="indefinite" begin="-.2"/>
          </circle>
          <circle r="15" cx="160" cy="100" style="fill:#FF0000; stroke:#FF0000; stroke-width:15;">
            <animate attributeName="opacity" calcMode="spline" dur="0.9" values="1;0;1;" 
                     keySplines=".5 0 .5 1;.5 0 .5 1" repeatCount="indefinite" begin="0"/>
          </circle>
        </svg>
      </div>
    `;
  };

  /**
   * Extrai o título da página do HTML
   * @param {Document} doc - Documento HTML para extrair o título
   * @param {string} path - Caminho da URL para verificar seções específicas
   * @returns {string} Título extraído ou título padrão
   */
  const extractPageTitle = (doc, path) => {
    // Verifica se é a página inicial
    if (isHomePage(path)) {
      return DEFAULT_HOME_TITLE;
    }
    
    // Verifica se é uma seção específica
    const currentSection = detectCurrentSection(path);
    if (currentSection) {
      return currentSection.displayTitle;
    }
    
    // Se não for uma seção específica, tenta extrair do documento
    const titleElement = doc.querySelector('title');
    if (titleElement && titleElement.textContent) {
      return titleElement.textContent;
    }
    
    // Fallback para título padrão
    return DEFAULT_HOME_TITLE;
  };

  /**
   * Atualiza o título da página com base na seção atual
   * @param {string} path - Caminho a ser verificado (opcional)
   * @param {string} forcedTitle - Título forçado a ser usado (opcional)
   */
  const updatePageTitle = (path, forcedTitle) => {
    if (forcedTitle) {
      document.title = forcedTitle;
      return;
    }
    
    // Verifica se é a página inicial
    if (isHomePage(path)) {
      document.title = DEFAULT_HOME_TITLE;
      return;
    }
    
    const currentSection = detectCurrentSection(path);
    if (currentSection) {
      document.title = currentSection.displayTitle;
    }
  };

  /**
   * Rola a página até a seção especificada
   * @param {string} sectionId - ID da seção para rolar
   */
  const scrollToSection = (sectionId) => {
    if (!sectionId) return;
    
    const section = document.getElementById(sectionId);
    if (section) {
      // Pequeno atraso para garantir que a página esteja totalmente carregada
      setTimeout(() => {
        section.scrollIntoView({ behavior: 'auto', block: 'start' });
      }, 0);
    }
  };

  /**
   * Funções de navegação e controle de painéis
   */
  const navControls = {
    // Função genérica para alternar propriedades de elementos
    toggleElement: (id, styleProp, value) => {
      const el = document.getElementById(id);
      if (el) el.style[styleProp] = value;
    },
    
    // Menu lateral
    openNav: () => navControls.toggleElement("main-menu", "width", "35%"),
    closeNav: () => navControls.toggleElement("main-menu", "width", "0px"),
    
    // Navegação principal
    openNav2: () => navControls.toggleElement("main-nav", "height", "100%"),
    closeNav2: () => navControls.toggleElement("main-nav", "height", "0px"),
    
    // Mini player
    openNav3: () => navControls.toggleElement("mini_player", "height", "60px"),
    closeNav3: () => navControls.toggleElement("mini_player", "height", "0px"),
    
    // Painel de pedidos musicais
    openNav4: () => navControls.toggleElement("requests", "display", "flex"),
    closeNav4: () => navControls.toggleElement("requests", "display", "none")
  };

  /**
   * Carrega uma nova página via Fetch API (substituindo AJAX)
   * @param {string} href - URL da página a ser carregada
   * @param {boolean} pushState - Se deve adicionar ao histórico de navegação
   * @param {string} forcedTitle - Título forçado a ser usado (opcional)
   */
  const loadPage = async (href, pushState = true, forcedTitle = null) => {
    try {
      navControls.closeNav();
      showLoader();
      navControls.openNav2(); // Abre sempre as janelas para manter consistência
      navControls.openNav3();

      // Normaliza o href para uso em detecção de seção
      const normalizedHref = href.replace(/https?:\/\/[^\/]+/, '').replace(/\/+$/, '');
      
      const response = await fetch(href);
      if (!response.ok) throw new Error(`Erro ao carregar ${href}`);
      
      const html = await response.text();
      const parser = new DOMParser();
      const doc = parser.parseFromString(html, 'text/html');
      
      const data = doc.getElementById('main-nav')?.innerHTML || '';
      
      // Determina o título da página
      let pageTitle = forcedTitle;
      if (!pageTitle) {
        // Verifica se é a página inicial
        if (isHomePage(normalizedHref)) {
          pageTitle = DEFAULT_HOME_TITLE;
        } else {
          pageTitle = extractPageTitle(doc, normalizedHref);
        }
      }
      
      // Atualiza o título imediatamente
      document.title = pageTitle;
      
      // Detecta a seção com base no href
      const currentSection = detectCurrentSection(normalizedHref);
      
      // Pequeno delay para o loader ser visível
      setTimeout(() => {
        if (!elements.content) return;
        
        // Fade in com animação CSS em vez de jQuery
        elements.content.classList.add('fade-in');
        elements.content.innerHTML = data;

        if (pushState) {
          // Armazena informações da seção no estado do histórico
          window.history.pushState({ 
            href, 
            sectionId: currentSection?.id,
            title: pageTitle,
            isHome: isHomePage(normalizedHref)
          }, pageTitle, href);
        }

        // Fecha janelas se for home
        if (isHomePage(href)) {
          navControls.closeNav();
          navControls.closeNav2();
          navControls.closeNav3();
        }

        // Analytics
        if (typeof gtag === 'function') {
          gtag('config', 'G-V90Y3V7G5F', {
            page_path: href,
            page_title: pageTitle
          });
        }

        // Reaplica eventos aos novos elementos
        bindAjaxNavigation();
        
        // Rola para a seção correspondente se existir
        if (currentSection && currentSection.id) {
          scrollToSection(currentSection.id);
        }
        
        // Remove classe de animação após completar
        setTimeout(() => {
          elements.content?.classList.remove('fade-in');
        }, 500);
      }, 1000);
    } catch (error) {
      console.error('Erro ao carregar página:', error);
      // Tratamento de erro visual para o usuário
      if (elements.content) {
        elements.content.innerHTML = `
          <div class="error-message">
            <h3>Erro ao carregar a página</h3>
            <p>${error.message}</p>
            <button onclick="window.location.reload()">Tentar novamente</button>
          </div>
        `;
      }
    }
  };

  /**
   * Associa eventos de clique aos links para navegação AJAX
   */
  const bindAjaxNavigation = () => {
    document.querySelectorAll('.mContent a').forEach(link => {
      // Remove eventos anteriores para evitar duplicação
      link.removeEventListener('click', handleLinkClick);
      // Adiciona novo evento
      link.addEventListener('click', handleLinkClick);
    });
  };

  /**
   * Manipulador de eventos para cliques em links
   * @param {Event} e - Evento de clique
   */
  const handleLinkClick = (e) => {
    e.preventDefault();
    const href = e.currentTarget.getAttribute('href');

    const currentPath = window.location.pathname.replace(/\/+$/, '');
    const targetPath = href.replace(/\/+$/, '');

    // Impede recarregamento/reação se já estiver na home
    const isHome = path => path === '' || path === '/' || path === '/index2.php';

    if (isHome(currentPath) && isHome(targetPath)) {
      return; // já estamos na home, não faz nada
    }

    if (href) loadPage(href);
  };

  /**
   * Atualiza conteúdo dinâmico via Fetch API
   * @param {string} selector - Seletor CSS do elemento a atualizar
   * @param {string} url - URL do conteúdo a ser carregado
   */
  const fetchAndUpdate = async (selector, url) => {
    try {
      const response = await fetch(url);
      if (!response.ok) throw new Error(`Erro ao carregar ${url}`);
      
      const html = await response.text();
      const element = document.querySelector(selector);
      
      if (element) {
        element.innerHTML = html;
      }
    } catch (error) {
      console.error(`Erro ao atualizar ${selector}:`, error);
    }
  };

  /**
   * Inicia o loop de atualização periódica de conteúdo
   */
  const startRefreshLoop = () => {
    // Atualização inicial
    fetchAndUpdate("#request", "/pedidos/pedidos.php");

    // Configurar intervalo para atualizações periódicas
    setInterval(() => {
      fetchAndUpdate("#request", "/pedidos/pedidos.php");
    }, 3600000); // 1 hora
  };

  /**
   * Gerencia a notificação de cookies
   */
  const setupCookieNotification = () => {
    const cookieMessage = document.getElementById("cookie-notification");
    const acceptBtn = document.getElementById("cookie-notification-close");
    const denyBtn = document.getElementById("cookie-notification-close2");

    if (!cookieMessage) return;

    const hasCookieDecision = localStorage.getItem("cookiesAccepted");

    if (hasCookieDecision === null) {
      cookieMessage.style.display = "block";
    }

    if (acceptBtn) {
      acceptBtn.addEventListener("click", (e) => {
        e.preventDefault();
        localStorage.setItem("cookiesAccepted", "true");
        cookieMessage.style.display = "none";
      });
    }

    if (denyBtn) {
      denyBtn.addEventListener("click", (e) => {
        e.preventDefault();
        localStorage.setItem("cookiesAccepted", "false");
        cookieMessage.style.display = "none";
      });
    }
  };

  /**
   * Configura o comportamento do loader inicial
   */
  const setupInitialLoader = () => {
    const loader = document.getElementById('loader');
    const cover = elements.coverSite;
    
    if (!loader || !cover) return;
    
    let timeout;

    const hideLoader = () => {
      loader.classList.add('fade-out');
      setTimeout(() => loader.remove(), 500);
    };

    const waitForCoverAnimation = () => {
      if (cover.classList.contains('clipIn')) {
        hideLoader();
      } else {
        const handleAnimEnd = (e) => {
          if (e.animationName.includes('clipIn')) {
            hideLoader();
            cover.removeEventListener('animationend', handleAnimEnd);
          }
        };
        
        cover.addEventListener('animationend', handleAnimEnd);
      }

      // Fallback de segurança
      timeout = setTimeout(hideLoader, 6000);
    };

    // Verificar se o background já foi carregado
    const waitForBg = setInterval(() => {
      const bg = cover.style.backgroundImage;

      if (bg && bg.includes('url(')) {
        clearInterval(waitForBg);
        waitForCoverAnimation();
      }
    }, 100);
  };

  /**
   * Configura o comportamento de scroll personalizado
   */
  const setupCustomScroll = () => {
    // Manipulador de eventos para a roda do mouse
    document.addEventListener('wheel', (e) => {
      const news = document.getElementById('latest_news');
      if (!news) return;

      // Evita interferência em outras áreas scrolláveis
      const scrollables = ['main-nav']; // IDs ou classes que podem rolar normalmente
      
      for (const cls of scrollables) {
        const el = document.querySelector(`.${cls}, #${cls}`);
        if (el && el.matches(':hover')) return; // Se o mouse estiver sobre outra área rolável, sai
      }

      // Verifica se é possível rolar o conteúdo da div
      const atTop = news.scrollTop === 0;
      const atBottom = news.scrollTop + news.clientHeight >= news.scrollHeight - 1;

      const delta = e.deltaY; // Valor original, sem suavização

      if ((delta < 0 && !atTop) || (delta > 0 && !atBottom)) {
        e.preventDefault(); // Impede scroll da página
        news.scrollTop += delta; // Scroll controlado, mas com comportamento natural
      }
    }, { passive: false });

    // Resetar scroll ao navegar
    document.querySelectorAll('.transition, .menu a').forEach(el => {
      el.addEventListener('click', () => {
        setTimeout(() => {
          const news = document.getElementById('latest_news');
          if (news) {
            // Usando scrollTo com comportamento suave
            news.scrollTo({
              top: 0,
              behavior: 'smooth'
            });
          }
        }, 2000);
      });
    });
  };

  /**
   * Inicialização quando o DOM estiver pronto
   */
  const init = () => {
    // Configurar manipuladores de eventos para navegação do histórico
    window.addEventListener('popstate', (event) => {
      if (event.state) {
        showLoader();
        navControls.openNav2();
        navControls.openNav3();
        
        // Verifica se estamos voltando para a página inicial
        if (event.state.isHome) {
          document.title = DEFAULT_HOME_TITLE;
          
          if (event.state.href) {
            // Usa false para não empilhar novamente no histórico
            loadPage(event.state.href, false, DEFAULT_HOME_TITLE);
          }
          
          // Fecha os painéis para a página inicial
          setTimeout(() => {
            navControls.closeNav();
            navControls.closeNav2();
            navControls.closeNav3();
          }, 1000);
          
          return;
        }
        
        // Carrega a página do histórico com o título salvo
        if (event.state.href) {
          // Usa false para não empilhar novamente no histórico
          // Passa o título salvo para garantir consistência
          loadPage(event.state.href, false, event.state.title);
        }
      }
    });

    // Fechar menu ao clicar fora
    window.addEventListener('mouseup', (event) => {
      const menu = elements.menu;
      if (menu && event.target !== menu && !menu.contains(event.target)) {
        menu.style.width = "0px";
      }
    });

    // Estado inicial ao entrar direto em página interna
    if (history.state === null) {
      const href = window.location.pathname;
      
      // Determina o título inicial
      let initialTitle;
      const isHome = isHomePage(href);
      
      if (isHome) {
        initialTitle = DEFAULT_HOME_TITLE;
      } else {
        const currentSection = detectCurrentSection(href);
        initialTitle = currentSection ? currentSection.displayTitle : document.title;
      }
      
      document.title = initialTitle;
      
      // Salva o estado inicial com informações da seção
      history.replaceState({ 
        href, 
        sectionId: isHome ? null : detectCurrentSection(href)?.id,
        title: initialTitle,
        isHome: isHome
      }, initialTitle, href);
    }

    const path = window.location.pathname;

    // Se não está na home, carrega conteúdo da URL atual
    if (!isHomePage(path)) {
      navControls.openNav2();
      navControls.openNav3();

      // Carregar conteúdo inicial
      fetch(path)
        .then(response => response.text())
        .then(html => {
          const parser = new DOMParser();
          const doc = parser.parseFromString(html, 'text/html');
          
          const data = doc.getElementById('main-nav')?.innerHTML || '';
          
          const mainNav = document.getElementById('main-nav');
          if (mainNav) {
            mainNav.innerHTML = data;
            
            // Atualiza o título com base na URL atual
            const pageTitle = extractPageTitle(doc, path);
            document.title = pageTitle;
            
            bindAjaxNavigation();
            
            // Verifica se precisa rolar para uma seção específica
            const currentSection = detectCurrentSection(path);
            if (currentSection && currentSection.id) {
              scrollToSection(currentSection.id);
            }
          }
        })
        .catch(error => console.error('Erro ao carregar página inicial:', error));
    } else {
      // Se estamos na home, garantimos que o título esteja correto
      document.title = DEFAULT_HOME_TITLE;
    }

    // Inicializa eventos
    bindAjaxNavigation();
    setupCookieNotification();
    setupCustomScroll();
    startRefreshLoop();
    
    // Verifica se precisa atualizar o título e rolar para uma seção específica
    if (!isHomePage(path)) {
      const currentSection = detectCurrentSection();
      if (currentSection) {
        document.title = currentSection.displayTitle;
        if (currentSection.id) {
          scrollToSection(currentSection.id);
        }
      }
    }
  };

  // Expor funções necessárias globalmente
  window.openNav = navControls.openNav;
  window.closeNav = navControls.closeNav;
  window.openNav2 = navControls.openNav2;
  window.closeNav2 = navControls.closeNav2;
  window.openNav3 = navControls.openNav3;
  window.closeNav3 = navControls.closeNav3;
  window.openNav4 = navControls.openNav4;
  window.closeNav4 = navControls.closeNav4;

  // Inicializar quando o DOM estiver pronto
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
    window.addEventListener('load', setupInitialLoader);
  } else {
    init();
    setupInitialLoader();
  }
})();
