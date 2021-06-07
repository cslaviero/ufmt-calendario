# app/_inicio/viewsInicio.py
from flask import flash, redirect, render_template, url_for, request
from markupsafe import Markup, escape
from flask_login import login_required
from datetime import datetime
from . import inicio
from wtforms import Form, BooleanField, StringField, PasswordField, SelectField, validators
from .. _evento.formEvento import EventoForm
from .. import db
from .. import mysql
from .. models.models import Periodo, Evento

@inicio.route('/listaInicio', methods=['GET', 'POST'])
def listaInicio():

	form = EventoForm()
	cur = mysql.get_db().cursor()
	#sql = 'SELECT tbp.prd_id, tbp.prd_nome, COUNT(tbe.eve_id) as eventos, date_format(tbp.prd_data_ini, %s) as inicio, date_format(tbp.prd_data_fim, %s) as fim FROM tbl_periodos as tbp, tbl_eventos as tbe WHERE tbe.eve_periodo = tbp.prd_id GROUP BY tbe.eve_periodo ORDER BY tbp.prd_data_fim DESC LIMIT 3'
	sql='SELECT tbp.prd_id, tbp.prd_nome, COUNT(tbe.eve_id) as eventos, date_format(tbp.prd_data_ini, %s) as inicio, date_format(tbp.prd_data_fim, %s) as fim FROM tbl_periodos as tbp, tbl_eventos as tbe WHERE tbe.eve_periodo = tbp.prd_id GROUP BY tbe.eve_periodo DESC ORDER BY tbp.prd_data_fim DESC LIMIT 4'
	data1 = ('%d/%m/%Y %H:%i:%s')
	data2 = ('%d/%m/%Y %H:%i:%s')
	cur.execute(sql, (data1, data2))
	rows = cur.fetchall()

	sql = 'SELECT * FROM tbl_categoria'
	cur.execute(sql)
	rowsCategoria = cur.fetchall()
	cur.close()
	return render_template('inicio.html', form= form, periodos= rows, categorias= rowsCategoria, title= "Início")

@inicio.route('/insertEventoInicio/<string:prdNome>', methods=['POST'])
def insertEventoInicio(prdNome):
	print('======= nome período =========: ',prdNome)
	form = EventoForm()
	if request.method == 'POST':
		try:
			periodo2 = Periodo.query.filter_by(prd_nome = prdNome).first()
			idPeriodo = periodo2.prd_id
			print('======= id período =========: ',idPeriodo)
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
			flash('Cadastro do evento realizado com sucesso inicio', 'success')
		except Exception as e:
			print(e)
			flash('Cadastro do evento não realizado inicio', 'error')
		finally:
			# redireciona para a página de períodos
			return redirect(url_for('inicio.listaInicio'))

