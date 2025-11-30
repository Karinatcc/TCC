/* ==========================
      SELETORES PRINCIPAIS
   ========================== */

const sendBtn = document.getElementById("sendBtn");
const msgInput = document.getElementById("msgInput");
const messages = document.getElementById("messages");

/* ==========================
      FUNÇÃO UNIVERSAL PARA ADICIONAR MSG
      (PRONTA PARA BACKEND FUTURO)
   ========================== */

function addMessage(text, type) {
    const div = document.createElement("div");
    div.classList.add("msg", type);
    div.textContent = text;

    messages.appendChild(div);

    // Scroll automático sempre para o final
    messages.scrollTop = messages.scrollHeight;
}

/* ==========================
      ENVIO DA MENSAGEM
   ========================== */

function sendMessage() {
    const text = msgInput.value.trim();
    if (text === "") return;

    addMessage(text, "sent"); // adiciona a mensagem com animação

    msgInput.value = "";

    // Futuro backend:
    // socket.emit("cliente_envia", text);
}

/* Eventos: botão e Enter */
sendBtn.addEventListener("click", sendMessage);

msgInput.addEventListener("keypress", (e) => {
    if (e.key === "Enter") sendMessage();
});

/* ==========================
      SISTEMA DE ABAS LATERAIS
   ========================== */

const tabs = document.querySelectorAll(".tab");
const statusText = document.getElementById("statusText");

const mensagens = {
    atendidos: "Nenhum cliente disponível.",
    aguardando: "Nenhum cliente aguardando no momento.",
    resolvido: "Nenhum atendimento resolvido ainda.",
};

tabs.forEach((tab) => {
    tab.addEventListener("click", () => {
        tabs.forEach((t) => t.classList.remove("active"));
        tab.classList.add("active");

        const tipo = tab.dataset.status;
        statusText.textContent = mensagens[tipo];
    });
});

   /* ==========================
      DIGITANDO...
   ========================== */

const typingBox = document.getElementById("typingIndicator");

function showTyping() {
    typingBox.style.display = "flex";
    messages.scrollTop = messages.scrollHeight;
}

function hideTyping() {
    typingBox.style.display = "none";
}





