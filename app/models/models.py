# app/models.py

from flask_login import UserMixin
from werkzeug.security import generate_password_hash, check_password_hash
from app import mysql, login_manager
from sqlalchemy import Table, Column, Integer, String
from flask_login import UserMixin
from werkzeug.security import generate_password_hash, check_password_hash
from app import db, login_manager


class Usuario(UserMixin, db.Model):
    
    __tablename__ = 'tbl_usuarios'

    usu_id = db.Column(db.Integer, primary_key=True)
    usu_nome = db.Column(db.String(100), unique=True, nullable=False)
    usu_email = db.Column(db.String(200), unique=True, nullable=False)
    usu_usuario = db.Column(db.String(60), nullable=False)
    usu_senha = db.Column(db.Text, nullable=False)
    permissoes = db.relationship('Permissoes', backref='usuario', lazy='dynamic')

    def get_id(self):
        return (self.usu_id)

    @property
    def password(self):
        #Prevent pasword from being accessed
        raise AttributeError('password is not a readable attribute.')

    @password.setter
    def password(self, password):
        #Set password to a hashed password
        self.usu_senha = generate_password_hash(password)

    def verify_password(self, password):
        #Check if hashed password matches actual password
        return check_password_hash(self.usu_senha, password)
   
    def __repr__(self):
        return '<Usuário: {}>'.format(self.usu_nome)

# Set up user_loader
@login_manager.user_loader
def load_user(user_id):
    return Usuario.query.get(int(user_id))

class ItemPermitido(db.Model):

    __tablename__ = 'tbl_item_permissao'

    pri_id = db.Column(db.Integer, primary_key=True)
    pri_nome = db.Column(db.String(200), unique=True, nullable=False)
    permissoes = db.relationship('Permissoes', backref='item_permitido', lazy='dynamic')

    def __repr__(self):
        return '<Item Permitido: {}>'.format(self.pri_nome)

class Permissoes(db.Model):

    __tablename__ = 'tbl_permissoes'

    prm_id = db.Column(db.Integer, primary_key=True)
    prm_usuario = db.Column(db.Integer, db.ForeignKey('tbl_usuarios.usu_id'), nullable=False)
    prm_item_permitido = db.Column(db.Integer, db.ForeignKey('tbl_item_permissao.pri_id'), nullable=False)
    prm_inserir = db.Column(db.SmallInteger)
    prm_alterar = db.Column(db.SmallInteger)
    prm_deletar = db.Column(db.SmallInteger)

    def __repr__(self):
        return '<Permissão: {}>'.format(self.prm_id)


class Periodo(db.Model):
    
    __tablename__ = 'tbl_periodos'

    prd_id = db.Column(db.Integer, primary_key=True)
    prd_nome = db.Column(db.String(20), unique=True, nullable=False)
    prd_data_ini = db.Column(db.DateTime, nullable=False)
    prd_data_fim = db.Column(db.DateTime, nullable=False)
    prd_url = db.Column(db.Text, nullable=True)
    evento = db.relationship('Evento', backref='periodo', lazy='dynamic')

    def __repr__(self):
        return '<Período: {}>'.format(self.prd_nome)

class Categoria(db.Model):
    
    __tablename__ = 'tbl_categoria'

    cat_id = db.Column(db.Integer, primary_key=True)
    cat_nome = db.Column(db.String(100), unique=True, nullable=False)
    cat_cor = db.Column(db.String(8), nullable=False)
    evento = db.relationship('Evento', backref='categoria', lazy='dynamic')

    def __repr__(self):
        return '<Categoria: {}>'.format(self.cat_nome)

class Evento(db.Model):
    
    __tablename__ = 'tbl_eventos'

    eve_id = db.Column(db.Integer, primary_key=True)
    eve_periodo = db.Column(db.Integer, db.ForeignKey('tbl_periodos.prd_id'), nullable=False)
    eve_categoria = db.Column(db.Integer, db.ForeignKey('tbl_categoria.cat_id'), nullable=False)
    eve_nome = db.Column(db.String(255), nullable=False)
    eve_local = db.Column(db.String(255), nullable=True)
    eve_data_ini = db.Column(db.DateTime, nullable=False)
    eve_data_fim = db.Column(db.DateTime, nullable=False)
    eve_descricao = db.Column(db.Text, nullable=False)
    eve_url = db.Column(db.String(255), nullable=True)

    def __repr__(self):
        return '<Evento: {}>'.format(self.eve_nome)