# app/_evento/forms.py

from flask_wtf import FlaskForm
from wtforms import widgets, Form as _Form
from wtforms.fields import DateTimeField, DateField, TimeField
from wtforms import PasswordField, StringField, SubmitField, BooleanField, ValidationError
from wtforms.validators import DataRequired, Email, EqualTo
from .. models.models import Evento


class EventoForm(FlaskForm):
    enviarNotificacao = BooleanField()
