# app/_evento/viewsEvento.py
from flask import flash, redirect, render_template, url_for, request
from flask_login import login_required, current_user
from datetime import datetime
from . import evento
from wtforms import Form, BooleanField, StringField, PasswordField, SelectField, validators
from .. _evento.formEvento import EventoForm
from .. import db
from .. import mysql
from .. models.models import Evento, Permissoes

@evento.route('/eventos')
def eventos():

	form = EventoForm()
	cur = mysql.get_db().cursor()
	sql = 'SELECT eve_id, eve_nome, prd_nome, cat_nome, date_format(eve_data_ini, %s) as inicio, date_format(eve_data_fim, %s) as fim, eve_local, eve_url, eve_descricao FROM tbl_categoria, tbl_periodos, tbl_eventos WHERE cat_id = eve_categoria AND prd_id = eve_periodo ORDER BY eve_data_ini DESC LIMIT 500'
	data1 = ('%d/%m/%Y %H:%i:%s')
	data2 = ('%d/%m/%Y %H:%i:%s')
	cur.execute(sql, (data1, data2))
	rows = cur.fetchall()

	sql = 'SELECT * FROM tbl_periodos'
	cur.execute(sql)
	rowsPeriodo = cur.fetchall()

	sql = 'SELECT * FROM tbl_categoria'
	cur.execute(sql)
	rowsCategoria = cur.fetchall()

	cur.close()

	auxId = current_user.get_id()
	prmEvento = Permissoes.query.filter_by(prm_usuario = auxId).filter_by(prm_item_permitido = 1).first ()
	prmCategoria = Permissoes.query.filter_by(prm_usuario = auxId).filter_by(prm_item_permitido = 2).first ()
	prmPeriodo = Permissoes.query.filter_by(prm_usuario = auxId).filter_by(prm_item_permitido = 3).first ()
	prmUsuario = Permissoes.query.filter_by(prm_usuario = auxId).filter_by(prm_item_permitido = 4).first ()

	return render_template('eventos.html', form= form, eventos= rows, periodos= rowsPeriodo, categorias= rowsCategoria, prmEvento= prmEvento, prmCategoria= prmCategoria, prmPeriodo= prmPeriodo, prmUsuario= prmUsuario, title= "Eventos")

@evento.route('/insertEvento', methods=['POST'])
def insertEvento():

	form = EventoForm()
	if request.method == 'POST':
		try:
			idPeriodo = request.form['periodo']
			idCategoria = request.form['categoria']
			nome = request.form['nome']
			local = request.form['local']
			data = request.form['dataini']
			dataini = datetime(int(data[6:10]), int(data[3:5]), int(data[0:2]), int(data[11:13]), int(data[14:16]), int(data[17:19]))
			data = request.form['datafim']
			datafim = datetime(int(data[6:10]), int(data[3:5]), int(data[0:2]), int(data[11:13]), int(data[14:16]), int(data[17:19]))
			if not form.enviarNotificacao.data:
				#print('============Enviar Notificacao: ', form.enviarNotificacao.data)
				pass
			des = request.form['desc']
			url = request.form['url']
			evento = Evento(idPeriodo, idCategoria, nome, local, dataini, datafim, des, url)
			db.session.add(evento)
			#adiciona o evento no db
			db.session.commit()
			flash('Cadastro do evento realizado com sucesso', 'success')
		except Exception as e:
			flash('Cadastro do evento não realizado', 'error')
		finally:
			# redireciona para a página de períodos
			return redirect(url_for('evento.eventos'))

@evento.route('/updateEvento/<int:idEve>', methods=['POST'])
def updateEvento(idEve):

	form = EventoForm()
	if request.method == 'POST':
		try:
			evento = Evento.query.filter_by(eve_id = idEve).first()
			evento.eve_nome = request.form['nome']
			evento.eve_periodo = int(request.form['periodo'])
			date = request.form['dataini']
			evento.eve_data_ini = datetime(int(date[6:10]), int(date[3:5]), int(date[0:2]), int(date[11:13]), int(date[14:16]), int(date[17:19]))
			date = request.form['datafim']
			evento.prd_data_fim = datetime(int(date[6:10]), int(date[3:5]), int(date[0:2]), int(date[11:13]), int(date[14:16]), int(date[17:19]))
			if not form.enviarNotificacao.data:
				#print('============Enviar Notificacao: ', form.enviarNotificacao.data)
				pass
			evento.eve_categoria = int(request.form['categoria'])
			evento.eve_local = request.form['local']
			evento.eve_url = request.form['url']
			evento.eve_descricao = request.form['desc']
			#adiciona o periodo no db
			db.session.commit()
			flash('Alteração do evento realizada com sucesso', 'success')
		except Exception as e:
			flash('Alteração do evento não realizada', 'error')
		finally:
			# redireciona para a página de eventos
			return redirect(url_for('evento.eventos'))

	return render_template('eventos.html', eventos= rows, title= "Eventos")

@evento.route('/deleteEvento/<int:idEve>', methods=['POST'])
def deleteEvento(idEve):

	try:
		#deleta os eventos selecionado
		Evento.query.filter_by(eve_id = idEve).delete()
		db.session.commit()
		flash('Evento deletado com sucesso', 'success')
	except Exception as e:
		flash('Evento não deletado', 'error')
	finally:
		return redirect(url_for('evento.eventos'))

