# calendarioufr
Reprojeto do calendário acadêmico da Universidade Federal de Rondonópolis
###
Start projeto
Usando o clone do projeto acesse o diretório do projeto:
exemplo: 
#
	cd c:/calendarioufr
#
Instalar o python: version 3.9.5 ou superior
#
	Python do projet version 3.9.5
#
Ativar ambiente virtual:
#
	venv\Scripts\activate
#
Dependências: 
#
	pip install -r requirements.txt
#
Windows cmd:
# 
	set FLASK_CONFIG=development
	set FLASK_APP=run.py
	set FLASK_ENV=development
	set FLASK_DEBUG=True 
	flask run
#
Linux and Mac:
# 
	export FLASK_CONFIG=development
	export FLASK_APP=run.py
	export FLASK_ENV=development
	export FLASK_DEBUG=True
	flask run
#
configurar start projeto-> link (caso algo dê errado)
#
	https://flask.palletsprojects.com/en/1.1.x/tutorial/factory/
#

Criar o banco de dados MySql: Calendario
Configurar o nome e senha no arquivo 'config.py', diretório 'C:\calendarioufr\instance' 

Banco migração:
#
	flask db init
#
se já iniciado:
#
	flask db stamp head
#
	flask db migrate
	flask db upgrade
#
referência estrutura:
#
	https://explore-flask.readthedocs.io/en/latest/organizing.html
#
estrutura do projeto em diretórios:
#
├───calendarioufr
│   ├───app
│   │   ├───models
│   │   │   └───__pycache__
│   │   ├───static
│   │   │   ├───css
│   │   │   ├───dist
│   │   │   ├───font-awesome
│   │   │   │   ├───css
│   │   │   │   ├───fonts
│   │   │   │   ├───less
│   │   │   │   └───scss
│   │   │   ├───img
│   │   │   └───js
│   │   ├───templates
│   │   ├───_categoria
│   │   │   └───__pycache__
│   │   ├───_evento
│   │   │   └───__pycache__
│   │   ├───_home
│   │   │   └───__pycache__
│   │   ├───_inicio
│   │   │   └───__pycache__
│   │   ├───_periodo
│   │   │   └───__pycache__
│   │   ├───_usuario
│   │   │   └───__pycache__
│   │   └───__pycache__
│   ├───instance
│   ├───migrations
│   │   ├───versions
│   │   │   └───__pycache__
│   │   └───__pycache__
│   └───__pycache__
----------------
#