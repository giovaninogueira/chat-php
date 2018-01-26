## Bem vindo ao Chat em php
-

Para inicializar o proejto, basta seguir as instruções abaixo:

1-) Executar o comando "composer install" dentro da pasta do projeto 

2-) Após instalar as dependências, executar o comando "php index.php"

3-) Após o server estiver sendo executado, basta apenas chamar ele no cliente (web)


## Inicializando o chat no cliente (online User)

var conn = new WebSocket('ws://localhost:8090');

- Inicializando a conexão com o chat

conn.onopen = function(e) {
    console.log("Connection established!");
};

- Escutando as mensagens recebidas

conn.onmessage = function(e) {
    console.log(e.data);
};

- Enviando a mensagem

conn.send('Legal, neh ?');

- Fechando a conexão

conn.onclose = function(e) {
   console.log("User close connection!");
};

## Créditos

[RATCHET](http://socketo.me)
