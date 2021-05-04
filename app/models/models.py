# app/models.py

from flask_login import UserMixin
from werkzeug.security import generate_password_hash, check_password_hash

from app import mysql, login_manager
from sqlalchemy import Table, Column, Integer, String

# app/models.py

from flask_login import UserMixin
from werkzeug.security import generate_password_hash, check_password_hash
from app import db, login_manager


class Usuario(UserMixin, db.Model):
    
    __tablename__ = 'tbl_usuarios'

        
    usu_id = db.Column(db.Integer, primary_key=True)
    usu_nome = db.Column(db.String(100), index=True, unique=True)
    usu_email = db.Column(db.String(200), index=True, unique=True)
    usu_usuario = db.Column(db.String(60), index=True)
    usu_senha = db.Column(db.Text)

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



"""
class Department(db.Model):
    
    #Create a Department table


    __tablename__ = 'departments'

    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(60), unique=True)
    description = db.Column(db.String(200))
    Usuarios = db.relationship('Usuario', backref='department',
                                lazy='dynamic')

    def __repr__(self):
        return '<Department: {}>'.format(self.name)


class Role(db.Model):
    
    #Create a Role table
    

    __tablename__ = 'roles'

    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(60), unique=True)
    description = db.Column(db.String(200))
    Usuarios = db.relationship('Usuario', backref='role',
                                lazy='dynamic')

    def __repr__(self):
        return '<Role: {}>'.format(self.name)
"""