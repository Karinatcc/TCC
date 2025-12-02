// sidebar-simples.js - VERSÃO ULTRA SIMPLES
console.log('Sidebar carregada');

function carregarClientes() {
    fetch('listar-clientes.php?status=aguardando')
        .then(r => r.json())
        .then(data => {
            if (data.clientes && data.clientes.length > 0) {
                mostrarClientes(data.clientes);
            }
        });
}

function mostrarClientes(clientes) {
    const statusText = document.getElementById('statusText');
    if (statusText) statusText.style.display = 'none';
    
    // Remove lista anterior
    const listaAntiga = document.querySelector('.clientes-lista');
    if (listaAntiga) listaAntiga.remove();
    
    // Cria nova lista
    const lista = document.createElement('div');
    lista.className = 'clientes-lista';
    
    clientes.forEach(cliente => {
        const div = document.createElement('div');
        div.className = 'cliente';
        div.innerHTML = `<strong>${cliente.nome}</strong>`;
        
        div.onclick = function() {
            selecionarCliente(cliente.cliente_id, cliente.nome);
        };
        
        lista.appendChild(div);
    });
    
    // Adiciona após statusText
    if (statusText) {
        statusText.insertAdjacentElement('afterend', lista);
    }
}

function selecionarCliente(clienteId, clienteNome) {
    console.log('Cliente selecionado:', clienteId);
    
    // 1. Atualiza título
    document.getElementById('chatName').textContent = clienteNome;
    
    // 2. Salva no sessionStorage
    sessionStorage.setItem('clienteAtual', clienteId);
    sessionStorage.setItem('clienteNome', clienteNome);
    
    // 3. Recarrega mensagens (o chat.js vai buscar)
    if (window.carregarMensagens) {
        window.carregarMensagens();
    }
}

// Inicia
carregarClientes();
setInterval(carregarClientes, 10000);