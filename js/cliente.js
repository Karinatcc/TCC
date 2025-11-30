const clientSend = document.getElementById("clientSend");
const clientInput = document.getElementById("clientInput");
const clientMessages = document.getElementById("clientMessages");

const typing = document.getElementById("typingIndicator");

clientSend.addEventListener("click", sendClientMessage);
clientInput.addEventListener("keypress", e => {
    if (e.key === "Enter") sendClientMessage();
});

function sendClientMessage() {
    const text = clientInput.value.trim();
    if (text === "") return;

    addMessage(text, "sent");
    clientInput.value = "";

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
