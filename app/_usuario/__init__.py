# app/_usuario/__init__.py

from flask import Blueprint

usuario = Blueprint('usuario', __name__)

from . import viewUsuario
