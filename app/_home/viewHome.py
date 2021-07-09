# app/_home/views.py

from flask import flash, redirect, render_template, url_for, request
from flask_login import login_required
from wtforms import Form, BooleanField, StringField, PasswordField, SelectField, validators
from .. import mysql
from .. import db
from . import home
from .. models.models import Evento, Categoria, Comentario
from datetime import datetime
import secrets

@home.route('/')
def homepage():

	cur = mysql.get_db().cursor()
	sql='SELECT tbp.prd_id, tbp.prd_nome, COUNT(tbe.eve_id) as eventos, date_format(tbp.prd_data_ini, %s) as inicio, date_format(tbp.prd_data_fim, %s) as fim, tbp.prd_url FROM tbl_periodos as tbp, tbl_eventos as tbe WHERE tbe.eve_periodo = tbp.prd_id GROUP BY tbe.eve_periodo DESC ORDER BY tbp.prd_data_fim DESC LIMIT 4'
	data1 = ('%d/%m/%Y %H:%i:%s')
	data2 = ('%d/%m/%Y %H:%i:%s')
	cur.execute(sql, (data1, data2))
	rows = cur.fetchall()

	sql = 'SELECT * FROM tbl_categoria'
	cur.execute(sql)
	cats = cur.fetchall()

	cur.close()
	return render_template('index.html', periodos=rows, categorias= cats)

@home.route('/coment', methods=['POST'])
def coment():

	if request.method == 'POST':
		try:
			name = request.form['name']
			email = request.form['email']
			curso = request.form['curso']
			texto = request.form['texto']
			nota = request.form['fb']
			comentario = Comentario(name, email, curso, texto, nota)

			db.session.add(comentario)#adiciona o comentário no db
			db.session.commit()
		except Exception as e:
			pass
		finally:
			return redirect(url_for('home.homepage'))

@home.route('/add/<string:idPeriodo>/<string:idCat>/<string:strBusca>', methods=['GET', 'POST'])
def add(idPeriodo, idCat, strBusca):

	cur = mysql.get_db().cursor()
	sql = 'SELECT tbp.prd_id, tbp.prd_nome as nome , date_format(tbp.prd_data_ini, %s) as  date_inicio , date_format(tbp.prd_data_fim, %s) as date_fim, tbp.prd_url FROM tbl_periodos as tbp ORDER BY date_fim DESC LIMIT 3'
	data1 = ('%d/%m/%Y %H:%i:%s')
	data2 = ('%d/%m/%Y %H:%i:%s')
	cur.execute(sql, (data1, data2))
	rows = cur.fetchall()
	cur.close()
	eventDict={# cria um dicionário de dados
		'Janeiro': {1:['x/01', '10/05', 'matricula']},
		'Fevereiro': {1:['20/01', '22/05', 'ajuste matricula']},
		'Março': {1:['23/01', '25/05', 'recesso'], 2:['20/01', '10/05', 'matricula']},
		'Abril': {},
		'Maio': {},
		'Junho': {},
		'Julho': {},
		'Agosto': {},
		'Setembro': {},
		'Outubro': {},
		'Novembro': {},
		'Dezembro': {}
	}
	# cria as listas do dicionário para inseri mais de um evento
	jan ={}
	fev ={}
	mar ={}
	abr ={}
	mai ={}
	jun ={}
	jul ={}
	ago ={}
	sete ={}
	out ={}
	nov ={}
	dez ={}
	# busca todos eventos do período pelo id do período
	evento='';
	if idCat == 'None' and strBusca == 'None':
		evento = Evento.query.filter_by(eve_periodo = int(idPeriodo)).order_by(Evento.eve_data_ini.asc()).all()
	elif idCat != 'None' and strBusca == 'None':
		evento = Evento.query.filter_by(eve_periodo = int(idPeriodo)).filter_by(eve_categoria = int(idCat)).order_by(Evento.eve_data_ini.asc()).all()
	elif idCat == 'None' and strBusca != 'None':
		strBusca = '%'+strBusca+'%'
		evento = Evento.query.filter_by(eve_periodo = int(idPeriodo)).filter(Evento.eve_nome.like(strBusca)).all()
	else:
		strBusca = '%'+strBusca+'%'
		evento = Evento.query.filter_by(eve_periodo = int(idPeriodo)).filter_by(eve_categoria = int(idCat)).filter(Evento.eve_nome.like(strBusca)).all()

	mesFim = '' # variavel para esvrever abreviação do mês final do evento
	for row in evento: # inseri um objeto por vez a lista
		dataFim = str(row.eve_data_fim)
		if dataFim[5:7] == '01':
			mesFim = 'Jan' # atribui a string de abreviação do mês
		elif dataFim[5:7] == '02':
			mesFim = 'Fev'
		elif dataFim[5:7] == '03':
			mesFim = 'Mar'
		elif dataFim[5:7] == '04':
			mesFim = 'Abr'
		elif dataFim[5:7] == '05':
			mesFim = 'Mai'
		elif dataFim[5:7] == '06':
			mesFim = 'Jun'
		elif dataFim[5:7] == '07':
			mesFim = 'Jul'
		elif dataFim[5:7] == '08':
			mesFim = 'Ago'
		elif dataFim[5:7] == '09':
			mesFim = 'Set'
		elif dataFim[5:7] == '10':
			mesFim = 'Out'
		elif dataFim[5:7] == '11':
			mesFim = 'Nov'
		elif dataFim[5:7] == '12':
			mesFim = 'Dez'

		data = str(row.eve_data_ini)
		if data[5:7] == '01': # captura o evento e ordena pelo mês
			jan[row.eve_id] = str(row.eve_data_ini)[8:10],'Fev', str(row.eve_data_fim)[8:10], mesFim, row.eve_nome
		elif data[5:7] == '02':
			fev[row.eve_id] = str(row.eve_data_ini)[8:10],'Fev', str(row.eve_data_fim)[8:10], mesFim, row.eve_nome
		elif data[5:7] == '03':
			mar[row.eve_id] = str(row.eve_data_ini)[8:10],'Mar', str(row.eve_data_fim)[8:10], mesFim, row.eve_nome
		elif data[5:7] == '04':
			abr[row.eve_id] = str(row.eve_data_ini)[8:10],'Abr', str(row.eve_data_fim)[8:10], mesFim, row.eve_nome
		elif data[5:7] == '05':
			mai[row.eve_id] = str(row.eve_data_ini)[8:10],'Mai', str(row.eve_data_fim)[8:10], mesFim, row.eve_nome
		elif data[5:7] == '06':
			jun[row.eve_id] = str(row.eve_data_ini)[8:10],'Jun', str(row.eve_data_fim)[8:10], mesFim, row.eve_nome
		elif data[5:7] == '07':
			jul[row.eve_id] = str(row.eve_data_ini)[8:10],'Jul', str(row.eve_data_fim)[8:10], mesFim, row.eve_nome
		elif data[5:7] == '08':
			ago[row.eve_id] = str(row.eve_data_ini)[8:10],'Ago', str(row.eve_data_fim)[8:10], mesFim, row.eve_nome
		elif data[5:7] == '09':
			sete[row.eve_id] = str(row.eve_data_ini)[8:10],'Set', str(row.eve_data_fim)[8:10], mesFim, row.eve_nome
		elif data[5:7] == '10':
			out[row.eve_id] = str(row.eve_data_ini)[8:10],'Out', str(row.eve_data_fim)[8:10], mesFim, row.eve_nome
		elif data[5:7] == '11':
			nov[row.eve_id] = str(row.eve_data_ini)[8:10],'Nov', str(row.eve_data_fim)[8:10], mesFim, row.eve_nome
		elif data[5:7] == '12':
			dez[row.eve_id] = str(row.eve_data_ini)[8:10],'Dez', str(row.eve_data_fim)[8:10], mesFim, row.eve_nome

	eventDict['Janeiro'] = jan # inseri a lista de tuplas no dicionário
	eventDict['Fevereiro'] = fev
	eventDict['Março'] = mar
	eventDict['Abril'] = abr
	eventDict['Maio'] = mai
	eventDict['Junho'] = jun
	eventDict['Julho'] = jul
	eventDict['Agosto'] = ago
	eventDict['Setembro'] = sete
	eventDict['Outubro'] = out
	eventDict['Novembro'] = nov
	eventDict['Dezembro'] = dez
	return render_template('dadosPeriodo.html', eventDict= eventDict)

