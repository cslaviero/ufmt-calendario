# Calendario Acadêmico UFMT

Reprojeto do calendário acadêmico da Universidade Federal de Mato Grosso.

Pré Requisitos
- PHP 5.6 ou superior
- Apache 2.x com mod_rewrite ativado

Arquivo de conexão ao Banco de Dados
- "app\src\conecta.php"

Banco de Dados em SQL para instalação
- "calendario.sql"

## Instalação de Servidor local para testes

Baixe o XAMPP para Windows
- https://www.apachefriends.org/index.html
- No setup de instalação, selecione os serviços para instalar (Apache, MySQL, PHP, Perl e phpMyAdmin).
- Selecione uma pasta para instalar o xampp (Ex.: C:\xampp).
- Após instalados todos os serviços, copie ou clone o projeto para a pasta /htdocs dentro da pasta raiz do Xampp.
- Ative o mod_rewrite no arquivo httpd.conf, clicando em "config" no painel do Xampp. Dentro do arquivo php.ini descomente a linha correspondente ao comando "LoadModule rewrite_module modules/mod_rewrite.so".

Instalar Banco de dados
- Inicie o Serviço phpMyAdmin usando o Browser (http://localhost/phpMyAdmin ou clique em phpMyAdmin no botão "config" do Apache no painel do Xampp) e efetue o login digitando os valores padrões de login: root, sem senha.
- Importe o arquivo calendario.sql que consta na pasta raíz deste projeto clicando em "Importar".

Acessar o site do Calendário
- Acesse o site através do Browser (http://localhost)
- Para acessar a Área Administrativa (http://localhost/admin)
