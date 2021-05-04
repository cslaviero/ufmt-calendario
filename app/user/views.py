# app/user/views.py

from flask import flash, redirect, render_template, url_for
from flask_login import login_required, login_user, logout_user

from app.user import user
from app.user.forms import RegistrationForm, LoginForm
from app import db
from app.models.models import Usuario


@user.route('/register', methods=['GET', 'POST'])
def register():
    
    form = RegistrationForm()
    if form.validate_on_submit():
        usuario = Usuario ( usu_nome=form.name.data, 
                            usu_email=form.email.data,
                            usu_usuario=form.tipo.data, 
                            password=form.password.data)
        #password com segurança werkzeug.security
        print('------------email------------: ', usuario.usu_email)
        #print('senha: ', usuario.password)
        print('------------aqui------------:', usuario)
        #db.session.add(usuario)
        #https://docs.sqlalchemy.org/en/14/orm/declarative_tables.html#declarative-table-configuration
        # add user to the database
        #db.session.commit()
        flash(u'You have successfully registered! You may now login.')

        # redirect to the login page
        return redirect(url_for('user.login'))

    # load registration template
    return render_template('user/register.html', form=form, title='Register')


@user.route('/login', methods=['GET', 'POST'])
def login():
    """
    Handle requests to the /login route
    Log an user in through the login form
    """
   
    form = LoginForm()
    if form.validate_on_submit():
        print("'---------------subimetido----------------'")
        # check whether user exists in the database and whether
        # the password entered matches the password in the database
        user = Usuario.query.filter_by(usu_email=form.email.data).first()
        
        if user is not None and user.verify_password(
                form.password.data):
            # log user in
            login_user(user)

            # redirect to the dashboard page after login
            return redirect(url_for('home.dashboard'))

        # when login details are incorrect
        else:
            flash('Invalid email or password.')
        
    # load login template
    return render_template('user/login.html', form=form, title='Login')
    #return '<h2> olá login</h2>'

@user.route('/logout')
#login_manager.login_message = "Você precisa estar"
#@login_required
def logout():
    """
    Handle requests to the /logout route
    Log an user out through the logout link
    """
    logout_user()
    flash('You have successfully been logged out.')

    # redirect to the login page
    return redirect(url_for('user.login'))