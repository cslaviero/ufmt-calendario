# app/home/views.py

from flask import render_template, request
from flask_login import login_required
from wtforms import Form, BooleanField, StringField, PasswordField, SelectField, validators
from .. import mysql
from app.home import home


@home.route('/')
def homepage():
    """
    Renderize o template da página inicial index.html
    """
    return render_template('home/index.html', title="Bem vindo(a)")


@home.route('/dashboard')
@login_required
def dashboard():
    """
    Renderize o template do painel na rota / dashboard
    """
    return render_template('home/dashboard.html', title="Dashboard")

@home.route('/add', methods=['GET', 'POST'])
@login_required
def add():
  #if request.method == 'POST':
   
  cur = mysql.get_db().cursor()
    #cur.execute('''INSERT INTO tbl_campus (cps_id, cps_nome) VALUES (%s, %s)''',(id, nome))
    #mysql.connection.commit()
    #return render_template('index.html')
  cur.execute('''SELECT cps_id, cps_nome FROM tbl_campus''')
  rv = cur.fetchall()
  for x in rv:
    print(x[0], x[1])

  return str(rv)
  #return 'str(rv)'
