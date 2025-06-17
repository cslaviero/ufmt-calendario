# app/_periodo/forms.py

from flask_wtf import FlaskForm
from wtforms import widgets, Form as _Form
from wtforms.fields import DateTimeField, DateField, TimeField
from wtforms import PasswordField, StringField, SubmitField, BooleanField, ValidationError
from wtforms.validators import DataRequired, Email, EqualTo
from .. models.models import Usuario

#class RegistrationForm(FlaskForm):

class PeriodoForm(FlaskForm):
    """
    Form com inputs do periodo
    """
    nome = PasswordField('Senha', validators=[DataRequired(message='Campo obrigatório.')])
    #data_ini = DateField(format='%Y/%m/%d')
    #data_ini = DateField(format='%Y/%m/%d')
    url = StringField('Email', [Email(message='Email inválido!')])
    importar = BooleanField()
    enviarNotificacao = BooleanField()
    submit = SubmitField('Login')
    #data = DateTimeField('Which date favorite', format='%Y/%m/%d %H:%M:%S')
    #hora = TimeField('Which date favorite', format='%H:%M')
