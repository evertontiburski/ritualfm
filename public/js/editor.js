function inicializarEditorDeTexto() {
    
    const executeCommand = (comando, valor = null) => {
        document.execCommand(comando, false, valor);
    };

    const botoesComando = document.querySelectorAll('.comando-editor');
    botoesComando.forEach(button => {
        // Altera o tipo do botão para 'button' para não submeter o formulário
        button.type = 'button';
        button.addEventListener('click', () => {
            const comando = button.dataset.comando;
            executeCommand(comando);
        });
    });

    const seletorFormato = document.querySelector('.comando-formato');
    if (seletorFormato) {
        seletorFormato.addEventListener('change', () => {
            executeCommand('formatBlock', seletorFormato.value);
        });
    }

    const seletorFonte = document.querySelector('.comando-fonte');
    if (seletorFonte) {
        seletorFonte.addEventListener('change', () => {
            executeCommand('fontName', seletorFonte.value);
        });
    }

    const seletorEspacamento = document.getElementById('comando-espacamento');
    if (seletorEspacamento) {
        seletorEspacamento.addEventListener('change', () => {
            const lineHeight = seletorEspacamento.value;
            const selection = window.getSelection();
            if (selection.rangeCount > 0) {
                let node = selection.getRangeAt(0).startContainer;
                if (node.nodeType === 3) node = node.parentNode;

                while (node && node.id !== 'editor' && !['P', 'H1', 'H2', 'H3', 'LI', 'DIV'].includes(node.tagName)) {
                    node = node.parentNode;
                }

                if (node && node.id !== 'editor') {
                    node.style.lineHeight = lineHeight;
                }
            }
        });
    }

    // --- LÓGICA DE SUBMISSÃO ADAPTADA PARA O SEU FORMULÁRIO ---
    const form = document.querySelector('.admin-form');
    const hiddenTextarea = document.getElementById('texto');
    const editorDiv = document.getElementById('editor');

    if (form && hiddenTextarea && editorDiv) {
        form.addEventListener('submit', () => {
            // Antes de o formulário ser enviado, copia o conteúdo HTML do editor
            // para dentro do <textarea> escondido.
            hiddenTextarea.value = editorDiv.innerHTML;
        });
    } else {
        console.error('Elementos do editor ou formulário não encontrados!');
    }
}

document.addEventListener('DOMContentLoaded', inicializarEditorDeTexto);