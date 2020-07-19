# README

## Introdução

Este é um projeto para uma entrevista. O objetivo é fazer um sistema em php para listar, alterar e remover clientes definidos pelos seguintes campos:

* Nome
* e-mail
* CPF
* Telefone

O telefone é opcional.

Para simplificar, usamos um outro formato de CPF, o pseudo-CPF: XXX.XXX-XX. Isto evita que acidentalmente usemos CPFs verdadeiros. Os digitos depois do '-' não são verdadeiros dígitos verificadores, e a aplicação não verifica que eles correspondem a alguma transformação dos primeiros digitos.

Os números de telefone também são simplificados, no formato XX-XXX.

O email também é simplificado, e só são aceitos pontos, caracteres alfanuméricos, e um @. Não é feita nenhuma validação usando os RFCs de endereços de email ou URIs.

O projeto foi feito por mim, mas usei pequenos trechos de código que encontrei em pesquisas quando não sabia como fazer algo, por exemplo, o único arquivo css, que foi copiado e alterado, e o uso da biblioteca monolog é baseado nos exepmlos do site da mesma, além de outros trechos.

## Como rodar o projeto

Este projeto usa e precisa de:

* Uma instalação de php recente, com o servidor embutido
    - O [driver de PHP Data Objects (PDO) para postgres](https://www.php.net/manual/en/ref.pdo-pgsql.php)
    - Composer
        - A biblioteca monolog, a ser instalada pelo composer
* Uma instalação do gerenciador de base de dados postgresql

Os passos exatos para a instalação desses componentes podem variar dependendo ambiente exato onde o projeto vai ser rodado, mas um exemplo de instalação será dado abaixo, baseado no meu sistema (Fedora 32 em x86_64).

Primeiro, clone o projeto deste repositório:

<code>git clone https://github.com/sparq-beam/clientes.git</code>

Depois de clonar o projeto, entre no diretório raiz do mesmo. Os comandos abaixo supõem que o diretório atual é a raiz do projeto.

### Postgresql

A versão que utilizei para testar o projeto foi postgresql 12.3.

#### Instalação

Instale os pacotes dos clientes para postgresql:

<code>sudo dnf install postgresql</code>

Instale os pacotes necessários para rodar um servidor postgresql:

<code>sudo dnf install postgresql-server</code>

#### Configuração e lançamento

Agora é necessário configurar uma base de dados. É possível usar um cluster postgresql em outros lugares, mas este guia vai supor o uso do diretório <code>db/postgresql</code>, a partir da raiz do projeto.

Primeiro, crie o cluster:

<code>initdb -D db/postgresql</code>

O arquivo de configuração é gerado em <code>db/postgresql/postgresql.conf</code>. Se não for modificado, este arquivo permite o uso de sockets unix para conexão ao servidor, usando um diretório que pode ser privilegiado. Se quiser rodar o servidor postgresql em modo usuário,
adicione esta linha de configuração:

<code>unix_socket_directories = ''</code>

Dessa forma, só conexões TCP poderão ser usadas.

Vamos usar a porta padrão, <code>5432</code> para nos conectar ao servidor postgresql. Caso queira mudar isso, não se esqueça de mudar a configuração do projeto em <code>src/config.php</code>.

Inicie o servidor postgres apontando para o cluster que criamos:

<code>postgres -D db/postgresql/</code>

Num outro terminal, navegue de novo até o diretório raiz do projeto.

Agora, crie a base de dados 'clients' usando psql:

<code>psql -d postgresql://localhost:5432/postgres</code>

Altere a linha de comando caso tenha mudado a porta.

No programa psql, crie a base de dados <code>clients</code>:

<code>CREATE DATABASE clients;</code>

Você pode sair do psql com <code>\q</code>.

Agora, se conecte com o psql a nova base de dados:

<code>psql -d postgresql://localhost:5432/clients</code>

Para criar a tabela de clientes e inserir dados iniciais, use os arquivos distribuídos com o projeto:

Criação da tabela:

<code>\i db/create_table.sql</code>

Inserção de dados iniciais:

<code>\i db/populate_table.sql</code>

Você pode sair do psql com <code>\q</code>.

Agora a base de dados está pronta para ser usada pelo código PHP. Lembre-se de manter o servidor aberto e use outro terminal para rodar o servidor PHP.

Como alternativa, é possível abrir um servidor no background com <code>pg_ctl</code> as opções apropriadas:

<code>pg_ctl -D db/postgresql/ -l db/db.log start</code>

Isto vai escrever um log da base de dados em <code>db/db.log</code>.

### PHP

#### Instalação

A versão que utilizei para testar o projeto foi php 7.4.8.

Instale PHP no seu sistema, por exemplo, usando um gerenciador de pacotes:

<code>sudo dnf install php</code>

Instale também as extensões necessárias para PHP Data Objects, e o driver PDO para postgres. Pore exemplo:

<code>sudo dnf install php-pdo php-pgsql</code>

Instale também o composer:

<code>sudo dnf install composer</code>

#### Configuração e lançamento

Navegue até o diretório raiz do projeto.

Primeiro, é necessário instalar as dependências do projeto, definidas no arquivo <code>composer.json</code>. A partir da raiz, use o comando:

<code>composer install</code>

Isto vai baixar a biblioteca <code>monolog</code>, usada para emitir os logs.

Caso tenha alterado a porta do servidor postgresql, ou outros elementos, modifique a configuração em <code>src/config.php</code>. A configuração padrão supõe que o usuário que vai rodar o PHP pode acessar o servidor postgresql no <code>localhost:5432</code>:

<code>$config['pdoDsn'] = 'pgsql:host=localhost;port=5432;dbname=clients';</code>

Para lançar o servidor embutido PHP, use o comando, aind a partir da raiz do projeto:

<code>php -S localhost:8080 -t public</code>

Isto vai abrir um servidor que escuta a porta 8080. Caso altere a porta, lembre-se de apontar o navegador ao endereço certo.

Em produção, seria bom usar um servidor como Apache ou nginx, mas só fiz o teste com o servidor PHP.

Agora, você pode abrir seu navegador e acessar a aplicação no endereço <code>localhost:8080</code>.

## Arquitetura e outras informações

Para este projeto, usei uma arquitetura MVC simplificada:

* A pasta <code>src/</code> tem os scripts do controlador
* A pasta <code>src/view</code> tem os scripts que geram a camada visível

Existe uma classe <code>Client</code>, para representar um cliente, mas só é usada para encapsular os dados dos clientes, para listá-los depois. Não há nada para a camada do modelo. As operações com os dados sao feitas diretamente com a conexão para a base de dados. 

Os recursos públicos se encontram na pasta <code>public</code>:

* Na pasta <code>public</code> estão as páginas que podem ser pedidas pelo usuário
* Na pasta <code>public/css</code> está um arquivo css que define o estilo da tabela de clientes

As páginas públicas simplesmente incluem o código dos scripts em <code>src/</code>. Estas, por sua vez, buscam ou alteram dados na base de dados e preenchem os dados usados pelas páginas definidas em <code>src/view</code> no array <code>$viewData</code>. As páginas em <code>src/view</code> imprimem esses dados e usam pequenas estruturas de controle (<code>if, foreach</code>).

Os erros, que podem surgir em vários pontos, fazem o buffer atual ser limpado e enviam uma resposta com uma página com uma mensagem de erro. Veja <code>fatalError</code> definido em <code>src/error.php</code>.

Um log das ações principais (remover, alterar, e criar um usuário) se encontra no arquivo <code>info.log</code>. Este log é feito usando a biblioteca <code>monolog</code>.

As quatro ações principais são:

* Listar
* Alterar
* Remover
* Criar

As ações de remover e alterar precisam de uma transação SQL um pouco mais complexa que a listagem e criação. Veja o comentário no arquivo <code>src/error.php</code> para entender melhor o uso de SELECT... FOR UPDATE.
