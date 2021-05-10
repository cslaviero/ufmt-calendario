# app/user/forms.py

from flask_wtf import FlaskForm
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
    password = PasswordField('Senha', validators=[DataRequired()])
    remember = BooleanField('Lembrar-me')
    submit = SubmitField('Login')