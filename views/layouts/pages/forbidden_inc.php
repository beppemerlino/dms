<!-- Main page content-->
<div class="container mt-5">
    <!-- Custom page header alternative example-->
    <div class="d-flex justify-content-between align-items-sm-center flex-column flex-sm-row mb-4">
        <div class="mr-4 mb-3 mb-sm-0">

            <div class="small">
                <?php
                function giorno($d){

                    $d_ex = explode("-", $d);
                    $d_ts = mktime(0,0,0,$d_ex[1],$d_ex[2],$d_ex[0]);
                    $num_gg = (int)date("N",$d_ts);

                    $giorno=array('','lunedì','martedì','mercoledì','giovedì','venerdì','sabato','domenica');
                    return $giorno[$num_gg];
                }


                echo '<span class="font-weight-500 text-primary">'.giorno(date('Y-m-d')).'</span>';
                echo ' &middot; ' . date('d/m/Y') . ' &middot; ' . date('H:i');
                ?>
            </div>
        </div>

    </div>
    <div class="card card-header-actions mx-auto">
        <div class="card-header">
            INC AMBIENTE E TERRITORIO Srl
        </div>
        <div class="card-body " >
            <div class="container">
                <div class="row">
                    <div class="col-sm">
                        &nbsp;
                    </div>
                    <div class="col-sm" style="text-align: center">
                        <h3><b>Spiacenti!</b><br><br>Non sei autorizzato a visualizzare questa pagina!</h3>
                    </div>
                    <div class="col-sm">
                        &nbsp;
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>