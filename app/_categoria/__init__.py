# app/_categoria/__init__.py
from flask import Blueprint

categoria = Blueprint('categoria', __name__)

from . import viewsCategoria