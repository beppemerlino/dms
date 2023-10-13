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
            <img src="assets/img/icons/computer.svg" height="32">Computer
            <b-button size="sm" class="m-2" variant="teal" v-on:click="insertPc"><img src="assets/img/icons/plus-white.svg" style="height: 16px;" />&nbsp;Nuovo Pc</b-button>
        </div>
        <div class="card-body" >
            <!-- // Filtro tabella -->
            <div class="form-row">
                <div class="col-md-3 ml-auto">
                    <b-form-input
                            id="filter-input"
                            v-model="filter"
                            type="search"
                            placeholder="Ricerca"
                    ></b-form-input>
                </div>
            </div>

            <div class="form-row">&nbsp;</div>


            <!-- // Tabella dei Computer -->
            <div class="datatable">
                <b-table id="table"
                         class="text-center"
                         striped bordered small hover
                         :items="computer_list"
                         :fields="fields_table_computer"
                         :current-page="pageNumber"
                         :per-page="rowsInPage"
                         @filtered="onFiltered"
                         :filter="filter"
                         sort-icon-left :busy="isBusy">
                    <template #table-busy>
                        <div class="text-center text-blue my-2">
                            <b-spinner class="align-middle"></b-spinner>
                        </div>
                    </template>

                    <template #cell(foto)="record1" >
                        <b-avatar  button @click="openAvatar(record1.item)" square variant="info" :src="'assets/' + record1.item.foto"></b-avatar>
                    </template>


                    <template #cell(actions)="row" >
                        <b-button size="sm" v-on:click="editPc(row.item)" variant="link" v-b-tooltip.hover title="Modifica Pc"><img src="assets/img/icons/edit-blue.svg" style="height: 16px" /></b-button>
                        <b-button size="sm" v-on:click="deleteItem(row.item)" variant="link"  v-b-tooltip.hover title="Cancella Pc"><img src="assets/img/icons/trash-red.svg" style="height: 16px" /></b-button>
                        <b-button size="sm" v-on:click="viewItemSw(row.item)" variant="link"  v-b-tooltip.hover title="Software del Pc"><img src="assets/img/icons/software.svg" style="height: 16px" /></b-button>
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

    <!-- FINESTRA MODALE PER LA VISUALIZZAZIONE DEI SOFTWARE PRESENTI NEL PC -->

    <div class="modal fade bd-example-modal-xl" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true" id="modalSwPc">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- HEADER -->
                    <h1 style="text-transform: uppercase">LISTA DEI SOFTWARE DEL COMPUTER <strong>{{computer_sw}}</strong></h1>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" style="color: #9c0e00"><img src="assets/img/icons/window-close.svg" style="height: 32px" /></span>
                    </button>

                </div>
                <div class="modal-body">
                    <!-- BODY -->
                    <div class="datatable">
                        <b-table id="table"
                                 class="text-center"
                                 striped bordered small hover
                                 :items="software_list"
                                 :fields="fields_table_sw_computer"
                                 :current-page="pageNumber_sw"
                                 :per-page="rowsInPage_sw"
                                 sort-icon-left :busy="isBusy_sw">

                        </b-table>
                    </div>
                </div>
                <div class="modal-footer">
                    <!-- FOOTER -->

                </div>

            </div>
        </div>
    </div>


    <!-- FINESTRA MODALE PER LA MODIFICA O L'INSERIMENTO DI UN PC -->

    <div class="modal fade bd-example-modal-xl" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true" id="modalEditPc">
        <div class="modal-dialog modal-xl">
            <form v-on:submit.prevent="salvaDati">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 style="text-transform: uppercase">{{ aggiungi }} COMPUTER</h1>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style="color: #9c0e00"><img src="assets/img/icons/window-close.svg" style="height: 32px" /></span>
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
                                         :stencil-props="{ aspectRatio: auto }"
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
                                <label for="nome">NOME COMPUTER</label>
                                <b-form-input type="text" class="form-control form-control-sm" size="sm" id="nome" v-model="nome" placeholder="Nome computer" required>
                            </div>
                            <div class="col-md-2">
                                <label for="vendor">MARCA</label>
                                <b-form-input type="text" class="form-control form-control-sm" size="sm" id="vendor" v-model="vendor" placeholder="Marca" required>
                            </div>
                            <div class="col-md-2">
                                <label for="model">NOME MODELLO</label>
                                <b-form-input type="text" class="form-control form-control-sm" size="sm" id="model" v-model="model" placeholder="Nome modello" required>
                            </div>
                            <div class="col-md-2">
                                <label for="cpu_1">CPU PRINCIPALE</label>
                                <b-form-input type="text" class="form-control form-control-sm" size="sm" id="cpu_1" v-model="cpu_1" placeholder="CPU principale" required>
                            </div>
                            <div class="col-md-4">
                                <label for="cpu_2">CPU SECONDARIA (opzionale)</label>
                                <b-form-input type="text" class="form-control form-control-sm" size="sm" id="cpu_2" v-model="cpu_2" placeholder="CPU secondaria">
                            </div>
                        </div>

                        <div>&nbsp;</div>


                        <!-- #################### #2 RIGA ############################ -->
                        <div class="row">
                            <div class="col-md-8">
                                <label for="operative_system">SISTEMA OPERATIVO</label>
                                <b-form-input type="text" class="form-control form-control-sm" size="sm" id="operative_system" v-model="operative_system" placeholder="SISTEMA OPERATIVO" required>
                            </div>
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
                                <label for="keyboard">TASTIERA</label>
                                <b-form-input type="text" class="form-control form-control-sm" size="sm" id="keyboard" v-model="keyboard" placeholder="Modello tastiera" required>
                            </div>
                            <div class="col-md-2">
                                <label for="mouse">MOUSE</label>
                                <b-form-input type="text" class="form-control form-control-sm" size="sm" id="mouse" v-model="mouse" placeholder="Modello Mouse" required>
                            </div>
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
                            <div class="col-md-4">
                                <label for="form_factory">FORM FACTORY</label>
                                <b-form-select
                                        class="form-control form-control-sm"
                                        size="sm"
                                        id="form_factory"
                                        v-model="form_factory"
                                        :options="opt_formfactory"
                                        text-field="text"
                                        style="padding-top: 1px; padding-bottom: 1px;">
                                    <template #first>
                                        <b-form-select-option :value="null" disabled>-- Selezionare una Form Factory --</b-form-select-option>
                                    </template>
                                </b-form-select>
                            </div>
                            <div class="col-md-4">
                                <label for="ram_size">RAM</label>
                                <b-form-input type="text" class="form-control form-control-sm" size="sm" id="ram_size" v-model="ram_size" placeholder="RAM" required>
                            </div>


                        </div>

                        <div>&nbsp;</div>

                        <!-- #################### #5 RIGA ############################ -->
                        <div class="row">
                            <div class="col-md-6">
                                <label for="primary_disk_size">PRIMARY DISK SIZE</label>
                                <b-form-input type="text" class="form-control form-control-sm" size="sm" id="primary_disk_size" v-model="primary_disk_size" placeholder="Primary disk size" required>
                            </div>
                            <div class="col-md-6">
                                <label for="secondary_disk_size">SECONDARY DISK SIZE (Opzionale)</label>
                                <b-form-input type="text" class="form-control form-control-sm" size="sm" id="secondary_disk_size" v-model="secondary_disk_size" placeholder="Secondary disk size" >
                            </div>

                        </div>

                        <div>&nbsp;</div>

                        <!-- #################### #6 RIGA ############################ -->
                        <div class="row">
                            <div class="col-md-6">
                                <label for="ip_address_1">INDIRIZZO IP 1</label>
                                <b-form-input type="text" class="form-control form-control-sm" size="sm" id="ip_address_1" v-model="ip_address_1" placeholder="Indirizzo IP 1" required>
                            </div>
                            <div class="col-md-6">
                                <label for="ip_address_2">INDIRIZZO IP 2 (OPZIONALE)</label>
                                <b-form-input type="text" class="form-control form-control-sm" size="sm" id="ip_address_2" v-model="ip_address_2" placeholder="Indirizzo IP 2">
                            </div>

                        </div>

                        <div>&nbsp;</div>

                        <!-- #################### #7 RIGA ############################ -->
                        <div class="row">
                            <div class="col-md-6">
                                <label for="mac_ethernet_1">INDIRIZZO MAC 1</label>
                                <b-form-input type="text" class="form-control form-control-sm" size="sm" id="mac_ethernet_1" v-model="mac_ethernet_1" placeholder="Indirizzo MAC 1" required>
                            </div>
                            <div class="col-md-6">
                                <label for="mac_ethernet_2">INDIRIZZO MAC 2 (OPZIONALE)</label>
                                <b-form-input type="text" class="form-control form-control-sm" size="sm" id="mac_ethernet_2" v-model="mac_ethernet_2" placeholder="Indirizzo MAC 2">
                            </div>
                        </div>

                        <div>&nbsp;</div>

                        <!-- #################### #8 RIGA ############################ -->
                        <div class="row">

                            <div class="col-md-3">

                                <b-form-checkbox
                                        id="bluetooth"
                                        v-model="bluetooth"
                                        name="bluetooth"
                                        value="1"
                                        unchecked-value="0"
                                >
                                    BLUETOOTH
                                </b-form-checkbox>

                            </div>

                            <div class="col-md-3">

                                <b-form-checkbox
                                        id="ethernet_1"
                                        v-model="ethernet_1"
                                        name="ethernet_1"
                                        value="1"
                                        unchecked-value="0"
                                >
                                    ETHERNET 1
                                </b-form-checkbox>

                            </div>
                            <div class="col-md-3">

                                <b-form-checkbox
                                        id="ethernet_2"
                                        v-model="ethernet_2"
                                        name="ethernet_2"
                                        value="1"
                                        unchecked-value="0"
                                >
                                    ETHERNET 2
                                </b-form-checkbox>

                            </div>


                            <div class="col-md-3">

                                <b-form-checkbox
                                        id="dvd_rom"
                                        v-model="dvd_rom"
                                        name="dvd_rom"
                                        value="1"
                                        unchecked-value="0"
                                >
                                    DVD-ROM
                                </b-form-checkbox>

                            </div>


                        </div>

                        <div>&nbsp;</div>

                        <!-- #################### #9 RIGA ############################ -->
                        <div class="row">
                            <div class="col-md-3">

                                <b-form-checkbox
                                        id="hdmi_port"
                                        v-model="hdmi_port"
                                        name="hdmi_port"
                                        value="1"
                                        unchecked-value="0"
                                >
                                    HDMI PORT
                                </b-form-checkbox>

                            </div>

                            <div class="col-md-3">

                                <b-form-checkbox
                                        id="dvi_port"
                                        v-model="dvi_port"
                                        name="dvi_port"
                                        value="1"
                                        unchecked-value="0"
                                >
                                    DVI PORT
                                </b-form-checkbox>

                            </div>

                            <div class="col-md-3">

                                <b-form-checkbox
                                        id="display_port"
                                        v-model="display_port"
                                        name="display_port"
                                        value="1"
                                        unchecked-value="0"
                                >
                                    DISPLAY PORT
                                </b-form-checkbox>

                            </div>


                        </div>

                        <div>&nbsp;</div>

                        <!-- #################### #10 RIGA ############################ -->
                        <div class="row">



                            <div class="col-md-3">

                                    <b-form-checkbox
                                            id="mdisplay_port"
                                            v-model="mdisplay_port"
                                            name="mdisplay_port"
                                            value="1"
                                            unchecked-value="0"
                                    >
                                        MINI DISPLAY PORT
                                    </b-form-checkbox>

                            </div>
                            <div class="col-md-3">

                                    <b-form-checkbox
                                            id="thunderbolt_port"
                                            v-model="thunderbolt_port"
                                            name="thunderbolt_port"
                                            value="1"
                                            unchecked-value="0"
                                    >
                                        THUNDERBOLT PORT
                                    </b-form-checkbox>

                            </div>
                            <div class="col-md-3">

                                    <b-form-checkbox
                                            id="wifi_card"
                                            v-model="wifi_card"
                                            name="wifi_card"
                                            value="1"
                                            unchecked-value="0"
                                    >
                                        WI-FI CARD
                                    </b-form-checkbox>

                            </div>

                        </div>

                        <div>&nbsp;</div>

                        <!-- #################### #11 RIGA ############################ -->
                        <div class="row">

                            <div class="col-md-4">
                                <label for="audio_card">SCHEDA AUDIO</label>
                                <b-form-input type="text" class="form-control form-control-sm" size="sm" id="audio_card" v-model="audio_card" placeholder="Scheda audio" required>
                            </div>
                            <div class="col-md-4">
                                <label for="num_usb">NUMERO PORTE USB 2.0</label>
                                <b-form-input type="number" min="0" max="10"  class="form-control form-control-sm" size="sm" id="num_usb" v-model="num_usb" placeholder="Numero poste USB 2.0" required>
                            </div>
                            <div class="col-md-4">
                                <label for="num_usb_3">NUMERO PORTE USB 3.0 (OPZIONALE)</label>
                                <b-form-input type="number" min="0" max="10"  class="form-control form-control-sm" size="sm" id="num_usb_3" v-model="num_usb_3" placeholder="Numero poste USB 3.0">
                            </div>

                        </div>

                        <div>&nbsp;</div>

                        <!-- #################### #12 RIGA ############################ -->
                        <div class="row">


                            <div class="col-md-6">
                                <label for="power_supply">UPS POWER SUPPLY (OPZIONALE)</label>
                                <b-form-input type="text" class="form-control form-control-sm" size="sm" id="power_supply" v-model="power_supply" placeholder="UPS POWER SUPPLY">
                            </div>
                            <div class="col-md-6">
                                <label for="power_cell">BATTERIA PER I NOTEBOOK (OPZIONALE)</label>
                                <b-form-input type="text" class="form-control form-control-sm" size="sm" id="power_cell" v-model="power_cell" placeholder="Batteria per i Notebook">
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
                    >{{ messaggio }}
                    </b-alert>
                    <b-alert
                            variant="danger"
                            dismissible
                            fade
                            :show="showErrorAlert"
                            @dismissed="showErrorAlert=false"
                    >{{ messaggio }}
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
                    <h5 class="modal-title" id="ModalLongTitle">Cancellazione Computer</h5>
                </div>
                <div class="modal-body">
                    <div>
                        <p>Cancella Pc&nbsp;<strong>&nbsp;{{nome}}</strong>.<br><br>Premere "OK" per continuare...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" v-on:click="closeModalDelete">Annulla</button>
                    <a type="button" class="btn btn-success" data-dismiss="modal" v-on:click="deletePc">OK</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Imaggine-->
    <div class="modal fade" data-backdrop="static" id="modalAvatar" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLongTitle">IMMAGINE PC</h5>
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
