# app/periodo/viewsEvento.py
from flask import flash, redirect, render_template, url_for, request
from flask_login import login_required
from datetime import datetime
from . import evento
from wtforms import Form, BooleanField, StringField, PasswordField, SelectField, validators
from .. import db
from .. import mysql
from .. models.models import Evento

@evento.route('/eventos', methods=['GET', 'POST'])
def eventos():

	cur = mysql.get_db().cursor()
	#sql = 'SELECT tbe.eve_id, tbe.eve_periodo, tbe.eve_nome , date_format(tbp.prd_data_ini, %s) as  date_inicio , date_format(tbe.prd_data_fim, %s) as date_fim, tbe.prd_url FROM tbl_eventos as tbe'
	sql = 'SELECT eve_id, eve_nome, prd_nome, cat_nome, date_format(eve_data_ini, %s) as inicio, date_format(eve_data_fim, %s) as fim FROM tbl_categoria, tbl_periodos, tbl_eventos WHERE cat_id = eve_categoria AND prd_id = eve_periodo ORDER BY eve_data_ini DESC LIMIT 500'
	data1 = ('%d/%m/%Y %H:%i:%s')
	data2 = ('%d/%m/%Y %H:%i:%s')
	cur.execute(sql, (data1, data2))
	rows = cur.fetchall()
	cur.close()

	return render_template('eventos.html', eventos= rows, title= "Eventos")

@evento.route('/insertEvento', methods=['POST'])
def insertEvento():

	return render_template('eventos.html', eventos= rows, title= "Eventos")

@evento.route('/updateEvento/<int:eve_id>', methods=['POST'])
def updateEvento(prd_id):

	return render_template('eventos.html', eventos= rows, title= "Eventos")

@evento.route('/deleteEvento/<int:eve_id>', methods=['POST'])
def deleteEvento(prd_id):

	flash('Evento não deletado')
	return redirect(url_for('evento.eventos'))
