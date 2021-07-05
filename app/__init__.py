# app/__init__.py

from flask import Flask
from flask_sqlalchemy import SQLAlchemy
from flask_login import LoginManager
from flaskext.mysql import MySQL
from flask_bootstrap import Bootstrap
from config import app_config
from flask_migrate import Migrate

# inicialização da variável db
db = SQLAlchemy()
login_manager = LoginManager()
mysql = MySQL()


def create_app(config_name):
    app = Flask(__name__, instance_relative_config=True)
    app.config.from_object(app_config[config_name])
    app.config.from_pyfile('config.py')
    
    bootstrap = Bootstrap(app)
    db.init_app(app)
    mysql.init_app(app)
    login_manager.init_app(app)

    login_manager.login_message = "Você precisa estar logado para acessar esta página."
    login_manager.login_view = "usuario.login"
    migrate = Migrate(app, db)
    from app.models import models

    from ._home import home as home_blueprint
    app.register_blueprint(home_blueprint)

    from ._periodo import periodo as periodo_blueprint
    app.register_blueprint(periodo_blueprint)

    from ._inicio import inicio as inicio_blueprint
    app.register_blueprint(inicio_blueprint)

    from ._evento import evento as evento_blueprint
    app.register_blueprint(evento_blueprint)

    from ._usuario import usuario as usuario_blueprint
    app.register_blueprint(usuario_blueprint)

    from ._categoria import categoria as categoria_blueprint
    app.register_blueprint(categoria_blueprint)

    return app