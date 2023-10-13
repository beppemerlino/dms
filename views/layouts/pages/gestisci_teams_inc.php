<!-- Main page content-->
<?php define('DOMAIN', 'http://localhost:8099/dms/') ?>

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
                echo ' &middot; ' . date('d/m/Y') . ' &middot; ' . date('H:i');
                ?>
            </div>
        </div>

    </div>

    <div class="card card-header-actions mx-auto">
        <div class="card-header">
            <img src="assets/img/icons/team.svg" height="32">Teams
            <b-button size="sm" class="m-2" variant="teal" v-on:click="insertTeam"><i class="fas fa-fw fa-plus"></i>&nbsp;Nuovo team</b-button>
        </div>
        <div class="card-body" >
            <!-- // Tabella degli utenti attivi     -->
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
            <div class="datatable">
                <b-table id="table" striped bordered small hover :items="teams_list" :fields="fields_teams"  :current-page="currentPage"
                         :per-page="perPage"  @filtered="onFiltered" :filter="filter" sort-icon-left :busy="isBusy">
                    <template #table-busy>
                        <div class="text-center text-danger my-2">
                            <b-spinner class="align-middle"></b-spinner>
                            <strong>Loading...</strong>
                        </div>
                    </template>
                    <template #cell(nome_team)="data">
                        <b class="text-primary">{{ data.value.toUpperCase() }}</b>
                    </template>
                    <template #cell(Azioni)="row" >
                        <b-button size="sm" v-on:click="editTeam(row.item)" variant="link" v-b-tooltip.hover title="Modifica Team"><i class="fas fa-edit"></i></b-button>
                        <b-button size="sm" v-on:click="deleteItem(row.item)" variant="link"  v-b-tooltip.hover title="Cancella Utente"><i class="fas fa-trash"></i></b-button>
                        <b-button size="sm" v-on:click="inviaEmail(row.item)" variant="link" v-b-tooltip.hover title="Invia una comunicazione"><i class="fas fa-envelope"></i></b-button>
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

    <!-- Modal DELETE-->
    <div class="modal fade" data-backdrop="static" id="ModalDelete" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLongTitle">Cancellazione Utente</h5>
                </div>
                <div class="modal-body">
                    <div>
                        <p>Cancella Team&nbsp;<strong>&nbsp;{{nometeam}}</strong>.<br><br>Premere "OK" per continuare...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" v-on:click="closeModalDelete">Annulla</button>
                    <a type="button" class="btn btn-success" data-dismiss="modal" v-on:click="deleteTeam">OK</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Aggiungi/Modifica TEAM -->
    <div class="modal fade bd-example-modal-xl" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myMediumModalLabel" aria-hidden="true" id="modalTeam">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="text-uppercase">{{ edit_add }} un Team</h1>
                </div>
                    <div class="modal-body">
                        <div class="tab-content" id="cardTabContent">
                            <!-- TAB-CONTENT        inizio -->
                            <!-- TAB PANEL 1 -->
                            <div class="tab-pane fade show active " id="tabpanel1" role="tabpanel" aria-labelledby="example-tab">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="nome_team">Nome del team</label>
                                        <b-form-input type="text" class="form-control form-control-sm" size="sm" id="nome_team" v-model="nome_team"></b-form-input>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="teamleaders">Team Leader</label>
                                        <b-form-select class="form-control form-control-sm" size="sm" id="teamleaders" v-model="teamleader" :options="opt_teamleaders" text-field="text" v-on:change="attivaButton">
                                            <template #first>
                                                <b-form-select-option :value="null" disabled>-- Scegli un Team Leader --</b-form-select-option>
                                            </template>
                                        </b-form-select>
                                    </div>
                                </div>
                                <div>&nbsp;</div>
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label for="note">Note</label>
                                        <b-form-textarea class="form-control form-control-sm" size="sm" id="note" v-model="note" rows="5"></b-form-textarea>
                                    </div>

                                </div>
                                <div>&nbsp;</div><div>&nbsp;</div>
                            </div>

                            <!-- TAB PANEL 2 -->
                            <div class="tab-pane fade " id="tabpanel2" role="tabpanel" aria-labelledby="example-tab">

                                <b-container class="bv-example-row">
                                    <b-row>
                                        <b-col>
                                            TEAM
                                        </b-col>
                                        <b-col>
                                            LISTA UTENTI
                                        </b-col>
                                    </b-row>
                                    <b-row>
                                        <b-col>
                                            <draggable class="list-group" tag="ul" v-model="team_user_list" v-bind="dragOptions" :move="onMove" @start="isDragging=true" @end="isDragging=false">
                                                <transition-group type="transition" :name="'flip-list'">
                                                    <li class="list-group-item" v-for="element in team_user_list" :key="element.order">

                                                        <b-avatar button variant="info" :src="'<?php echo DOMAIN ?>assets/' + element.foto"></b-avatar>
                                                        &nbsp;{{element.nome}}&nbsp;-&nbsp;{{element.cognome}}
                                                    </li>
                                                </transition-group>
                                            </draggable>
                                        </b-col>
                                        <b-col>
                                            <draggable element="span" v-model="user_list" v-bind="dragOptions" :move="onMove">
                                                <transition-group name="no" class="list-group" tag="ul">
                                                    <li class="list-group-item" v-for="element in user_list" :key="element.order">

                                                        <b-avatar button variant="info" :src="'<?php echo DOMAIN ?>assets/' + element.foto"></b-avatar>
                                                        &nbsp;{{element.nome}}&nbsp;-&nbsp;{{element.cognome}}
                                                    </li>
                                                </transition-group>
                                            </draggable>
                                        </b-col>
                                    </b-row>
                                </b-container>

                            </div>

                            <!-- TAB PANEL 3 -->
                            <div class="tab-pane fade " id="tabpanel3" role="tabpanel" aria-labelledby="example-tab">
                                {{esitoSalvataggio}}
                            </div>

                            <!-- TAB-CONTENT        fine -->
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="btn_previous" :disabled="btnPrevDisabled" v-on:click="previousTeam"><span v-html="indietro"></span></button>
                        <button type="button" class="btn btn-primary" id="btn_next" :disabled="btnNextDisabled" v-on:click="nextTeam"><span v-html="avanti"></span></button>
                    </div>

            </div>
        </div>
    </div>

    <!-- Modal Invia Email -->
    <div class="modal fade bd-example-modal-xl" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myMediumModalLabel" aria-hidden="true" id="modalInviaEmail">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="text-uppercase">Invia Email per comunicare con un Team</h1>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" style="color: #9c0e00"><i class="fas fa-window-close"></i></span>
                    </button>
                </div>
                <form v-on:submit.prevent="emailSend">
                    <div class="modal-body">
                        <div class="tab-content" id="cardTabContent">
                            <div class="tab-pane fade show active " id="tabpanel1" role="tabpanel" aria-labelledby="example-tab">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="subject">OGGETTO:&nbsp;</label>
                                        <b-form-input type="text" class="form-control form-control-sm" size="sm" id="subject" v-model="subjectEmail" placeholder="inserisci l'oggetto..." required>
                                    </div>
                                </div>
                                <div>&nbsp;</div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div id="editor">
                                        </div>
                                    </div>
                                </div>
                                <div>&nbsp;</div><div>&nbsp;</div><div>&nbsp;</div>
                            </div>
                            <div class="tab-pane fade " id="tabpanel2" role="tabpanel" aria-labelledby="example-tab">
                                <div v-if="loadingEmail" class="d-flex justify-content-center">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                                <div v-else>
                                    <div v-if="showSuccessAlertEmail">
                                        <p >
                                            Email Inviata con Successo!
                                        </p>
                                    </div>
                                    <div v-if="showErrorAlertEmail">
                                        <p >
                                            Errore! email non inviata
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="btn_next" :disabled="btnNextDisabled"><span v-html="avanti"></span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>