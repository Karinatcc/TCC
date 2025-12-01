const clientSend = document.getElementById("clientSend");
const clientInput = document.getElementById("clientInput");
const clientMessages = document.getElementById("clientMessages");

const typing = document.getElementById("typingIndicator");

clientSend.addEventListener("click", sendClientMessage);
clientInput.addEventListener("keypress", e => {
    if (e.key === "Enter") sendClientMessage();
});

async function sendClientMessage() {
    const text = clientInput.value.trim();
    if (text === "") return;

    addMessage(text, "sent");
    clientInput.value = "";

    try {
        const response = await fetch('salvar-mensagem.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'mensagem=' + encodeURIComponent(text)
        });

        const data = await response.json();
        
        if (!data.success) {
            console.error('Erro ao salvar:', data.error);
        }
        
    } catch (error) {
        console.error('Erro de rede:', error);
    }

    simulateTyping();
}

function addMessage(text, type) {
    const div = document.createElement("div");
    div.classList.add("msg", type);
    div.textContent = text;
    clientMessages.appendChild(div);

    clientMessages.scrollTop = clientMessages.scrollHeight;
}

/* Simula o atendente digitando */
function simulateTyping() {
    typing.style.display = "flex";

    setTimeout(() => {
        typing.style.display = "none";
        addMessage("Certo! Estou verificando isso pra você...", "received");
    }, 2000);
}