<?php
require_once('views/config/config.php');
require_once('views/inc/menuPoint.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Gestione Dati INC Ambiente e Territorio Srl" />
    <meta name="author" content="Beppe Merlino" />
    <title>DMS - Data Management System</title>

    <link rel="icon" href="https://www.incaet.it/wp-content/uploads/2019/05/cropped-thumbnail_Logo_INC-1-192x192.png" sizes="192x192" />


    <script src="https://momentjs.com/downloads/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.27.0/feather.min.js" crossorigin="anonymous"></script>
    <link type="text/css" rel="stylesheet" href="node_modules/bootstrap-vue/dist/bootstrap-vue.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" />

    <link href="node_modules/quill/dist/quill.snow.css" rel="stylesheet">

    <link href="https://cdn3.devexpress.com/jslib/21.2.7/css/dx-diagram.min.css" rel="stylesheet">
    <link href="https://cdn3.devexpress.com/jslib/21.2.7/css/dx-gantt.min.css" rel="stylesheet">
    <link href="https://cdn3.devexpress.com/jslib/21.2.7/css/dx.light.css" rel="stylesheet">

    <link href="css/styles.css" rel="stylesheet" />
    <link rel="stylesheet" href="node_modules/vue-airbnb-style-datepicker/dist/vue-airbnb-style-datepicker.min.css">

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet"/>



    <link rel="stylesheet" href="node_modules/vue-advanced-cropper/dist/style.css" />
    <style>
        .upload-example {
            margin-top: 20px;
            margin-bottom: 20px;
            user-select: none;
            border: solid 1px #eee;
            min-height: 300px;
            max-height: 500px;
            width: 100%;
        }


        .flip-list-move {
            transition: transform 0.5s;
        }
        .no-move {
            transition: transform 0s;
        }
        .ghost {
            opacity: 0.5;
            background: #c8ebfb;
        }
        .list-group {
            min-height: 20px;
        }
        .list-group-item {
            cursor: move;
        }
        .list-group-item i {
            cursor: pointer;
        }
    </style>


</head>
<body class="nav-fixed">
<?php include('views/inc/navbar.php'); ?>
<div id="layoutSidenav" >
    <?php include('views/inc/sidebar.php'); ?>
    <div id="layoutSidenav_content">
        <main>
            <?php

            $utente_autorizzato = false;
            $flag = false;
            foreach ($array_menupoint as $item) {

                if($menupoint == $item['pagina']){
                    foreach ($item['gruppi'] as $gruppo) {

                        if ($_SESSION["NOME.GRUPPO"] == $gruppo) {
                            $utente_autorizzato = true;
                        }

                    }
                }

                if ($menupoint == $item['pagina'] && $utente_autorizzato && $menupoint != '') {
                    include'pages/'.$item['pagina'].'_inc.php';
                    $flag = true;
                }


            }
            if (!$flag && $utente_autorizzato) {

                include 'pages/template_inc.php';

            }
            if (!$utente_autorizzato) {

                include 'pages/forbidden_inc.php';

            }

            ?>
        </main>
        <footer class="footer footer-light" >
            <div class="container-fluid" >
                <div class="row align-items-center">
                    <div class="col col-lg-4">&nbsp;</div>
                    <div class="col-md-auto">Copyright &copy; INC Ambiente e Territorio SRL <?php echo strval(date('Y')) ?></div>
                    <div class="col col-lg-4">&nbsp;</div>
                </div>
            </div>
        </footer>
    </div>
    <?php include('views/inc/modal_logout.php'); ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/date-fns/1.9.0/date_fns.min.js" integrity="sha512-ToehgZGJmTS39fU8sfP9/f0h2Zo6OeXXKgpdEgzqUtPfE5By1K/ZkD8Jtp5PlfdaWfGVx+Jw5j10h63wSwM1HA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.22/pagination/jPaginator/dataTables.jPaginator.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js" crossorigin="anonymous"></script>
<!-- vue axios -->
<script src="node_modules/axios/dist/axios.min.js"></script>

<script src="node_modules/bootstrap-vue/dist/bootstrap-vue.min.js"></script>
<script src="node_modules/vue-airbnb-style-datepicker/dist/no-dep/vue-airbnb-style-datepicker.min.js"></script>
<script src="node_modules/vue-advanced-cropper/dist/index.umd.js"></script>
<script src="node_modules/vuejs-paginate/dist"></script>
<script src="node_modules/sortablejs/Sortable.min.js"></script>
<script src="node_modules/vuedraggable/dist/vuedraggable.umd.min.js"></script>

<script src="node_modules/quill/dist/quill.js"></script>

<script src="https://cdn3.devexpress.com/jslib/21.2.7/js/dx.all.js"></script>

<script src="script/_navbar.js"></script>
<script src="script/_sidebar.js"></script>

<?php

        $utente_autorizzato = false;

        foreach ($array_menupoint as $item) {

            if($menupoint == $item['pagina']){
                foreach ($item['gruppi'] as $gruppo) {

                    if ($_SESSION["NOME.GRUPPO"] == $gruppo) {
                        $utente_autorizzato = true;
                        echo '<script src="script/'. $item['pagina'] .'.js" ></script>';
                        break;
                    }

                }
            }



        }

?>

<script src="js/scripts.js"></script>
<script src="js/navigate.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js" crossorigin="anonymous"></script>

<script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>


</body>
</html>
