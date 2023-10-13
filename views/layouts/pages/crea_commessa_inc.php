<!-- Main page content-->
<div id="app" class="container-fluid">
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
            {{titolo}}
            <button class="btn btn-teal btn-sm" v-on:click="nuovaCommessa"><i class="fas fa-file"></i>&nbsp;&nbsp;Nuova Commessa</button>
        </div>
        <div class="card-body " >

            <div class="tab-content" id="cardTabContent">
                <div class="tab-pane fade show active" id="tabpanel1" role="tabpanel" aria-labelledby="example-tab">
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
                        <b-table id="table" striped bordered small hover :items="commesseList" :fields="fields_commesse"  :current-page="currentPage"
                                 :per-page="perPage"  @filtered="onFiltered" :filter="filter" sort-icon-left :busy="isBusy">
                            <template #table-busy>
                                <div class="text-center text-danger my-2">
                                    <b-spinner class="align-middle"></b-spinner>
                                    <strong>Loading...</strong>
                                </div>
                            </template>
                            <template #cell(codice)="data">
                                <b class="text-primary">{{ data.value.toUpperCase() }}</b>
                            </template>
                            <template #cell(chiusa)="record" >
                                <div v-if="record.item.chiusa === '0'" class="badge badge-success badge-pill">SI</div>
                                <div v-if="record.item.chiusa === '1'" class="badge badge-red-soft badge-pill">NO</div>
                            </template>
                            <template #cell(assegnata)="row1" >
                                <div v-if="(!row1.item.teams.length)" class="badge badge-red-soft badge-pill">NON ASSEGNATA!</div>
                                <div v-else>
                                    <div v-for="team of row1.item.teams">
                                        <div v-if="team.sigla === 'AL'" class="badge badge-pink badge-pill">AL</div>
                                        <div v-if="team.sigla === 'EC'" class="badge badge-teal badge-pill">EC</div>
                                        <div v-if="team.sigla === 'MO'" class="badge badge-yellow badge-pill">MO</div>
                                        <div v-if="team.sigla === 'NC'" class="badge badge-primary badge-pill">NC</div>
                                        <div v-if="team.sigla === 'TR'" class="badge badge-secondary badge-pill">TR</div>
                                    </div>
                                </div>
                            </template>
                            <template #cell(Azioni)="row" >
                                <b-button size="sm" v-on:click="editCommessa(row.item)" variant="link" v-b-tooltip.hover title="Modifica Commessa"><i class="fas fa-edit"></i></b-button>
                                <b-button size="sm" v-if="row.item.rendicontata==='0'" v-on:click="eliminaCommessa(row.item)" variant="link"  v-b-tooltip.hover title="Cancella Commessa"><i class="fas fa-trash"></i></b-button>
                                <b-button size="sm" v-on:click="estendiCommessa(row.item)" variant="link" v-b-tooltip.hover title="Estendi Commessa"><i class="fas fa-code-branch"></i></b-button>
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
                <div class="tab-pane fade " id="tabpanel2" role="tabpanel" aria-labelledby="example-tab">
                    <!-- Form per l'inserimento di una commessa -->
                    <form v-on:submit.prevent="salvaCommessa">
                        <div class="form-row">
                            <div class="form-group col-md-2">
                                <label for="codice">Codice</label>
                                <b-input-group size="sm">
                                    <b-form-input v-model="codice" id="codice" readonly></b-form-input>
                                </b-input-group>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="anno">Anno</label>
                                <b-input-group size="sm">
                                    <b-form-input v-model="anno" id="anno" readonly></b-form-input>
                                </b-input-group>
                            </div>
                            <div class="form-group col-md-8">
                                <label for="cliente">Cliente</label>
                                <b-input-group size="sm">
                                    <b-form-input v-model="cliente" required></b-form-input>
                                    <b-input-group-append>
                                        <b-button size="sm" text="Button" variant="blue" :disabled="!!!cliente" v-on:click="cercaCliente">Cerca</b-button>
                                    </b-input-group-append>
                                </b-input-group>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="codice">Localizzazione</label>
                                <b-input-group size="sm">
                                    <b-form-input v-model="localizzazione" required></b-form-input>
                                </b-input-group>
                            </div>
                            <div class="form-group col-md-8">
                                <label for="tipo_lavoro">Tipo di Lavoro</label>
                                <b-input-group size="sm">
                                    <b-form-input v-model="tipo_lavoro" required></b-form-input>
                                    <b-input-group-append>
                                        <b-button size="sm" text="Button" variant="blue" :disabled="!!!tipo_lavoro" v-on:click="cercaTipoLavoro">Cerca</b-button>
                                    </b-input-group-append>
                                </b-input-group>
                            </div>
                        </div>
                        <div>&nbsp;</div>
                        <div class="form-row">
                            <div class="form-group col-md-2">
                                <b-form-checkbox
                                v-model="chiusa"
                                value="1"
                                unchecked-value="0"
                                >
                                   CHIUSA
                                </b-form-checkbox>
                            </div>
                            <div class="form-group col-md-10" >

                                <b-button type="submit" size="sm" variant="success" style="float: right">SALVA</b-button>
                                <span style="float: right">&nbsp;</span>
                                <b-button type="button" size="sm" variant="warning" style="float: right" v-on:click="annullaCommessa">ANNULLA</b-button>


                            </div>
                        </div>
                        <div>&nbsp;</div>
                        <hr class="border-cyan" />
                        <div class="form-row">

                            <b-form-group label="Team al quale assegnare la commessa:" v-slot="{ ariaDescribedby }">
                                <b-form-checkbox-group
                                        id="checkbox-group-1"
                                        v-model="selected_teams"
                                        :options="options_teams"
                                        :aria-describedby="ariaDescribedby"
                                        name="flavour-1"
                                ></b-form-checkbox-group>
                            </b-form-group>

                        </div>





                    </form>
                </div>
            </div>



        </div>
    </div>

    <!-- MODAL PER L'ISERIMENTO DEL CLIENTE -->
    <div class="modal fade bd-example-modal-xl" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true" id="modalClienti">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1>Inserisci un Cliente precedentemente registrato</h1>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" style="color: #9c0e00"><i class="fas fa-window-close"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Extra large modal -->
                    <div v-if="loading_cli" class="d-flex justify-content-center">
                        <div class="spinner-border" role="status" >
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <div v-else>
                        <div class="card">
                            <div class="card-body">
                                <div class="datatable">
                                    <b-table id="table_for" striped bordered small hover :items="clientiList" :fields="fields_table_cli"  :current-page="currentPage_cli"
                                             :per-page="perPage_cli">
                                        <template #cell(cliente)="row" >
                                            <b-link href="#" v-on:click="insertCliente(row.item)">{{row.item.cliente}}</b-link>
                                        </template>
                                    </b-table>
                                    <b-col sm="7" md="3" class="my-1">
                                        <b-pagination
                                                v-model="currentPage_cli"
                                                :total-rows="totalRows_cli"
                                                :per-page="perPage_cli"
                                                align="fill"
                                                size="sm"
                                                class="my-0"
                                        ></b-pagination>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL PER L'ISERIMENTO DEL TIPO LAVORO -->
    <div class="modal fade bd-example-modal-xl" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true" id="modalTipiLavoro">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1>Inserisci un Tipo di Lavoro precedentemente registrato</h1>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" style="color: #9c0e00"><i class="fas fa-window-close"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Extra large modal -->
                    <div v-if="loading_tip" class="d-flex justify-content-center">
                        <div class="spinner-border" role="status" >
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <div v-else>
                        <div class="card">
                            <div class="card-body">
                                <div class="datatable">
                                    <b-table id="table_for" striped bordered small hover :items="tipiLavoroList" :fields="fields_table_tip"  :current-page="currentPage_tip"
                                             :per-page="perPage_tip">
                                        <template #cell(tipo_lavoro)="row" >
                                            <b-link href="#" v-on:click="insertTipoLavoro(row.item)">{{row.item.tipo_lavoro}}</b-link>
                                        </template>
                                    </b-table>
                                    <b-col sm="7" md="3" class="my-1">
                                        <b-pagination
                                                v-model="currentPage_tip"
                                                :total-rows="totalRows_tip"
                                                :per-page="perPage_tip"
                                                align="fill"
                                                size="sm"
                                                class="my-0"
                                        ></b-pagination>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal DELETE-->
    <div class="modal fade" data-backdrop="static" id="ModalDelete" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLongTitle">Cancellazione Commessa</h5>
                </div>
                <div class="modal-body">
                    <div>
                        <p>Stai per cancellare la Commessa n.<strong>&nbsp;{{CodiceCommessaDaEliminare}}</strong>.<br>Per continuare premere 'OK'.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
                    <a type="button" class="btn btn-success" data-dismiss="modal" v-on:click="deleteCommessa(id_carico)">OK</a>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal SAVE-->
    <div class="modal fade" data-backdrop="static" id="ModalSave" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLongTitle">Salvataggio dati</h5>
                </div>
                <div class="modal-body">
                    <div v-if="loading_save" class="d-flex justify-content-center">
                        <div class="spinner-border" role="status" >
                            <span class="sr-only">Attendere...</span>
                        </div>
                    </div>
                    <div v-else id="save_response">
                        <p>{{save_response}}</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" v-on:click="chiudiSalva">OK</button>
                </div>
            </div>
        </div>
    </div>


</div>
