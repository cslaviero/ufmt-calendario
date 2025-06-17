# app/periodo/__init__.py

from flask import Blueprint

periodo = Blueprint('periodo', __name__)

from . import viewPeriodo
