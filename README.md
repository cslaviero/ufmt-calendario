# calendarioufr
Reprojeto do calendário acadêmico da Universidade Federal de Rondonópolis
#
Start projeto

	Usando o clone do projeto acesse o diretório do projeto:
	exemplo: 
	cd c:/calendarioufr
#
Instalar o python: version 3.9.5 ou superior

	Python do projet version 3.9.5
#
Ativar ambiente virtual:

	venv\Scripts\activate
#
Dependências: 

	pip install -r requirements.txt
#
Windows cmd:

	set FLASK_CONFIG=development
	set FLASK_APP=run.py
	set FLASK_ENV=development
	set FLASK_DEBUG=True 
	flask run
#
Linux and Mac:

	export FLASK_CONFIG=development
	export FLASK_APP=run.py
	export FLASK_ENV=development
	export FLASK_DEBUG=True
	flask run
#
configurar start projeto-> link (caso algo dê errado)

	https://flask.palletsprojects.com/en/1.1.x/tutorial/factory/

#
Criar o banco de dados MySql: Calendario
Configurar o USER e PASSWORD no arquivo 'config.py', diretório 'C:\calendarioufr\instance'
Realizar a importação da base de dados 'calendario.sql'

	Na importação serão inseridos:
	Um usuário com o Login: admin e senha: 123
	Um período teste
	Um evento teste
	Os itens permitidos ao acesso do usuário
	Permissões do usuário
	Categorias
	Note: a importação é necessária para popular a base de dados para rodar a aplicação.

Banco migração:

	flask db init
#
se já iniciado:

	flask db stamp head

	flask db migrate
	flask db upgrade
#
referência estrutura:

	https://explore-flask.readthedocs.io/en/latest/organizing.html
#
estrutura do projeto em diretórios:

	calendarioufr
		app
			__init__.py
			_categoria
				viewCategoria.py
			_evento
				formEvento.py
				viewEvento.py
			_home
				viewHome.py
			_inicio
				viewInicio.py
			_periodo
				formPeriodo.py
				viewPeriodo.py
			_usuario
				formUsuario.py
				viewUsuario.py
			models
				models.py
			static
				css
				dist
					calendar.js
					categoria.js
					eventos.js
					inicio.js
					main.js
					periosos.js
					usuarios.js
				font-awesome
				img
				js
			templates
				categorias.html
				comentarios.html
				dadosPeriodo.html
				eventos.html
				eventoShow.html
				form_categorias.html
				form_comentario.html
				form_eventos.html
				form_perfil.html
				form_eventos.html
				form_perfil_ùser.html
				form_periodos.html
				form_users.html
				index.html
				inicio.html
				login.hrml
				navegacao.html
				showProxEventos.html
				user.html
		instance
			config.py
		migrations
			alembic.ini
			env.py
	calendario.sql
	config.py
	README.md
	requirements.txt
	run.py
#