/* SELETORES PRINCIPAIS */
const sendBtn = document.getElementById("sendBtn");
const msgInput = document.getElementById("msgInput");
const messages = document.getElementById("messages");

/* ENVIO DA MENSAGEM */
function sendMessage() {
    const text = msgInput.value.trim();
    if (text === "") return;

    // Adiciona visualmente primeiro
    const div = document.createElement("div");
    div.classList.add("msg", "sent");
    div.textContent = text;
    messages.appendChild(div);
    
    msgInput.value = "";
    messages.scrollTop = messages.scrollHeight;

    // Envia para o backend
    fetch('salvar-mensagem.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'mensagem=' + encodeURIComponent(text)
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success) {
            console.error('Erro ao salvar mensagem:', data.error);
        }
    })
    .catch(error => console.error('Erro de rede:', error));
}

/* Eventos: botão e Enter */
sendBtn.addEventListener("click", sendMessage);
msgInput.addEventListener("keypress", (e) => {
    if (e.key === "Enter") sendMessage();
});

/* SISTEMA DE ABAS LATERAIS */
const tabs = document.querySelectorAll(".tab");
const statusText = document.getElementById("statusText");

const mensagens = {
    atendidos: "Nenhum cliente disponível.",
    aguardando: "Nenhum cliente aguardando no momento."
};

tabs.forEach((tab) => {
    tab.addEventListener("click", () => {
        tabs.forEach((t) => t.classList.remove("active"));
        tab.classList.add("active");

        const tipo = tab.dataset.status;
        statusText.textContent = mensagens[tipo];
    });
});

/* CARREGAR MENSAGENS DO BANCO */
function carregarMensagens() {
    fetch('carregar-mensagens-empresa.php')
        .then(r => r.json())
        .then(data => {
            if (data.success && messages) {
                messages.innerHTML = '';
                
                data.mensagens.forEach(msg => {
                    const div = document.createElement("div");
                    const isSent = msg.enviado_por !== null;
                    
                    div.classList.add("msg", isSent ? "sent" : "received");
                    div.innerHTML = `
                        ${msg.conteudo}
                        <div class="msg-info">
                            ${msg.nome_exibicao} • ${formatarData(msg.criado_em)}
                        </div>
                    `;
                    messages.appendChild(div);
                });
                
                messages.scrollTop = messages.scrollHeight;
            }
        })
        .catch(error => console.error('Erro ao carregar mensagens:', error));
}

function formatarData(dataString) {
    const data = new Date(dataString);
    return data.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
}

// Carregar mensagens ao iniciar e atualizar a cada 1 segundo
if (messages) {
    carregarMensagens();
    setInterval(carregarMensagens, 1000);
}