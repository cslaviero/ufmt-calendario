# app/periodo/views.py

from flask import render_template, request
from flask_login import login_required
from wtforms import Form, BooleanField, StringField, PasswordField, SelectField, validators
from .. import mysql
from . import periodo


@periodo.route('/periodos', methods=['GET', 'POST'])
def add():
  #if request.method == 'POST':
  cur = mysql.get_db().cursor()
  cur.execute('''SELECT * FROM tbl_periodos''')
  rv = cur.fetchall()
  cur.close()
  for x in rv:
    print(x[0], x[1])

  return render_template('periodo/periodo.html', periodos=rv, title="Periodos")
  
