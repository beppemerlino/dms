<!-- Main page content-->
<?php define('DOMAIN', 'http://localhost/dms/') ?>
<div id="app" class="container mt-5">
    <!-- Custom page header alternative example-->
    <div class="d-flex justify-content-between align-items-sm-center flex-column flex-sm-row mb-4">
        <div class="mr-4 mb-3 mb-sm-0">

            <div class="small">
                <?php
                function giorno($d){

                    $d_ex = explode("-", $d);
                    $d_ts = mktime(0,0,0,$d_ex[1],$d_ex[2],$d_ex[0]);
                    $num_gg = (int)date("N",$d_ts);

                    $giorno = array('','lunedì','martedì','mercoledì','giovedì','venerdì','sabato','domenica');
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
            <img src="assets/img/icons/computer.svg" height="32">Workstation
            <b-button size="sm" class="m-2" variant="blue" ><i class="fas fa-fw fa-plus"></i>&nbsp;Nuova Workstation</b-button>
        </div>
        <div class="card-body" >
            <!-- // Filtro tabella -->
            <div class="form-row">
                <div class="col-md-3 ml-auto">
                    <div class="input-group">
                        <input class="form-control " type="search" id="filter" placeholder="Filtro">
                    </div>
                </div>
            </div>

            <div class="form-row">&nbsp;</div>


            <!-- // Tabella delle Workstation-->
            <div class="datatable">
                <b-table id="table" class="text-center" style="font-weight: bold; font-size: 18px;" striped bordered small hover :items="workstation_list" :fields="fields_table_workstation" :current-page="pageNumber"
                         :per-page="rowsInPage"  @filtered="onFiltered" :filter="filter" sort-icon-left :busy="isBusy">
                    <template #table-busy>
                        <div class="text-center text-blue my-2">
                            <b-spinner class="align-middle"></b-spinner>
                        </div>
                    </template>




                    <template #cell(action)="row">

                    </template>

                </b-table>
                <b-col sm="7" md="3" class="my-1">
                    <b-pagination
                        v-model="pageNumber"
                        :total-rows="totalRows"
                        :per-page="rowsInPage"
                        align="fill"
                        size="sm"
                        class="my-0"
                    ></b-pagination>
                </b-col>
            </div>

        </div>
    </div>


    <!-- Modal inserimento modifica Workstation -->

    <div class="modal fade bd-example-modal-xl" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true" id="modalEditSave">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1>{{ titolo_modal }}</h1>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" style="color: #9c0e00"><i class="fas fa-window-close"></i></span>
                    </button>
                </div>



            </div>
        </div>
    </div>




</div>
