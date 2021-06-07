# app/_evento/viewsEvento.py
from flask import flash, redirect, render_template, url_for, request
from flask_login import login_required
from datetime import datetime
from . import evento
from wtforms import Form, BooleanField, StringField, PasswordField, SelectField, validators
from .. _evento.formEvento import EventoForm
from .. import db
from .. import mysql
from .. models.models import Evento

@evento.route('/eventos', methods=['GET', 'POST'])
def eventos():

	form = EventoForm()
	cur = mysql.get_db().cursor()
	#sql = 'SELECT tbe.eve_id, tbe.eve_periodo, tbe.eve_nome , date_format(tbp.prd_data_ini, %s) as  date_inicio , date_format(tbe.prd_data_fim, %s) as date_fim, tbe.prd_url FROM tbl_eventos as tbe'
	sql = 'SELECT eve_id, eve_nome, prd_nome, cat_nome, date_format(eve_data_ini, %s) as inicio, date_format(eve_data_fim, %s) as fim FROM tbl_categoria, tbl_periodos, tbl_eventos WHERE cat_id = eve_categoria AND prd_id = eve_periodo ORDER BY eve_data_ini DESC LIMIT 500'
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

	return render_template('eventos.html', form= form, eventos= rows, periodos= rowsPeriodo, categorias= rowsCategoria, title= "Eventos")

@evento.route('/insertEvento', methods=['POST'])
def insertEvento():

	form = EventoForm()
	if request.method == 'POST':
		idPeriodo = request.form['periodo']
		idCategoria = request.form['categoria']
		nome = request.form['nome']
		local = request.form['local']

		data = request.form['dataini']
		dataini = datetime(int(data[6:10]), int(data[3:5]), int(data[0:2]), int(data[11:13]), int(data[14:16]), int(data[17:19]))

		data = request.form['datafim']
		datafim = datetime(int(data[6:10]), int(data[3:5]), int(data[0:2]), int(data[11:13]), int(data[14:16]), int(data[17:19]))

		if not form.enviarNotificacao.data:
			print('============Enviar Notificacao: ', form.enviarNotificacao.data)

		des = request.form['desc']
		url = request.form['url']

		evento = Evento(idPeriodo, idCategoria, nome, local, dataini, datafim, des, url)
		print('======== evento: ', evento)
		db.session.add(evento)
		#adiciona o evento no db
		db.session.commit()
		flash('Cadastro do evento realizado com sucesso')
		# redireciona para a página de períodos
		return redirect(url_for('evento.eventos'))
	flash('Cadastro do evento não realizado')
	return render_template('eventos.html', title= "Eventos")

@evento.route('/updateEvento/<int:eve_id>', methods=['POST'])
def updateEvento(prd_id):
	form = EventoForm()

	return render_template('eventos.html', eventos= rows, title= "Eventos")

@evento.route('/deleteEvento/<int:eve_id>', methods=['POST'])
def deleteEvento(prd_id):
	form = EventoForm()

	flash('Evento não deletado')
	return redirect(url_for('evento.eventos'))

