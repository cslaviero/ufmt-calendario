<!-- Navigation -->
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="inicio.php">Calendário UFMT</a>
    </div>
    <!-- /.navbar-header -->

    <ul class="nav navbar-top-links navbar-right">
        <!-- /.dropdown -->
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
                <li><a href="usuarios.php?perfil"><i class="fa fa-user fa-fw"></i> Perfil do usuário</a>
                </li>
                <li class="divider"></li>
                <li><a href="#" onclick="logout();"><i class="fa fa-sign-out fa-fw"></i> Sair</a>
                </li>
            </ul>
            <!-- /.dropdown-user -->
        </li>
        <!-- /.dropdown -->
    </ul>
    <!-- /.navbar-top-links -->

    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
                <li>
                    <a href="inicio.php"><i class="fa fa-home fa-fw"></i> Início</a>
                </li>
                <li>
                    <a href="#"><i class="fa fa-calendar fa-fw"></i> Eventos<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="eventos.php">Listar</a>
                        </li>
                        <li>
                            <a href="eventos.php?insere">Inserir</a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>

                <li>
                    <a href="#"><i class="fa fa-tags fa-fw"></i> Categorias<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="categorias.php">Listar</a>
                        </li>
                        <li>
                            <a href="categorias.php?insere">Inserir</a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>

                <li>
                    <a href="#"><i class="fa fa-mortar-board fa-fw"></i> Períodos<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="periodos.php">Listar</a>
                        </li>
                        <li>
                            <a href="periodos.php?insere">Inserir</a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>

                <li>
                    <a href="#"><i class="fa fa-university fa-fw"></i> Campus<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="campus.php">Listar</a>
                        </li>
                        <li>
                            <a href="campus.php?insere">Inserir</a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>

                <li>
                    <a href="#"><i class="fa fa-users fa-fw"></i> Usuários<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="usuarios.php">Listar</a>
                        </li>
                        <li>
                            <a href="usuarios.php?insere">Inserir</a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
        </div>
        <!-- /.sidebar-collapse -->
    </div>
    <!-- /.navbar-static-side -->
</nav>