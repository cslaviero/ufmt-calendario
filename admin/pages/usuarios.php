<?php
    require "../../app/src/conecta.php";

    $sql = "SELECT * FROM tbl_usuarios;";
    $exe = $Conn->query($sql);
    $num = $exe->num_rows;
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Calendário UFMT - Usuários</title>

    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="../vendor/datatables-plugins/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="../vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

<div id="wrapper">

    <?php include "navegacao.php"; ?> <!-- Nav com topo e menu lateral -->

    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Usuários
                    <a role="button" data-toggle="modal" data-target="#ins_usuario" class="btn btn-primary" style="float: right;">Inserir usuários</a>
                </h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Todos os usuários
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                            <tr>
                                <th>Usuários</th>
                                <th>Ações</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                                $i = 0;
                                while($reg = $exe->fetch_array()){
                            ?>
                            <tr class="<?php if($i % 2 == 0){ echo "odd"; } else { echo "even"; }?> gradeX">
                                <td><?=$reg['usu_nome']?></td>
                                <td class="center" style="min-width: 70px;">
                                    <a role="button" title="Editar" data-toggle="modal" data-target="#alt_usuario" class="btn btn-info btn-circle" onclick="altera(<?=$reg['usu_id']?>);"><i class="fa fa-pencil"></i></a>
                                    <a role="button" title="Excluir" data-toggle="modal" data-target="#del_usuario" class="btn btn-danger btn-circle" onclick="deleta(<?=$reg['usu_id']?>);"><i class="fa fa-times"></i></a>
                                </td>
                            </tr>
                            <?php $i++; } ?>
                            </tbody>
                        </table>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->

<?php include "form_usuarios.php"; ?> <!-- Modal de cadastro de usuários -->

<!-- jQuery -->
<script src="../vendor/jquery/jquery.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="../vendor/metisMenu/metisMenu.min.js"></script>

<!-- DataTables JavaScript -->
<script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
<script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
<script src="../vendor/datatables-responsive/dataTables.responsive.js"></script>

<!-- Custom Theme JavaScript -->
<script src="../dist/js/sb-admin-2.js"></script>

<!-- Funções do sistema da API -->
<script src="../dist/js/usuarios.js"></script>

<!-- Page-Level Demo Scripts - Tables - Use for reference -->
<script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true
        });
    });
</script>

</body>

</html>