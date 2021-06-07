# app/_usuario/views.py
from flask import flash, redirect, render_template, url_for, request
from flask_login import login_required, login_user, logout_user
from . import usuario
from .. _usuario.formsUsuario import RegistrationForm, LoginForm
from .. import db
from .. models.models import Usuario
from .. import mysql

@usuario.route('/usuarios', methods=['GET', 'POST'])
def usuarios():

    form = RegistrationForm()
    cur = mysql.get_db().cursor()
    sql = 'SELECT * FROM tbl_usuarios'
    cur.execute(sql)
    rows = cur.fetchall()
    cur.close()
    return render_template('user.html', form= form, usuarios= rows, title= "Usuários")

@usuario.route('/insertUser', methods=['GET', 'POST'])
def insertUser():

    form = RegistrationForm()
    try:
        if form.validate_on_submit():
            usuario = Usuario ( usu_nome=form.name.data, 
                usu_email=form.email.data,
                usu_usuario=form.tipo.data, 
                password=form.password.data)
            #password com segurança werkzeug.security
            db.session.add(usuario)
            #https://docs.sqlalchemy.org/en/14/orm/declarative_tables.html#declarative-table-configuration
            #add user to the database
            db.session.commit()
            flash('Cadastro do usuário realizado com sucesso', 'success')
        else:
            flash('Preencha corretamente', 'error')
            flash('Nome inserido: '+form.name.data+' ou ', 'error')
            flash('Email inserido: '+form.email.data, 'error')
            flash('Cadastro do usuário não realizado: '+form.email.data, 'error')
    except Exception as e:
        flash('Cadastro do usuário não realizado: '+form.email.data, 'error')
    finally:
        return redirect(url_for('usuario.usuarios'))

@usuario.route('/login', methods=['GET', 'POST'])
def login():

    form = LoginForm()#metodo com os inputs do formulário
    if form.validate_on_submit():#condição de validação dos inputs
        user = Usuario.query.filter_by(usu_email=form.email.data).first()# busca o usuário no banco de dados
        if user is None:#verifica se o usuário existe no banco de dados
            flash('Email não encontrado!')# usuário não existe, então email também não.
            # Usuário redirecionado para a página login
        elif user is not None and user.verify_password(form.password.data):#verifica se os atributos do usuário existe no banco de dados
        #Se usúario e senha existe no mesmo objeto
            if form.remember.data:#verifica o checkbok marcado
                 login_user(user, remember=True, duration=timedelta(days=1))#Seta o usuário na sessão por um dia
            else: #o checkbok desmarcado
                login_user(user, remember=False)#Seta o usuário na sessão até fechar o navegador
            return redirect(url_for('home.homepage')) #usuário logado e redirecionado para a página inicial
        else:
            #Usuário existe, mas senha não está correta
            flash('Senha inválida!')#redirecionado para a página login com a msg
    return render_template('login.html', form=form, title='Login')#renderiza a página login

@usuario.route('/logout')
@login_required
def logout():

    logout_user() #retira o usuário da session 
    flash('Usuário fez logout.')
    # redireciona para a página login
    return redirect(url_for('usuario.login'))