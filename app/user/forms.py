# app/user/forms.py

from flask_wtf import FlaskForm
from wtforms import widgets, Form as _Form

from wtforms.fields.html5 import DateTimeField, DateField, TimeField
from wtforms import PasswordField, StringField, SubmitField, BooleanField, ValidationError
from wtforms.validators import DataRequired, Email, EqualTo
from app.models.models import Usuario

class RegistrationForm(FlaskForm):
    """
    Form para users cadastrar
    """
    name = StringField('Nome', validators=[DataRequired()])
    email = StringField('Email', validators=[DataRequired(), Email()])
    tipo = StringField('Usuário', validators=[DataRequired()])
    password = PasswordField('Password', validators=[DataRequired()])
    #confirm_password = PasswordField('Confirm Password')
    submit = SubmitField('Register')
    
    def validate_email(self, field):
        if Usuario.query.filter_by(usu_email=field.data).first():
            raise ValidationError('Email is already in use.')

    def validate_username(self, field):
        if Usuario.query.filter_by(usu_nome=field.data).first():
            raise ValidationError('Username is already in use.')

class LoginForm(FlaskForm):  
    """
    Form para users logar
    """
    email = StringField('Email', [Email(message='Email inválido!')])
    password = PasswordField('Senha', validators=[DataRequired(message='Campo obrigatório.')])
    remember = BooleanField('Lembrar-me')
    hora = TimeField(format='%H:%M')
    #date = DateField(format='%Y/%m/%d')
    submit = SubmitField('Login')
    date = DateTimeField('Which date favorite', format='%Y/%m/%d %H:%M:%S')
    #date = TimeField('Which date favorite', format='%H:%M')
"""
class template_filter('to_date')
def format_datetime(value):
    return value.strftime('%d/%m/%Y')
"""