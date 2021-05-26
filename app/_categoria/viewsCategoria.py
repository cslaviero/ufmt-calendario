# app/_categoria/viewsCategoria.py
from flask import flash, redirect, render_template, url_for, request
from flask_login import login_required
from datetime import datetime
from . import categoria
from wtforms import Form, BooleanField, StringField, PasswordField, SelectField, validators
from .. import db
from .. import mysql
from .. models.models import Categoria

@categoria.route('/categorias', methods=['GET', 'POST'])
def categorias():

	cur = mysql.get_db().cursor()
	sql = 'SELECT cat_id, cat_nome, cat_cor FROM tbl_categoria'
	cur.execute(sql)
	rows = cur.fetchall()
	cur.close()

	return render_template('categorias.html', categorias= rows, title= "Categorias")

@categoria.route('/insertCategoria', methods=['POST'])
def insertCategoria():

	return render_template('categorias.html', categorias= rows, title= "Categorias")

@categoria.route('/updateCategoria/<int:cat_id>', methods=['POST'])
def updateCategoria(prd_id):

	return render_template('categorias.html', categorias= rows, title= "Categorias")

@categoria.route('/deleteCategoria/<int:cat_id>', methods=['POST'])
def deleteCategoria(prd_id):

	flash('Evento não deletado')
	return redirect(url_for('categoria.categorias'))
