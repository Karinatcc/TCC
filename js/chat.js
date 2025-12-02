// chat.js - VERSÃO FINAL CORRIGIDA
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
    
    // Envia para salvar
    fetch('salvar-mensagem.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `mensagem=${encodeURIComponent(texto)}&cliente_id=${clienteId}`
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
    msgInput.onkeypress = function(e) {
        if (e.key === 'Enter') sendMessage();
    };
}

// Carrega mensagens
window.carregarMensagens = function() {
    const clienteId = sessionStorage.getItem('clienteAtual');
    if (!clienteId || !messages) return;
    
    console.log('📥 Carregando mensagens para cliente:', clienteId);
    
    // Carrega TODAS mensagens da empresa
    fetch('carregar-mensagens-empresa.php')
        .then(r => r.json())
        .then(data => {
            console.log('📦 Mensagens recebidas:', data.mensagens?.length || 0);
            
            if (data.mensagens) {
                // Filtra mensagens deste cliente específico
                mostrarMensagensFiltradas(data.mensagens, clienteId);
            }
        })
        .catch(error => {
            console.error('❌ Erro ao carregar mensagens:', error);
        });
};

// Nova função: Filtra mensagens por cliente_id
function mostrarMensagensFiltradas(todasMensagens, clienteId) {
    if (!messages) return;
    
    messages.innerHTML = '';
    
    // Filtra: mostra apenas mensagens deste cliente
    const mensagensDoCliente = todasMensagens.filter(msg => {
        // Se a mensagem tem cliente_id, compara
        if (msg.cliente_id) {
            return msg.cliente_id === clienteId;
        }
        // Se não tem cliente_id (mensagens antigas), mostra de qualquer forma
        return true;
    });
    
    console.log(`👥 Mensagens filtradas: ${mensagensDoCliente.length} de ${todasMensagens.length}`);
    
    if (mensagensDoCliente.length === 0) {
        messages.innerHTML = '<div class="msg-info">Nenhuma mensagem ainda. Envie a primeira!</div>';
        return;
    }
    
    mensagensDoCliente.forEach(msg => {
        const div = document.createElement('div');
        // true = mensagem do dono (enviado_por não é null)
        // false = mensagem do cliente (enviado_por é null)
        const isSent = msg.enviado_por !== null;
        
        div.className = `msg ${isSent ? 'sent' : 'received'}`;
        div.innerHTML = `
            ${msg.conteudo}
            <div class="msg-info">
                ${isSent ? 'Você' : 'Cliente'} • ${formatarHora(msg.criado_em)}
            </div>
        `;
        
        messages.appendChild(div);
    });
    
    messages.scrollTop = messages.scrollHeight;
}

// Função antiga (mantém para compatibilidade)
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
setInterval(function() {
    if (sessionStorage.getItem('clienteAtual')) {
        window.carregarMensagens();
    }
}, 3000);

// Teste manual
window.testarChat = function() {
    const clienteTeste = 'cli_test_123';
    sessionStorage.setItem('clienteAtual', clienteTeste);
    sessionStorage.setItem('clienteNome', 'Cliente Teste');
    document.getElementById('chatName').textContent = 'Cliente Teste';
    window.carregarMensagens();
    console.log('🧪 Teste manual executado');
};