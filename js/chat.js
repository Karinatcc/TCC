console.log('✅ Chat carregado');

// Elementos
const sendBtn = document.getElementById('sendBtn');
const msgInput = document.getElementById('msgInput');
const messages = document.getElementById('messages');

// Envio de mensagem
function sendMessage() {
    const clienteId = sessionStorage.getItem('clienteAtual');

    if (!clienteId) {
        alert('⚠️ Selecione um cliente primeiro!');
        return;
    }

    const texto = msgInput.value.trim();
    if (!texto) return;

    // Mostra na tela (feedback imediato)
    const div = document.createElement('div');
    div.className = 'msg sent';
    div.textContent = texto;
    messages.appendChild(div);

    msgInput.value = '';
    messages.scrollTop = messages.scrollHeight;

    const empresaId = sessionStorage.getItem('empresa_id');

    // Envia para salvar
    fetch('salvar-mensagem.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `mensagem=${encodeURIComponent(texto)}&cliente_id=${clienteId}&empresa_id=${empresaId}`
    })
        .then(r => r.json())
        .then(data => {
            if (!data.success) {
                console.error('❌ Erro ao salvar:', data.error);
            }
        })
        .catch(error => {
            console.error('❌ Erro de rede:', error);
        });
}

// Eventos
if (sendBtn && msgInput) {
    sendBtn.onclick = sendMessage;
    msgInput.onkeypress = function (e) {
        if (e.key === 'Enter') sendMessage();
    };
}
// Carrega mensagens
window.carregarMensagens = function () {
    const clienteId = sessionStorage.getItem('clienteAtual');

    if (!clienteId || !messages) return;

    fetch(`carregar-mensagens.php?cliente_id=${clienteId}`)
        .then(r => r.json())
        .then(data => {
            if (data.success && data.mensagens) {
                renderizarMensagens(data.mensagens);
            }
        })
        .catch(error => {
            console.error('❌ Erro ao carregar mensagens:', error);
        });
};

// Renderiza mensagens no chat
function renderizarMensagens(mensagens) {
    if (!messages) return;

    messages.innerHTML = '';

    if (mensagens.length === 0) {
        messages.innerHTML = `
            <div class="msg-info">Nenhuma mensagem ainda. Envie a primeira!</div>
        `;
        return;
    }

    mensagens.forEach(msg => {
        const div = document.createElement('div');

        // enviado_por == 0 OU null  → cliente
        // enviado_por != 0         → empresa
        const ehEmpresa = msg.enviado_por !== null && Number(msg.enviado_por) !== 0;

        div.className = `msg ${ehEmpresa ? 'empresa' : 'cliente'}`;

        // Protege contra HTML malicioso
        const conteudoSeguro = document.createElement('div');
        conteudoSeguro.textContent = msg.conteudo;

        div.innerHTML = `
        ${conteudoSeguro.innerHTML}
        <div class="msg-info">
            ${ehEmpresa ? 'Você' : 'Cliente'} • ${formatarHora(msg.criado_em)}
        </div>
    `;

        messages.appendChild(div);
    });


    messages.scrollTop = messages.scrollHeight;
}


function mostrarMensagens(mensagens) {
    mostrarMensagensFiltradas(mensagens, sessionStorage.getItem('clienteAtual'));
}

function formatarHora(dataString) {
    try {
        const data = new Date(dataString);
        return data.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
    } catch (e) {
        return '--:--';
    }
}

// Auto-atualização a cada 3 segundos
setInterval(function () {
    if (sessionStorage.getItem('clienteAtual')) {
        window.carregarMensagens();
    }
}, 3000);
