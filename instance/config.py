# instance/config.py
import os

SECRET_KEY = os.urandom(64)
SQLALCHEMY_DATABASE_URI = 'mysql://root:root@localhost/calendario'
MYSQL_DATABASE_USER = 'root'
MYSQL_DATABASE_PASSWORD = 'root'
MYSQL_DATABASE_DB = 'calendario'
MYSQL_DATABASE_HOST= 'localhost'

