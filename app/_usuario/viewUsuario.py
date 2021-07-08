# app/_usuario/views.py
from flask import flash, redirect, render_template, url_for, request
from flask_login import login_required, login_user, logout_user, current_user
from . import usuario
from .. _usuario.formsUsuario import RegistrationForm, LoginForm
from .. import db
from .. models.models import Usuario, Permissoes, Comentario
from .. import mysql

@usuario.route('/usuarios')
@login_required
def usuarios():

    form = RegistrationForm()
    cur = mysql.get_db().cursor()
    sql = 'SELECT * FROM tbl_usuarios'
    cur.execute(sql)
    rows = cur.fetchall()
    cur.close()

    permissoes = Permissoes.query.all()

    auxId = current_user.get_id()
    prmEvento = Permissoes.query.filter_by(prm_usuario = auxId).filter_by(prm_item_permitido = 1).first ()
    prmCategoria = Permissoes.query.filter_by(prm_usuario = auxId).filter_by(prm_item_permitido = 2).first ()
    prmPeriodo = Permissoes.query.filter_by(prm_usuario = auxId).filter_by(prm_item_permitido = 3).first ()
    prmUsuario = Permissoes.query.filter_by(prm_usuario = auxId).filter_by(prm_item_permitido = 4).first ()

    return render_template('user.html', form= form, usuarios= rows, permissoes= permissoes, prmEvento= prmEvento, prmCategoria= prmCategoria, prmPeriodo= prmPeriodo, prmUsuario= prmUsuario, title= "Usuários")

@usuario.route('/insertUser', methods=['GET', 'POST'])
@login_required
def insertUser():

    form = RegistrationForm()
    try:
        usuario = Usuario ( usu_nome=form.name.data,
            usu_email=form.email.data,
            usu_usuario=form.tipo.data,
            password=form.password.data)
        #password com segurança werkzeug.security
        db.session.add(usuario)
        db.session.commit()

        usu = Usuario.query.filter_by(usu_nome = form.name.data).first()
        usu.usu_id
        inserir=0
        alterar=0
        deletar=0
        if form.inscat.data:#verifica o checkbok marcado
            inserir=1
        if form.altcat.data:#verifica o checkbok marcado
            alterar=1
        if form.delcat.data:#verifica o checkbok marcado
            deletar=1
        permissoes = Permissoes(usu.usu_id, 1, inserir, alterar, deletar)
        db.session.add(permissoes)
        db.session.commit()

        inserir=0
        alterar=0
        deletar=0
        if form.inseve.data:#verifica o checkbok marcado
            inserir=1
        if form.alteve.data:#verifica o checkbok marcado
            alterar=1
        if form.deleve.data:#verifica o checkbok marcado
            deletar=1
        permissoes = Permissoes(usu.usu_id, 2, inserir, alterar, deletar)
        db.session.add(permissoes)
        db.session.commit()

        inserir=0
        alterar=0
        deletar=0
        if form.insprd.data:#verifica o checkbok marcado
            inserir=1
        if form.altprd.data:#verifica o checkbok marcado
            alterar=1
        if form.delprd.data:#verifica o checkbok marcado
            deletar=1
        permissoes = Permissoes(usu.usu_id, 3, inserir, alterar, deletar)
        db.session.add(permissoes)
        db.session.commit()

        inserir=0
        alterar=0
        deletar=0
        if form.insusu.data:#verifica o checkbok marcado
            inserir=1
        if form.altusu.data:#verifica o checkbok marcado
            alterar=1
        if form.delusu.data:#verifica o checkbok marcado
                deletar=1
        permissoes = Permissoes(usu.usu_id, 4, inserir, alterar, deletar)
        db.session.add(permissoes)
        db.session.commit()

        flash('Cadastro do usuário realizado com sucesso', 'success')
    except Exception as e:
        flash('Cadastro do usuário não realizado' 'error')
    finally:
        return redirect(url_for('usuario.usuarios'))

@usuario.route('/login', methods=['GET', 'POST'])
def login():

    form = LoginForm()#metodo com os inputs do formulário
    if form.validate_on_submit():#condição de validação dos inputs
        user = Usuario.query.filter_by(usu_usuario=form.tipo.data).first()# busca o usuário no banco de dados
        if user is None:#verifica se o usuário existe no banco de dados
            flash('Usuário não encontrado!')# se usuário não existe, msg de aviso ao usuário.
            # Usuário redirecionado para a página login
        elif user is not None and user.verify_password(form.password.data):#verifica se os atributos do usuário existe no banco de dados
        #Se usúario e senha existe no mesmo objeto
            if form.remember.data:#verifica o checkbok marcado
                 login_user(user, remember=True, duration=timedelta(days=1))#Seta o usuário na sessão por um dia
            else: #o checkbok desmarcado
                login_user(user, remember=False)#Seta o usuário na sessão até fechar o navegador
            return redirect(url_for('inicio.listaInicio')) #usuário logado e redirecionado para a página inicial
        else:
            #Usuário existe, mas senha não está correta
            flash('Senha inválida!')#redirecionado para a página login com a msg
    return render_template('login.html', form=form, title='Login')#renderiza a página login

