# app/_usuario/views.py
from flask import flash, redirect, render_template, url_for, request
from flask_login import login_required, login_user, logout_user
from . import usuario
from .. _usuario.forms import RegistrationForm, LoginForm
from .. import db
from .. models.models import Usuario
from .. import mysql


@usuario.route('/register', methods=['GET', 'POST'])
def register():
    
    form = RegistrationForm()
    if form.validate_on_submit():
        user = Usuario ( usu_nome=form.name.data, 
                            usu_email=form.email.data,
                            usu_usuario=form.tipo.data, 
                            password=form.password.data)
        #password com segurança werkzeug.security
        db.session.add(user)
        #https://docs.sqlalchemy.org/en/14/orm/declarative_tables.html#declarative-table-configuration
        #add user to the database
        db.session.commit()
        flash('You have successfully registered! You may now login.')

        # redirect to the login page
        return redirect(url_for('usuario.login')) 

    # load registration template
    return render_template('register.html', form=form, title='Register')

@usuario.route('/usuarios', methods=['GET', 'POST'])
def usuarios():
    
    form = RegistrationForm()

    # load registration template
    return render_template('user.html', form=form, title='Register')

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
    """
    Handle requests to the /logout route
    Log an usuario out through the logout link
    """
    logout_user()
    flash('Usuário fez logout.')
    # redirect to the login page
    return redirect(url_for('usuario.login'))