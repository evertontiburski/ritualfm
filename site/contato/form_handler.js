// Verifica se o listener já foi adicionado para evitar duplicação
if (!document.getElementById("contatoForm").hasAttribute("data-listener-added")) {
    document.getElementById("contatoForm").addEventListener("submit", function(event) {
        event.preventDefault(); // Impede o envio padrão do formulário
        event.stopPropagation(); // Impede que o evento se propague para listeners pais (importante!)

        const form = event.target;
        const formData = new FormData(form);
        const data = {};
        formData.forEach((value, key) => {
            data[key] = value.replace(/</g, "&lt;").replace(/>/g, "&gt;");
        });

        const btnSend = document.getElementById("btnsend");
        const originalButtonText = btnSend.textContent;
        btnSend.disabled = true;
        btnSend.textContent = "Enviando...";

        fetch("enviar.php", { // Endpoint PHP
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(data),
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => { throw new Error(text || `Erro HTTP: ${response.status}`) });
            }
            return response.json();
        })
        .then(result => {
            console.log("Resposta do PHP:", result);
            if (result.success) {
                btnSend.textContent = "Enviado";
                form.reset();
            } else {
                throw new Error(result.message || "Falha no envio informada pelo servidor.");
            }
            setTimeout(() => {
                btnSend.textContent = originalButtonText;
                btnSend.disabled = false;
            }, 3000);
        })
        .catch(error => {
            console.error("Erro:", error);
            btnSend.textContent = "Não Enviado";
            setTimeout(() => {
                btnSend.textContent = originalButtonText;
                btnSend.disabled = false;
            }, 3000);
        });
    });

    // Marca o formulário para indicar que o listener foi adicionado
    document.getElementById("contatoForm").setAttribute("data-listener-added", "true");
}

