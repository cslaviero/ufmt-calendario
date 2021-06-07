# app/_periodo/viewsPeriodo.py
from flask import flash, redirect, render_template, url_for, request
from markupsafe import Markup, escape
from flask_login import login_required
from datetime import datetime
from . import periodo
from wtforms import Form, BooleanField, StringField, PasswordField, SelectField, validators
from .. _periodo.formPeriodo import PeriodoForm
from .. import db
from .. import mysql
from .. models.models import Periodo, Evento

@periodo.route('/periodos', methods=['GET', 'POST'])
def periodos():

	form = PeriodoForm()
	cur = mysql.get_db().cursor()
	sql = 'SELECT tbp.prd_id, tbp.prd_nome as nome , date_format(tbp.prd_data_ini, %s) as  date_inicio , date_format(tbp.prd_data_fim, %s) as date_fim, tbp.prd_url FROM tbl_periodos as tbp'
	data1 = ('%d/%m/%Y %H:%i:%s')
	data2 = ('%d/%m/%Y %H:%i:%s')
	cur.execute(sql, (data1, data2))
	rows = cur.fetchall()
	cur.close()
	return render_template('periodos.html', form= form, periodos= rows, title= "Períodos")

@periodo.route('/insertPeriodo', methods=['POST'])
def insertPeriodo():

	form = PeriodoForm()
	if request.method == 'POST':
		try:
			nome = request.form['nome']

			if not request.form['dataini']:
				data = ('10-01-2000 00:00:00')
			else:
				data = request.form['dataini']
				print('=============data: ', data)
			dataini = datetime(int(data[6:10]), int(data[3:5]), int(data[0:2]), int(data[11:13]), int(data[14:16]), int(data[17:19]))

			if not request.form['datafim']:
				data = ('10/01/2000 00:00:00')
			else:
				data = request.form['dataini']
			datafim = datetime(int(data[6:10]), int(data[3:5]), int(data[0:2]), int(data[11:13]), int(data[14:16]), int(data[17:19]))

			url = request.form['url']
			periodo = Periodo(nome, dataini, datafim, url)
			db.session.add(periodo)
			#adiciona o periodo no db
			db.session.commit()

			if not form.importar.data:
				print('Desablilitado.....................', form.importar.data)
			else:
				prd_select_id = (request.form['periodo'])
				periodo2 = Periodo.query.filter_by(prd_nome = nome).first()
				print('=============periodo import id : ', prd_select_id)
				print('=============Novo periodo id : ', periodo2.prd_id)

				evento = Evento.query.filter_by(eve_periodo = prd_select_id).all()
				if not evento:
					print('Objeto vazio')
				else:
					print('=====================', evento)
					for row in evento:
						evento = Evento(periodo2.prd_id, row.eve_categoria, 
							row.eve_nome, row.eve_local, row.eve_data_ini, 
							row.eve_data_fim, row.eve_descricao, row.eve_url)
						db.session.add(evento)
						db.session.commit()
						print('=======Insert Evento :', row.eve_nome)
				flash('Cadastro do período realizado com sucesso', 'success')
		except Exception as e:
			print(e)
			flash('Cadastro do período não realizado', 'error')
		finally:
			# redireciona para a página de períodos
			return redirect(url_for('periodo.periodos'))

@periodo.route('/updatePeriodo/<int:prd_id>', methods=['POST'])
def updatePeriodo(prd_id):
	#print('======= id =========: ',prd_id)
	if request.method == 'POST':
		try:
			periodo = Periodo.query.filter_by(prd_id = prd_id).first()
			periodo.prd_nome = request.form['nome']

			cat_date = request.form['dataini']
			periodo.prd_data_ini = datetime(int(cat_date[6:10]), int(cat_date[3:5]), int(cat_date[0:2]), int(cat_date[11:13]), int(cat_date[14:16]), int(cat_date[17:19]))
			#print('===========================data início===========:',periodo.prd_data_ini)
			cat_date = request.form['datafim']
			periodo.prd_data_fim = datetime(int(cat_date[6:10]), int(cat_date[3:5]), int(cat_date[0:2]), int(cat_date[11:13]), int(cat_date[14:16]), int(cat_date[17:19]))
			#print('===========================data fim===========:',periodo.prd_data_fim)
			periodo.prd_url = request.form['url']
			db.session.add(periodo)
			#adiciona o periodo no db
			db.session.commit()
			flash('Alteração do período realizado com sucesso', 'success')
		except Exception as e:
			flash('Alteração do período não realizado', 'error')
		finally:
			# redireciona para a página de períodos
			return redirect(url_for('periodo.periodos'))

@periodo.route('/deletePeriodo/<int:prd_id>', methods=['POST'])
def deletePeriodo(prd_id):
	print('======= id delete =========: ',prd_id)
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