@home.route('/showEvento/?<string:idEvento>?', methods=['GET', 'POST'])
def showEvento(idEvento):

	eve = Evento.query.filter_by(eve_id = int(idEvento)).first()
	if eve is None:
		cat= None
		return redirect(url_for('home.homepage'))
	else:
		cat = Categoria.query.filter_by(cat_id = eve.eve_categoria).first()

	return render_template('eventoShow.html', evento= eve, categoria= cat, title='Evento')

@home.route('/buscaEvento', methods=['POST'])
def buscaEvento():
	if request.method == 'POST':
		busca = request.form['busca']

	cur = mysql.get_db().cursor()
	sql='SELECT tbp.prd_id, tbp.prd_nome, COUNT(tbe.eve_id) as eventos, date_format(tbp.prd_data_ini, %s) as inicio, date_format(tbp.prd_data_fim, %s) as fim FROM tbl_periodos as tbp, tbl_eventos as tbe WHERE tbe.eve_periodo = tbp.prd_id GROUP BY tbe.eve_periodo DESC ORDER BY tbp.prd_data_fim DESC LIMIT 4'
	data1 = ('%d/%m/%Y %H:%i:%s')
	data2 = ('%d/%m/%Y %H:%i:%s')
	cur.execute(sql, (data1, data2))
	rows = cur.fetchall()

	sql = 'SELECT * FROM tbl_categoria'
	cur.execute(sql)
	cats = cur.fetchall()

	cur.close()

	return render_template('index.html', strBusca=  busca, periodos=rows, categorias= cats, title='Home')

@home.route('/showProxEvento/<string:idPeriodoAtual>', methods=['POST'])
def showProxEvento(idPeriodoAtual):

	data_hora_atuais = datetime.now()
	data_e_hora_em_texto = data_hora_atuais.strftime('%d/%m/%Y')
	dia = data_hora_atuais.strftime('%d')
	mes = data_hora_atuais.strftime('%m')
	ano = data_hora_atuais.strftime('%Y')
	dia2 = int(dia)+7
	mes2= mes;
	ano2= int(ano);
	if dia2 > 30:
		dia2 = dia2 - 30
		mes2 = int(mes)+1
		if str(mes) == '12':
			mes2 = '01'
			ano2 = int(ano)+1

	data1 = (str(ano)+'-'+str(mes)+'-'+str(dia))
	data2 = (str(ano2)+'-'+str(mes2)+'-'+str(dia2))

	eventos = Evento.query.filter_by(eve_periodo = int(idPeriodoAtual)).filter(Evento.eve_data_ini.between(data1, data2)).order_by(Evento.eve_data_ini.asc()).all()

	return render_template('showProxEventos.html', eventos= eventos)
