# app/_usuario/forms.py

from flask_wtf import FlaskForm
from wtforms import widgets, Form as _Form
from wtforms.fields.html5 import DateTimeField, DateField, TimeField
from wtforms import PasswordField, StringField, SubmitField, BooleanField, ValidationError
from wtforms.validators import DataRequired, Email, EqualTo
from .. models.models import Usuario

class RegistrationForm(FlaskForm):
    """
    Form para usuário cadastrar
    """
    name = StringField('Nome', validators=[DataRequired(message='Campo obrigatório.')])
    email = StringField('Email', validators=[DataRequired(message='Campo obrigatório.'), Email(message='campo com xxx@gmail.com')])
    tipo = StringField('Usuário', validators=[DataRequired()])
    password = PasswordField('Password', validators=[DataRequired()])
    #confirm_password = PasswordField('Confirm Password')

    inscat = BooleanField()
    altcat = BooleanField()
    delcat = BooleanField()

    inseve = BooleanField()
    alteve = BooleanField()
    deleve = BooleanField()

    insprd = BooleanField()
    altprd = BooleanField()
    delprd = BooleanField()

    insusu = BooleanField()
    altusu = BooleanField()
    delusu = BooleanField()

    submit = SubmitField('Salvar')

    def validate_email(self, field):
        if Usuario.query.filter_by(usu_email=field.data).first():
            raise ValidationError('Esse email já está sendo usado.')

    def validate_username(self, field):
        if Usuario.query.filter_by(usu_nome=field.data).first():
            raise ValidationError('O nome de usuário já está em uso.')

class LoginForm(FlaskForm):  
    """
    Form para usuário logar
    """
    email = StringField('Email', [Email(message='Email inválido!')])
    password = PasswordField('Senha', validators=[DataRequired(message='Campo obrigatório.')])
    remember = BooleanField('Lembrar-me')
    hora = TimeField(format='%H:%M')
    date = DateField()
    submit = SubmitField('Login')