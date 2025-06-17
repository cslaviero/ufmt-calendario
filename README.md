# Calendário UFR
Reprojeto do calendário acadêmico da Universidade Federal de Rondonópolis
#
## Como iniciar

1. Instale o **Python 3.11** ou superior.
2. Crie e ative um ambiente virtual:

   ```bash
   python -m venv venv
   source venv/bin/activate  # no Windows use venv\Scripts\activate
   ```

3. Instale as dependências:

   ```bash
   pip install -r requirements.txt
   ```

4. Defina as variáveis do Flask e execute a aplicação:

   ```bash
   export FLASK_CONFIG=development
   export FLASK_APP=run.py
   export FLASK_ENV=development
   export FLASK_DEBUG=True
   flask run
   ```
   No Windows substitua `export` por `set`.
#
Configurar start projeto para (Development): referência ou (caso algo dê errado)

	https://flask.palletsprojects.com/en/1.1.x/tutorial/factory/

Configurar start projeto para (Production):

	https://flask.palletsprojects.com/en/2.0.x/config/

#
### Banco de dados

1. Crie um banco MySQL chamado **calendario**.
2. Ajuste `USER` e `PASSWORD` em `instance/config.py`.
3. Importe o arquivo `calendario.sql` para incluir os dados iniciais (usuário admin, itens de exemplo etc.).
   A importação é necessária para que a aplicação funcione corretamente.

### Migrações

	flask db init

se já iniciado:

	flask db stamp head

	flask db migrate
	flask db upgrade
#
Referência estrutural:

	https://explore-flask.readthedocs.io/en/latest/organizing.html
Estrutura do projeto:

	*calendarioufr
	├──────app
	│	├───__init__.py
	│	├───_categoria
	│	│	└───viewCategoria.py
	│	├───_evento
	│	│	├───formEvento.py
	│	│	└───viewEvento.py
	│	├───_home
	│	│	└───viewHome.py
	│	├───_inicio
	│	│	└───viewInicio.py
	│	├───_periodo
	│	│	├───formPeriodo.py
	│	│	└───viewPeriodo.py
	│	├───_usuario
	│	│	├───formUsuario.py
	│	│	└───viewUsuario.py
	│	├───models
	│	│	└───models.py
	│	├───static
	│	│	├───css
	│	│	├──────dist
	│	│	│	├───calendar.js
	│	│	│	├───categoria.js
	│	│	│	├───eventos.js
	│	│	│	├───inicio.js
	│	│	│	├───main.js
	│	│	│	├───periosos.js
	│	│	│	└───usuarios.js
	│	│	├───font-awesome
	│	│	├───img
	│	│	└───js
	│	└───templates
	│		├───categorias.html
	│		├───comentarios.html
	│		├───dadosPeriodo.html
	│		├───eventos.html
	│		├───eventoShow.html
	│		├───form_categorias.html
	│		├───form_comentario.html
	│		├───form_eventos.html
	│		├───form_perfil.html
	│		├───form_eventos.html
	│		├───form_perfil_ùser.html
	│		├───form_periodos.html
	│		├───form_users.html
	│		├───index.html
	│		├───inicio.html
	│		├───login.hrml
	│		├───navegacao.html
	│		├───showProxEventos.html
	│		└───user.html
	├───instance
	│	└───config.py
	├───migrations
	│	├───alembic.ini
	│	└───env.py
	├───calendario.sql
	├───config.py
	├───README.md
	├───requirements.txt
	└───run.py
#