@usuario.route('/logout')
def logout():

    logout_user() #retira o usuário da session 
    flash('Usuário fez logout.')
    # redireciona para a página login
    return redirect(url_for('usuario.login'))

@usuario.route('/updateUsuario/<int:idUsu>', methods=['POST'])
@login_required
def updateUsuario(idUsu):

    form = RegistrationForm()
    if request.method == 'POST':
        try:
            usuario = Usuario.query.filter_by(usu_id = idUsu).first()
            usuario.usu_nome = request.form['nome']
            usuario.usu_email = request.form['email']
            usuario.usu_usuario = request.form['usuario']
            if request.form['senha'] != '5xxxxx':
                usuario.password = request.form['senha']
            db.session.commit()

            auxId = current_user.get_id()
            alterarUser = Permissoes.query.filter_by(prm_usuario = auxId).filter_by(prm_item_permitido = 4).first ()

            if alterarUser.prm_alterar == 1:
                inserir=0
                alterar=0
                deletar=0
                if form.inscat.data:#verifica o checkbok marcado
                    inserir=1
                if form.altcat.data:#verifica o checkbok marcado
                    alterar=1
                if form.delcat.data:#verifica o checkbok marcado
                    deletar=1
                permissoes = Permissoes.query.filter_by(prm_usuario = idUsu).filter_by(prm_item_permitido = 1).first()
                permissoes.prm_inserir = inserir
                permissoes.prm_alterar = alterar
                permissoes.prm_deletar = deletar
                db.session.commit()

                inserir=0
                alterar=0
                deletar=0
                if form.inseve.data:#verifica o checkbok marcado
                    inserir=1
                if form.alteve.data:#verifica o checkbok marcado
                    alterar=1
                if form.deleve.data:#verifica o checkbok marcado
                    deletar=1
                permissoes = Permissoes.query.filter_by(prm_usuario = idUsu).filter_by(prm_item_permitido = 2).first()
                permissoes.prm_inserir = inserir
                permissoes.prm_alterar = alterar
                permissoes.prm_deletar = deletar
                db.session.commit()

                inserir=0
                alterar=0
                deletar=0
                if form.insprd.data:#verifica o checkbok marcado
                    inserir=1
                if form.altprd.data:#verifica o checkbok marcado
                    alterar=1
                if form.delprd.data:#verifica o checkbok marcado
                    deletar=1
                permissoes = Permissoes.query.filter_by(prm_usuario = idUsu).filter_by(prm_item_permitido = 3).first()
                permissoes.prm_inserir = inserir
                permissoes.prm_alterar = alterar
                permissoes.prm_deletar = deletar
                db.session.commit()

                inserir=0
                alterar=0
                deletar=0
                if form.insusu.data:#verifica o checkbok marcado
                    inserir=1
                if form.altusu.data:#verifica o checkbok marcado
                    alterar=1
                if form.delusu.data:#verifica o checkbok marcado
                    deletar=1
                permissoes = Permissoes.query.filter_by(prm_usuario = idUsu).filter_by(prm_item_permitido = 4).first()
                permissoes.prm_inserir = inserir
                permissoes.prm_alterar = alterar
                permissoes.prm_deletar = deletar
                db.session.commit()
            flash('Alteração do usuário realizada com sucesso', 'success')
        except Exception as e:
            flash('Alteração do usuário não realizada', 'error')
        finally:
            # redireciona para a página de períodos
            return redirect(url_for('usuario.usuarios'))

@usuario.route('/deleteUsuario/<int:idUsu>', methods=['POST'])
@login_required
def deleteUsuario(idUsu):

    if request.method == 'POST':
        try:
            #deleta todas permissões do usuário selecionado
            Permissoes.query.filter_by(prm_usuario = idUsu).delete()
            db.session.commit()
            #deleta o usuário já sem permissões
            Usuario.query.filter_by(usu_id = idUsu).delete()
            db.session.commit()
            flash('Usuário deletado com sucesso', 'success')
        except Exception as e:
            flash('Usuário não deletado', 'error')
        finally:
            # redireciona para a página de períodos
            return redirect(url_for('usuario.usuarios'))

@usuario.route('/comentarios')
@login_required
def comentarios():

    coment = Comentario.query.all()
    auxId = current_user.get_id()
    prmEvento = Permissoes.query.filter_by(prm_usuario = auxId).filter_by(prm_item_permitido = 1).first ()
    prmCategoria = Permissoes.query.filter_by(prm_usuario = auxId).filter_by(prm_item_permitido = 2).first ()
    prmPeriodo = Permissoes.query.filter_by(prm_usuario = auxId).filter_by(prm_item_permitido = 3).first ()
    prmUsuario = Permissoes.query.filter_by(prm_usuario = auxId).filter_by(prm_item_permitido = 4).first ()

    return render_template('comentarios.html', coment= coment, prmEvento= prmEvento, prmCategoria= prmCategoria, prmPeriodo= prmPeriodo, prmUsuario= prmUsuario, title= "Comentários")