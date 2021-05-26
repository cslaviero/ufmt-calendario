# app/periodo/viewsPeriodo.py
from flask import flash, redirect, render_template, url_for, request
from markupsafe import Markup, escape
from flask_login import login_required
from datetime import datetime
from . import periodo
from wtforms import Form, BooleanField, StringField, PasswordField, SelectField, validators
from .. periodo.formPeriodo import PeriodoForm
from .. import db
from .. import mysql
from .. models.models import Periodo, Evento

@periodo.route('/inicio', methods=['GET', 'POST'])
def inicio():

	form = PeriodoForm()
	cur = mysql.get_db().cursor()
	#sql = 'SELECT tbp.prd_id, tbp.prd_nome as nome , date_format(tbp.prd_data_ini, %s) as  date_inicio , date_format(tbp.prd_data_fim, %s) as date_fim, tbp.prd_url FROM tbl_periodos as tbp'
	sql = 'SELECT tbp.prd_id, tbp.prd_nome, COUNT(tbe.eve_id) as eventos, date_format(tbp.prd_data_ini, %s) as inicio, date_format(tbp.prd_data_fim, %s) as fim FROM tbl_periodos as tbp, tbl_eventos as tbe WHERE tbe.eve_periodo = tbp.prd_id GROUP BY tbe.eve_periodo ORDER BY tbp.prd_nome DESC'
	data1 = ('%d/%m/%Y %H:%i:%s')
	data2 = ('%d/%m/%Y %H:%i:%s')
	cur.execute(sql, (data1, data2))
	rows = cur.fetchall()
	cur.close()

	msg = Markup('<script>alert(olá mundo);</script>')
	return render_template('inicio.html', form= form, periodos= rows, title= "Início", msg =msg)

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

	msg = Markup('<script>alert(olá mundo);</script>')
	return render_template('periodos.html', form= form, periodos= rows, title= "Períodos", msg =msg)

@periodo.route('/insertPeriodo', methods=['POST'])
def insertPeriodo():

    form = PeriodoForm()
    if request.method == 'POST':
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

        flash('Cadastro do período realizado com sucesso')
        # redireciona para a página de períodos
        return redirect(url_for('periodo.periodos'))
    flash('Cadastro do período não realizado')
    return redirect(url_for('periodo.periodos'))

@periodo.route('/updatePeriodo/<int:prd_id>', methods=['POST'])
def updatePeriodo(prd_id):
	#print('======= id =========: ',prd_id)
	if request.method == 'POST':

		periodo = Periodo.query.filter_by(prd_id = prd_id).first()
		periodo.prd_nome = request.form['nome']

		cat_date = request.form['dataini']
		periodo.prd_data_ini = datetime(int(cat_date[6:10]), int(cat_date[3:5]), int(cat_date[0:2]), int(cat_date[11:13]), int(cat_date[14:16]), int(cat_date[17:19]))
		#print('===========================data início===========:',periodo.prd_data_ini)
		cat_date = request.form['datafim']
		periodo.prd_data_fim = datetime(int(cat_date[6:10]), int(cat_date[3:5]), int(cat_date[0:2]), int(cat_date[11:13]), int(cat_date[14:16]), int(cat_date[17:19]))
		#print('===========================data fim===========:',periodo.prd_data_fim)
		periodo.prd_url = request.form['url']

		#db.session.add(periodo)
		#https://docs.sqlalchemy.org/en/14/orm/declarative_tables.html#declarative-table-configuration
		#adiciona o periodo no db
		db.session.commit()
		flash('Cadastro do período realizado com sucesso')
		# redireciona para a página de períodos
		return redirect(url_for('periodo.periodos'))
	flash('Cadastro do período não realizado')
	return redirect(url_for('periodo.periodos'))

@periodo.route('/deletePeriodo/<int:prd_id>', methods=['POST'])
def deletePeriodo(prd_id):
	print('======= id delete =========: ',prd_id)
	if request.method == 'POST':
		Periodo.query.filter_by(prd_id = prd_id).delete()
		#db.session.add(periodo)
		#https://docs.sqlalchemy.org/en/14/orm/declarative_tables.html#declarative-table-configuration
		#adiciona o periodo no db
		db.session.commit()
		flash('Período deletado com sucesso')
		# redireciona para a página de períodos
		return redirect(url_for('periodo.periodos'))
	flash('Período não deletado')
	return redirect(url_for('periodo.periodos'))

	#https://flaskage.readthedocs.io/en/latest/database_queries.html#crud-operations
	#documentação de operações crud
	"""
	try: 
		periodo = Periodo.query.filter_by(prd_id=request.form.get('id')).first()
		prd_id = request.form['nome']
		_email = request.form['inputEmail']
		_password = request.form['inputPassword']
		_id = request.form['id']
		# validate the received values
		if _name and _email and _password and _id and request.method == 'POST':
			
			sql = 'UPDATE tbpl_user SET user_name=%s, user_email=%s, user_password=%s WHERE user_id=%s'
			data = (_name, _email, _hashed_password, _id,)
			con = mysql.connect()
			cur = mysql.get_db().cursor()
			cur.execute(sql, data)
			con.commit()
			cur.close()
			flash('User updated successfully!')
			return redirect('/')
		else:
			return 'Error while updating user'
	except Exception as e:
		print(e)
	finally:
		cursor.close() 
		conn.close()
	"""
	#return render_template('periodos.html', title="periodos")

