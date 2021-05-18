# app/periodo/views.py

from flask import render_template, request
from flask_login import login_required
from wtforms import Form, BooleanField, StringField, PasswordField, SelectField, validators
from .. import mysql
from . import periodo


@periodo.route('/listarPeriodos', methods=['GET', 'POST'])
def listarPeriodos():

	cur = mysql.get_db().cursor()
	cur.execute('SELECT * FROM tbl_periodos')
	rows = cur.fetchall()
	cur.close()
	#for x in rows:
	#	print(x[0], x[1])
	return render_template('periodos.html', periodos = rows, title="Periodos")

@periodo.route('/updatePeriodo', methods=['GET', 'POST'])
def updatePeriodo():

	try: 
		_name = request.form['inputName']
		_email = request.form['inputEmail']
		_password = request.form['inputPassword']
		_id = request.form['id']
		# validate the received values
		if _name and _email and _password and _id and request.method == 'POST':
			#do not save password as a plain text
			_hashed_password = generate_password_hash(_password)
			print(_hashed_password)
			# save edits
			sql = 'UPDATE tbl_user SET user_name=%s, user_email=%s, user_password=%s WHERE user_id=%s'
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
	return render_template('periodos.html', periodos = rows, title="Periodos")

