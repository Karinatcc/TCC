function carregarClientes() {
    fetch('listar-clientes.php')
        .then(r => r.json())
        .then(data => {
            if (data.clientes && data.clientes.length > 0) {
                mostrarClientes(data.clientes);
            } else {
                mostrarNenhumCliente();
            }
        })
        .catch(err => console.error("Erro ao carregar clientes:", err));
}

function mostrarNenhumCliente() {
    const statusText = document.getElementById('statusText');
    const listaAntiga = document.querySelector('.clientes-lista');
    if (listaAntiga) listaAntiga.remove();

    if (statusText) {
        statusText.style.display = 'block';
        statusText.textContent = "Nenhum cliente disponível.";
    }
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

        div.innerHTML = `
            <strong>${cliente.nome}</strong>
            <p>${cliente.telefone ?? ''}</p>
        `;

        // ✅ CORREÇÃO AQUI:
        div.onclick = function () {
            selecionarCliente(cliente.cliente_id, cliente.nome);
        };

        lista.appendChild(div);
    });

    if (statusText) {
        statusText.insertAdjacentElement('afterend', lista);
    }
}

function selecionarCliente(clienteId, clienteNome) {
    console.log('✅ Cliente selecionado:', clienteId, clienteNome);

    // Atualiza o título
    document.getElementById('chatName').textContent = clienteNome;

    // Salva no sessionStorage
    sessionStorage.setItem('clienteAtual', clienteId);
    sessionStorage.setItem('clienteNome', clienteNome);

    // Agora carrega mensagens DO CLIENTE CORRETO
    if (window.carregarMensagens) {
        window.carregarMensagens();
    }
}

// Inicia automaticamente
carregarClientes();

// Atualiza a lista de clientes a cada 10s
setInterval(carregarClientes, 10000);
