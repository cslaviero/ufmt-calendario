# app/_home/views.py

from flask import render_template, request, jsonify
from flask_login import login_required
from wtforms import Form, BooleanField, StringField, PasswordField, SelectField, validators
from .. import mysql
from . import home

@home.route('/')
def homepage():

	cur = mysql.get_db().cursor()
	#sql = 'SELECT tbp.prd_nome as nome , date_format(tbp.prd_data_fim, %s) as date_fim FROM tbl_periodos as tbp ORDER BY date_fim DESC LIMIT 1'
	sql='SELECT tbp.prd_id, tbp.prd_nome, COUNT(tbe.eve_id) as eventos, date_format(tbp.prd_data_ini, %s) as inicio, date_format(tbp.prd_data_fim, %s) as fim FROM tbl_periodos as tbp, tbl_eventos as tbe WHERE tbe.eve_periodo = tbp.prd_id GROUP BY tbe.eve_periodo DESC ORDER BY tbp.prd_data_fim DESC LIMIT 4'
	data1 = ('%d/%m/%Y %H:%i:%s')
	data2 = ('%d/%m/%Y %H:%i:%s')
	cur.execute(sql, (data1, data2))
	rowsNome = cur.fetchall()
	cur.close()

	cur = mysql.get_db().cursor()
	sql = 'SELECT * FROM tbl_periodos ORDER BY date_format(tbl_periodos.prd_data_fim, %s) DESC limit 4'
	cur.execute(sql, data1)
	rows = cur.fetchall()

	cur.close()
	return render_template('index.html', pNome= rowsNome, periodos=rows, title='Home')

@home.route('/dashboard')
@login_required
def dashboard():

	cur = mysql.get_db().cursor()
	sql = 'SELECT tbp.prd_nome as nome , date_format(tbp.prd_data_fim, %s) as date_fim FROM tbl_periodos as tbp ORDER BY date_fim DESC LIMIT 1'
	data = ('%d/%m/%Y %H:%i:%s')
	cur.execute(sql, data)
	rowsNome = cur.fetchall()
	cur.close()
	return render_template('home.html', title='Dashboard')

