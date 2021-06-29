# app/_categoria/viewsCategoria.py
from flask import flash, redirect, render_template, url_for, request
from flask_login import login_required, current_user
from datetime import datetime
from . import categoria
from wtforms import Form, BooleanField, StringField, PasswordField, SelectField, validators
from .. import db
from .. import mysql
from .. models.models import Categoria, Permissoes

@categoria.route('/categorias', methods=['GET', 'POST'])
@login_required
def categorias():

	rows = Categoria.query.all();
	auxId = current_user.get_id()
	prmEvento = Permissoes.query.filter_by(prm_usuario = auxId).filter_by(prm_item_permitido = 1).first ()
	prmCategoria = Permissoes.query.filter_by(prm_usuario = auxId).filter_by(prm_item_permitido = 2).first ()
	prmPeriodo = Permissoes.query.filter_by(prm_usuario = auxId).filter_by(prm_item_permitido = 3).first ()
	prmUsuario = Permissoes.query.filter_by(prm_usuario = auxId).filter_by(prm_item_permitido = 4).first ()

	return render_template('categorias.html', categorias= rows, prmEvento= prmEvento, prmCategoria= prmCategoria, prmPeriodo= prmPeriodo, prmUsuario= prmUsuario, title= "Categorias")

@categoria.route('/insertCategoria', methods=['POST'])
@login_required
def insertCategoria():

	if request.method == 'POST':
		try:
			nome = request.form['nome']
			cor = request.form['cor2']
			categoria = Categoria(nome, cor)
			db.session.add(categoria)
			#adiciona o evento no db
			db.session.commit()
			flash('Cadastro da categoria realizado com sucesso', 'success')
		except Exception as e:
			flash('Cadastro da categoria não realizado', 'error')
		finally:
			# redireciona para a página de categorias
			return redirect(url_for('categoria.categorias'))

@categoria.route('/updateCategoria/<int:idCat>', methods=['POST'])
@login_required
def updateCategoria(idCat):

	if request.method == 'POST':
		try:
			categoria = Categoria.query.filter_by(cat_id = idCat).first()
			categoria.cat_nome = request.form['nome']
			categoria.cat_cor = request.form['cor'][1:]
			db.session.commit()
			flash('Alteração da categoria realizada com sucesso', 'success')
		except Exception as e:
			flash('Alteração da categoria não realizada', 'error')
		finally:
			return redirect(url_for('categoria.categorias'))

@categoria.route('/deleteCategoria/<int:idCat>', methods=['POST'])
@login_required
def deleteCategoria(idCat):

	if request.method == 'POST':
		try:
			Categoria.query.filter_by(cat_id = idCat).delete()
			db.session.commit()
			flash('Categoria deletada com sucesso', 'success')
		except Exception as e:
			flash('Categoria não deletada', 'error')
		finally:
			# redireciona para a página de categorias
			return redirect(url_for('categoria.categorias'))
