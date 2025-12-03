CREATE TABLE usuarios (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nome TEXT,
    email TEXT UNIQUE,
    senha TEXT,
    telefone TEXT UNIQUE,
    link_unico TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE clientes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    empresa_id INTEGER NOT NULL,
    nome TEXT,
    status TEXT DEFAULT 'aguardando',
    ultima_atividade DATETIME DEFAULT CURRENT_TIMESTAMP,
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
    telefone TEXT,
    FOREIGN KEY (empresa_id) REFERENCES usuarios(id)
);
CREATE TABLE conversas (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    cliente_id INTEGER,
    usuario_id INTEGER,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);
CREATE TABLE mensagens (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    conteudo TEXT,
    enviado_por INTEGER,
    -- 1 = usuário, NULL = cliente
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    empresa_id INTEGER,
    cliente_id INTEGER,
    conversa_id INTEGER,
    FOREIGN KEY (enviado_por) REFERENCES usuarios(id),
    FOREIGN KEY (empresa_id) REFERENCES usuarios(id),
    FOREIGN KEY (cliente_id) REFERENCES clientes(id),
    FOREIGN KEY (conversa_id) REFERENCES conversas(id)
);