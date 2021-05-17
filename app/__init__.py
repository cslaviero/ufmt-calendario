# app/__init__.py

from flask import Flask
from flask_sqlalchemy import SQLAlchemy
from flask_login import LoginManager
from flaskext.mysql import MySQL
from flask_bootstrap import Bootstrap
from flask_datepicker import datepicker
from config import app_config
from flask_migrate import Migrate
from flask_fontawesome import FontAwesome

# inicialização da variável db
db = SQLAlchemy()
login_manager = LoginManager()
mysql = MySQL()


def create_app(config_name):
    app = Flask(__name__, instance_relative_config=True)
    app.config.from_object(app_config[config_name])
    app.config.from_pyfile('config.py')
    
    bootstrap = Bootstrap(app)
    datepicker(app)
    fa = FontAwesome(app)
    db.init_app(app)
    mysql.init_app(app)
    login_manager.init_app(app)

    login_manager.login_message = "Você precisa estar logado para acessar esta página."
    login_manager.login_view = "user.login"
    migrate = Migrate(app, db)
    from app.models import models

    from .admin import admin as admin_blueprint
    app.register_blueprint(admin_blueprint, url_prefix='/admin')

    from .user import user as user_blueprint
    app.register_blueprint(user_blueprint)

    from .home import home as home_blueprint
    app.register_blueprint(home_blueprint)

    from .periodo import periodo as periodo_blueprint
    app.register_blueprint(periodo_blueprint)

    return app