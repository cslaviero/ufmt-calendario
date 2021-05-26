# app/admin/__init__.py

from flask import Blueprint

evento = Blueprint('evento', __name__)

from . import viewsEvento