# app/_periodo/viewsPeriodo.py
from flask import flash, redirect, render_template, url_for, request
from markupsafe import Markup, escape
from flask_login import login_required, current_user
from datetime import datetime
from . import periodo
from wtforms import Form, BooleanField, StringField, PasswordField, SelectField, validators
from .. _periodo.formPeriodo import PeriodoForm
from .. import db
from .. import mysql
from .. models.models import Periodo, Evento, Permissoes

@periodo.route('/periodos')
@login_required
def periodos():

	form = PeriodoForm()# usado no modal
	cur = mysql.get_db().cursor()
	sql = 'SELECT tbp.prd_id, tbp.prd_nome as nome , date_format(tbp.prd_data_ini, %s) as  date_inicio , date_format(tbp.prd_data_fim, %s) as date_fim, tbp.prd_url FROM tbl_periodos as tbp'
	data1 = ('%d/%m/%Y %H:%i:%s')
	data2 = ('%d/%m/%Y %H:%i:%s')
	cur.execute(sql, (data1, data2))
	rows = cur.fetchall()
	cur.close()
	auxId = current_user.get_id()
	prmEvento = Permissoes.query.filter_by(prm_usuario = auxId).filter_by(prm_item_permitido = 1).first ()
	prmCategoria = Permissoes.query.filter_by(prm_usuario = auxId).filter_by(prm_item_permitido = 2).first ()
	prmPeriodo = Permissoes.query.filter_by(prm_usuario = auxId).filter_by(prm_item_permitido = 3).first ()
	prmUsuario = Permissoes.query.filter_by(prm_usuario = auxId).filter_by(prm_item_permitido = 4).first ()

	return render_template('periodos.html', form= form, periodos= rows, prmEvento= prmEvento, prmCategoria= prmCategoria, prmPeriodo= prmPeriodo, prmUsuario= prmUsuario, title= "Períodos")

@periodo.route('/insertPeriodo', methods=['POST'])
@login_required
def insertPeriodo():

	form = PeriodoForm()
	if request.method == 'POST':
		try:
			nome = request.form['nome']
			data = request.form['dataini']
			dataini = datetime(int(data[6:10]), int(data[3:5]), int(data[0:2]), int(data[11:13]), int(data[14:16]), int(data[17:19]))
			data = request.form['dataini']
			datafim = datetime(int(data[6:10]), int(data[3:5]), int(data[0:2]), int(data[11:13]), int(data[14:16]), int(data[17:19]))
			url = request.form['url']
			periodo = Periodo(nome, dataini, datafim, url)
			db.session.add(periodo)
			#adiciona o periodo no db
			db.session.commit()

			if form.importar.data:
				prd_select_id = (request.form['periodo'])
				periodo2 = Periodo.query.filter_by(prd_nome = nome).first()
				evento = Evento.query.filter_by(eve_periodo = prd_select_id).all()
				if evento:
					for row in evento:
						evento = Evento(periodo2.prd_id, row.eve_categoria, 
							row.eve_nome, row.eve_local, row.eve_data_ini, 
							row.eve_data_fim, row.eve_descricao, row.eve_url)
						db.session.add(evento)
						db.session.commit()
			flash('Cadastro do período realizado com sucesso', 'success')
		except Exception as e:
			flash('Cadastro do período não realizado', 'error')
		finally:
			# redireciona para a página de períodos
			return redirect(url_for('periodo.periodos'))

@periodo.route('/updatePeriodo/<int:prd_id>', methods=['POST'])
@login_required
def updatePeriodo(prd_id):

	if request.method == 'POST':
		try:
			periodo = Periodo.query.filter_by(prd_id = prd_id).first()
			periodo.prd_nome = request.form['nome']
			date = request.form['dataini']
			periodo.prd_data_ini = datetime(int(date[6:10]), int(date[3:5]), int(date[0:2]), int(date[11:13]), int(date[14:16]), int(date[17:19]))
			date = request.form['datafim']
			periodo.prd_data_fim = datetime(int(date[6:10]), int(date[3:5]), int(date[0:2]), int(date[11:13]), int(date[14:16]), int(date[17:19]))
			periodo.prd_url = request.form['url']

			db.session.commit()
			flash('Alteração do período realizada com sucesso', 'success')
		except Exception as e:
			flash('Alteração do período não realizada', 'error')
		finally:
			# redireciona para a página de períodos
			return redirect(url_for('periodo.periodos'))

@periodo.route('/deletePeriodo/<int:prd_id>', methods=['POST'])
@login_required
def deletePeriodo(prd_id):

	if request.method == 'POST':
		try:
			#deleta todos eventos do período selecionado
			Evento.query.filter_by(eve_periodo = prd_id).delete()
			db.session.commit()
			#deleta o período já sem eventos
			Periodo.query.filter_by(prd_id = prd_id).delete()
			db.session.commit()
			flash('Período deletado com sucesso', 'success')
		except Exception as e:
			flash('Período não deletado', 'error')
		finally:
			# redireciona para a página de períodos
			return redirect(url_for('periodo.periodos'))