@home.route('/add/<string:periodoNome>', methods=['GET', 'POST'])
#@login_required
def add(periodoNome):
	print('str: ', periodoNome)
	cur = mysql.get_db().cursor()
	sql = 'SELECT tbp.prd_id, tbp.prd_nome as nome , date_format(tbp.prd_data_ini, %s) as  date_inicio , date_format(tbp.prd_data_fim, %s) as date_fim, tbp.prd_url FROM tbl_periodos as tbp ORDER BY date_fim DESC LIMIT 3'
	data1 = ('%d/%m/%Y %H:%i:%s')
	data2 = ('%d/%m/%Y %H:%i:%s')
	cur.execute(sql, (data1, data2))
	rows = cur.fetchall()
	cur.close()
	eventDict={
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
	#https://www.fullstackpython.com/flask-json-jsonify-examples.html
	#jsonify({'eventDict': eventDict})
	return render_template('dadosPeriodo.html', eventDict= eventDict, periodos= rows, title= 'Períodos')

	#cur = mysql.get_db().cursor()
	#cur.execute('INSERT INTO tbl_campus (cps_id, cps_nome) VALUES (%s, %s)',(id, nome))
	#mysql.connection.commit()
	#rv = cur.fetchall()
	#for x in rv:
	#	print(x[0], x[1])
	#	return str(rv)
	#return render_template('index.html')

"""

import json

thisdict =	{
  "jan": "Ford",
  "fev": "Mustang",
  "abr": 1964
}
#thisdict["mai"] = "modells"
ele = {}
ele ["modells"] = "04/05","10/05", "receso", "11/05","20/05", "receso2","21/05","30/05", "receso3"
thisdict["mai"]  = ele
#("04/05","10/05", "receso"),(), ("21/05","30/05", "receso3")
#print(thisdict)

for x in thisdict:
  print(x)
print('----------------------')
for x in thisdict.values():
  print(x)
print('----------------------')  
for x in ele.values():
  print(x)
print('----------------------')  
pessoa_json = json.dumps(thisdict)
print(pessoa_json)
print('----------------------')
p_json = json.dumps(ele)
print(p_json)


--------------------
import re

#Check if the string starts with "The" and ends with "Spain":

txt = "The rain in Spain"
x = re.search("*Spain$", txt)

if x:
  print("YES! We have a match!")
else:
  print("No match")
  ---------------------------
<!DOCTYPE html>
<html>
<body>

<p>Looping through arrays inside arrays.</p>
<p id="demo"></p>
<p id="demo2"></p>

<script>
let data_inicio = "";
let data_fim = "";
let nomeEvento = "";
const dadosjan = {"models":["10/01", "10/05", "matricula"]}
    
const myObj = {
  "name":"John",
  "age":30,
  "jan": [{"models": ["04/05", "10/05", "receso", "11/05", "20/05", "receso2", "21/05", "30/05", "receso3"]}],
  "fev": [
    {"name":"Ford", "models":["Fiesta", "Focus", "Mustang"]},
    {"name":"BMW", "models":["320", "X3", "X5"]}
  ]
}

for (let i in myObj.jan) {
  data_inicio += "<h2>" + myObj.jan[i].name + "</h2>";
  for (let j in myObj.jan[i].models) {
    data_fim = myObj.jan[i].models;
  }
}
/*for (let i in myObj.fev) {
  data_inicio += "<h2>" + myObj.fev[i].name + "</h2>";
   
  for (let j in myObj.fev[i].models) {
      //data_inicio += myObj.fev[i].models ;
      //data_inicio=j;
      for (let k in myObj.fev[i].models) {
        //data_inicio = myObj.fev[0].models;
        data_fim = myObj.fev[1].models ;
      }
  }
}*/

document.getElementById("demo").innerHTML = "aqui"+data_inicio+"fim";
document.getElementById("demo2").innerHTML = data_fim;
</script>

</body>
</html>
https://www.digitalocean.com/community/tutorials/processing-incoming-request-data-in-flask-pt
-------------------------
<!DOCTYPE html>
<html>
<body>

<p>Looping through arrays inside arrays.</p>
<p id="demo"></p>
<p id="demo2"></p>

<script>
let data_inicio = "";
let data_fim = "";
let nomeEvento = "";
const dadosjan = {"models":["10/01", "10/05", "matricula"]}
    
const myObj = {
  "name":"John",
  "age":30,
  "jan": [{"models": ["04/05", "10/05", "receso", "11/05", "20/05", "receso2", "21/05", "30/05", "receso3"]}],
  "fev": [
    {"name":"Ford", "models":["Fiesta", "Focus", "Mustang"]},
    {"name":"BMW", "models":["320", "X3", "X5"]}
  ]
}

for (let i in myObj.jan) {
  data_inicio += "<h2>" + myObj.jan[i].name + "</h2>";
  for (let j in myObj.jan[i].models) {
    data_fim = myObj.jan[i].models;
  }
}
/*for (let i in myObj.fev) {
  data_inicio += "<h2>" + myObj.fev[i].name + "</h2>";
   
  for (let j in myObj.fev[i].models) {
      //data_inicio += myObj.fev[i].models ;
      //data_inicio=j;
      for (let k in myObj.fev[i].models) {
        //data_inicio = myObj.fev[0].models;
        data_fim = myObj.fev[1].models ;
      }
  }
}*/

document.getElementById("demo").innerHTML = "aqui"+data_inicio+"fim";
document.getElementById("demo2").innerHTML = data_fim;
</script>

</body>
</html>


thisdict =	{
  "jan":{'1':['10/01', '10/05', 'matricula'], 2:['10/01', '10/05', 'matricula'], 3:['10/01', '10/05', 'matricula']},
  "fev":  {1:['10/01', '10/05', 'matricula'], 2:['10/01', '10/05', 'matricula'], 3:['10/01', '10/05', 'matricula']},
  "mai": {1:['10/01', '10/05', 'matricula'], 2:['10/01', '10/05', 'matricula'], 3:['10/01', '10/05', 'matricula'], 4:['10/01', '10/05', 'matricula'] }
}
jan = {}
for i in rowDoBanco:
	jan [len(i)] = i[0],i[1],i[2]
x = thisdict.get("jan")
print(x.get('1')[0],x.get('1')[1], x.get('1')[2] )

print('------------')

x = thisdict.get("mai")
print(x)

print('------------')

x = thisdict.get("fev")
print(x)

-----------------------------------
cars = (10, "Ford", "Volvo", "BMW"),(2, "x", "y", "z"), (3, "p", "o","i")

x = 0
listMeses = {
				'jan':'j',
                'fev':'f'
            }
jan ={}
for i in cars:
	jan[i[0]] = i[0],i[1],i[2],i[3]
    
listMeses['jan'] = jan    
c = listMeses.get('jan')
print(c)
for j in c:
	#print(j)
    print(c.get(j)[0],c.get(j)[1], c.get(j)[2])
print('----------')
print(c.get(10)[0],c.get(10)[1], c.get(10)[2] )
print(listMeses)

==========================
cars = (10, "Ford", "Volvo", "BMW"),(2, "/12/", "y", "z"), (3, "/07/", "o","i"),  (31, "/09/", "o","i"),

x = 0
listMeses = {
				'jan':None,
                'fev':None
            }
print(listMeses)
jan ={}
for i in cars:
	jan[i[0]] = i[0],i[1][1:3],i[2],i[3]
    
listMeses['jan'] = jan    
c = listMeses.get('jan')
print(c)
for j in c:
	#print(j)
    print(c.get(j)[0],c.get(j)[1], c.get(j)[2])
print('----------')
print(c.get(10)[0],c.get(10)[1], c.get(10)[2] )
print(listMeses)
==========================
------------------------------------
"""

