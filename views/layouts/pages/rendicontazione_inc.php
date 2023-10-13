<!-- Main page content-->
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

                    $giorno=array('','lunedì','martedì','mercoledì','giovedì','venerdì','sabato','domenica');
                    return $giorno[$num_gg];
                }


                echo '<span class="font-weight-500 text-primary">'.giorno(date('Y-m-d')).'</span>';
                echo ' &middot; ' . date('d/m/Y') . ' &middot; ' . date('H:i', strtotime('+2 hours'));

                $date = date_create(date('Y-m-d'));
                $date1 = date_sub($date, date_interval_create_from_date_string('7 days'));
                $date1 = date_format($date1, 'Y-m-d');

                echo '<input type="hidden" id="date1" name="date1" value="' . $date1 . '" />';
                echo '<input type="hidden" id="date2" name="date2" value="' . date('Y-m-d') . '" />';

                ?>
            </div>
            <div class="datepicker-trigger ">
                <input class="btn-primary" type="button" id="trigger-range" placeholder="Seleziona un range" :value="date1 + ' / ' + date2">
                <airbnb-style-datepicker
                        :trigger-element-id="'trigger-range'"
                        :mode="'range'"
                        :date-one="date1"
                        :date-two="date2"
                        v-on:date-one-selected="function(val) { date1 = val }"
                        v-on:date-two-selected="function(val) { date2 = val }"
                        v-on:apply="cambiaData"></airbnb-style-datepicker>
            </div>
        </div>

    </div>

    <div class="card card-header-actions mx-auto">
        <div class="card-header">
            <img src="assets/img/icons/dollar.svg" height="32">&nbsp;RENDICONTAZIONI COMMESSE<b-button class="align-content-sm-center text-uppercase" variant="primary" size="sm" @click="esportaRendicontazioni">Esporta</b-button>
        </div>
        <div class="card-body " >
            <!-- Tabella con la lista delle commesse -->
            <div class="datatable">
                <b-col lg="12" class="my-lg-4">
                    <b-form-group
                            :label="filter"
                            label-for="filter-input"
                            label-cols-sm="3"
                            label-align-sm="right"
                            label-size="sm"
                            class="mb-0"
                    >
                        <b-input-group size="sm">
                            <b-form-input
                                    id="filter-input"
                                    v-model="filter"
                                    type="search"
                                    placeholder="Type to Search"
                            ></b-form-input>
                            <b-input-group-append>
                                <b-button :disabled="!filter" @click="filter = ''">Annulla</b-button>
                            </b-input-group-append>
                        </b-input-group>
                    </b-form-group>
                </b-col>
                <b-table id="table" striped bordered small hover :items="rendicontazioniList" :fields="fields_rendicontazioni"  :current-page="currentPage"
                         :per-page="perPage"  @filtered="onFiltered" :filter="filter" sort-icon-left :busy="isBusy">
                    <template #table-busy>
                        <div class="text-center text-danger my-2">
                            <b-spinner class="align-middle"></b-spinner>
                            <strong>Loading...</strong>
                        </div>
                    </template>
                    <template #cell(data)="item">
                        <b class="text-primary">{{ item.value.toUpperCase() }}</b>
                    </template>
                    <template #cell(num_ore)="row">
                        <b class="text-secondary">{{ row.value.toUpperCase() }}</b>
                    </template>
                    <template #cell(chiusa)="record" >
                        <div v-if="record.item.chiusa === '0'" class="badge badge-success badge-pill">SI</div>
                        <div v-if="record.item.chiusa === '1'" class="badge badge-red-soft badge-pill">NO</div>
                    </template>


                </b-table>
                <b-col sm="7" md="3" class="my-1">
                    <b-pagination
                            v-model="currentPage"
                            :total-rows="totalRows"
                            :per-page="perPage"
                            align="fill"
                            size="sm"
                            class="my-0"
                    ></b-pagination>
                </b-col>
            </div>
        </div>
    </div>


</div>