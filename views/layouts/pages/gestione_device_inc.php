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
            <img src="assets/img/icons/device.svg" height="32">Device
            <b-button size="sm" class="m-2" variant="teal" v-on:click="insertDevice"><img src="assets/img/icons/plus-white.svg" style="height: 16px;" />&nbsp;Nuovo Device</b-button>
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


            <!-- // Tabella dei Device -->
            <div class="datatable">
                <b-table id="table" class="text-center"  striped bordered small hover :items="device_list" :fields="fields_table_nas" :current-page="pageNumber"
                         :per-page="rowsInPage"  @filtered="onFiltered" :filter="filter" sort-icon-left :busy="isBusy">
                    <template #table-busy>
                        <div class="text-center text-blue my-2">
                            <b-spinner class="align-middle"></b-spinner>
                        </div>
                    </template>

                    <template #cell(foto)="record1" >
                        <b-avatar  button @click="openAvatar(record1.item)" square variant="info" :src="'assets/' + record1.item.foto"></b-avatar>
                    </template>


                    <template #cell(actions)="row" >
                        <b-button size="sm" v-on:click="editDevice(row.item)" variant="link" v-b-tooltip.hover title="Modifica Device"><img src="assets/img/icons/edit-blue.svg" style="height: 16px" /></b-button>
                        <b-button size="sm" v-on:click="deleteItem(row.item)" variant="link"  v-b-tooltip.hover title="Cancella Device"><img src="assets/img/icons/trash-red.svg" style="height: 16px" /></b-button>
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

    <!-- FINESTRA MODALE PER LA MODIFICA O L'INSERIMENTO DI UN PC -->

    <div class="modal fade bd-example-modal-xl" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true" id="modalEditDevice">
        <div class="modal-dialog modal-xl">
            <form v-on:submit.prevent="salvaDati">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 style="text-transform: uppercase">{{ aggiungi }} DEVICE</h1>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style="color: #9c0e00"><i class="fas fa-window-close"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <!-- Profile picture image -->

                                <img id="img" class="img-account-profile mb-2" :src="'./assets/' + foto" alt="" />


                            </div>
                            <div class="col-md-8">

                                <cropper ref="cropper"
                                         class="upload-example"
                                         :src="image.src"
                                         :stencil-props="{ aspectRatio: 4/4 }"
                                         :debounce="false"
                                         @change="onChange"
                                ></cropper>
                                <div>&nbsp;</div>
                                <label for="file">Upload immagine</label>
                                <b-form-file ref="file" accept=".jpg, .jpeg, .JPG, JPEG" placeholder="Inserisci file" drop-placeholder="Inserisci il file qui" browse-text="Cerca" id="file" size="sm" @change="loadImage($event)"></b-form-file>
                                <div>&nbsp;</div>
                                <button v-if="image.src" type="button" class="btn btn-primary btn-sm" @click="uploadImage">SALVA IMMAGINE</button>

                            </div>
                        </div>
                        <div>&nbsp;</div>


                        <!-- #################### #1 RIGA ############################ -->
                        <div class="row">
                            <div class="col-md-2">
                                <label for="nome">NOME DEVICE</label>
                                <b-form-input type="text" class="form-control form-control-sm" size="sm" id="nome" v-model="nome" placeholder="Nome device" required>
                            </div>
                            <div class="col-md-2">
                                <label for="vendor">MARCA</label>
                                <b-form-input type="text" class="form-control form-control-sm" size="sm" id="vendor" v-model="vendor" placeholder="Marca" required>
                            </div>
                            <div class="col-md-2">
                                <label for="model">NOME MODELLO</label>
                                <b-form-input type="text" class="form-control form-control-sm" size="sm" id="model" v-model="model" placeholder="Nome modello" required>
                            </div>
                        </div>

                        <div>&nbsp;</div>


                        <!-- #################### #2 RIGA ############################ -->
                        <div class="row">

                            <div class="col-md-4">
                                <label for="id_workstation">WORKSTATION</label>
                                <b-form-select
                                            class="form-control form-control-sm"
                                            size="sm"
                                            id="id_workstation"
                                            v-model="id_workstation"
                                            :options="opt_workstation"
                                            text-field="text"
                                            style="padding-top: 1px; padding-bottom: 1px;">
                                    <template #first>
                                        <b-form-select-option :value="null" disabled>-- Selezionare una Workstation --</b-form-select-option>
                                    </template>
                                </b-form-select>
                            </div>
                        </div>

                        <div>&nbsp;</div>


                        <!-- #################### #3 RIGA ############################ -->
                        <div class="row">

                            <div class="col-md-2">
                                <label for="serial_number">SERIAL NUMBER</label>
                                <b-form-input type="text" class="form-control form-control-sm" size="sm" id="serial_number" v-model="serial_number" placeholder="Serial number" required>
                            </div>
                            <div class="col-md-6">
                                <label for="part_number">PART NUMBER (Opzionale)</label>
                                <b-form-input type="text" class="form-control form-control-sm" size="sm" id="part_number" v-model="part_number" placeholder="Part number" >
                            </div>

                        </div>

                        <div>&nbsp;</div>


                        <!-- #################### #4 RIGA ############################ -->
                        <div class="row">
                            <div class="col-md-4">
                                <label for="rif_cespite">RIF. CESPITE (opzionale)</label>
                                <b-form-input type="text" class="form-control form-control-sm" size="sm" id="rif_cespite" v-model="rif_cespite" placeholder="Riferimento cespite">
                            </div>

                            <div class="col-md-8">
                                <label for="description">DESCRIZIONE</label>
                                <b-form-input type="text" class="form-control form-control-sm" size="sm" id="description" v-model="description" placeholder="Descrizione" required>
                            </div>

                        </div>

                        <div>&nbsp;</div>


                    </div>
                    <b-alert
                            variant="success"
                            dismissible
                            fade
                            :show="showSuccessAlert"
                            @dismissed="showSuccessAlert=false"
                    >{{messaggio}}
                    </b-alert>
                    <b-alert
                            variant="danger"
                            dismissible
                            fade
                            :show="showErrorAlert"
                            @dismissed="showErrorAlert=false"
                    >{{messaggio}}
                    </b-alert>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Chiudi</button>
                        <button type="submit" class="btn btn-primary btn-sm" >SALVA</button>
                    </div>
                </div>
            </form>
        </div>

    </div>

    <!-- Modal DELETE-->
    <div class="modal fade" data-backdrop="static" id="ModalDelete" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLongTitle">Cancellazione Device</h5>
                </div>
                <div class="modal-body">
                    <div>
                        <p>Cancella Device&nbsp;<strong>&nbsp;{{nome}}</strong>.<br><br>Premere "OK" per continuare...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" v-on:click="closeModalDelete">Annulla</button>
                    <a type="button" class="btn btn-success" data-dismiss="modal" v-on:click="deleteDevice">OK</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Imaggine-->
    <div class="modal fade" data-backdrop="static" id="modalAvatar" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLongTitle">IMMAGINE DEVICE</h5>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <b-avatar square variant="info" :src="'assets/' + avatar" size="30rem"></b-avatar>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Chiudi</button>
                </div>
            </div>
        </div>
    </div>

</div>
