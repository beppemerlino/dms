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
            <img src="assets/img/icons/users-solid.svg" height="32">Utenti
            <b-button size="sm" class="m-2" variant="teal" v-on:click="insertUser"><i class="fas fa-fw fa-user-plus"></i>&nbsp;Inserisci Utente</b-button>
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
                <b-table id="table" striped bordered small hover :items="utentiList" :fields="fields_utenti"  :current-page="currentPage"
                         :per-page="perPage"  @filtered="onFiltered" :filter="filter" sort-icon-left :busy="isBusy">
                    <template #table-busy>
                        <div class="text-center text-danger my-2">
                            <b-spinner class="align-middle"></b-spinner>
                            <strong>Loading...</strong>
                        </div>
                    </template>
                    <template #cell(foto)="record1" >
                        <b-avatar button @click="openAvatar(record1.item)" variant="info" :src="'<?php echo DOMAIN ?>assets/' + record1.item.foto"></b-avatar>
                    </template>
                    <template #cell(sesso)="record" >
                        <div v-if="record.item.sesso === '1'" class="badge badge-blue-soft badge-pill">Maschio</div>
                        <div v-if="record.item.sesso === '2'" class="badge badge-red-soft badge-pill">Femmina</div>
                    </template>
                    <template #cell(group)="record2" >
                        <div v-if="record2.item.group === '1'" class="badge badge-primary badge-pill">SuperAdmins</div>
                        <div v-if="record2.item.group === '2'" class="badge badge-orange badge-pill">Admins</div>
                        <div v-if="record2.item.group === '3'" class="badge badge-teal badge-pill">SuperUsers</div>
                        <div v-if="record2.item.group === '4'" class="badge badge-secondary badge-pill">Users</div>
                    </template>
                    <template #cell(attivo)="record3" >
                        <div v-if="record3.item.attivo === '1'" class="badge badge-success badge-pill">OK!</div>
                        <div v-if="record3.item.attivo === '2'" class="badge badge-warning badge-pill">Non Attivo!</div>
                    </template>

                    <template #cell(Azioni)="row" >
                        <b-button v-if="(super_admin === true || row.item.id !== '1')" size="sm" v-on:click="editUser(row.item)" variant="link" v-b-tooltip.hover title="Modifica Utente"><i class="fas fa-user-edit"></i></b-button>
                        <b-button v-if="(row.item.id !== '1' && row.item.rendicontato === '0')" size="sm" v-on:click="deleteItem(row.item)" variant="link"  v-b-tooltip.hover title="Cancella Utente"><i class="fas fa-trash"></i></b-button>
                        <b-button v-if="(super_admin === true || row.item.id !== '1')" size="sm" v-on:click="editPassword(row.item)" variant="link" v-b-tooltip.hover title="Modifica Password"><i class="fas fa-user-lock"></i></b-button>
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

    <!-- FINESTRA MODALE PER LA MODIFICA O L'INSERIMENTO DI UN UTENTE -->

    <div class="modal fade bd-example-modal-xl" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true" id="modalEditUser">
        <div class="modal-dialog modal-xl">
                <form v-on:submit.prevent="salvaDati">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 style="text-transform: uppercase">{{ aggiungi }} Utente</h1>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true" style="color: #9c0e00"><i class="fas fa-window-close"></i></span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-4">
                                        <!-- Profile picture image -->

                                    <img id="img" class="img-account-profile rounded-circle mb-2" :src="photo" alt="" />


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
                        <div class="row">
                            <div class="col-md-3">
                                <label for="nickname">Nick Name</label>
                                <b-form-input type="text" class="form-control form-control-sm" size="sm" id="nickname" v-model="nickname" placeholder="Nickname" required>
                            </div>
                            <div class="col-md-1">
                                <label for="title">Titolo</label>
                                <b-form-input type="text" class="form-control form-control-sm" size="sm" id="title" v-model="title" placeholder="Titolo" required>
                            </div>
                            <div class="col-md-4">
                                <label for="firstname">Nome</label>
                                <b-form-input type="text" class="form-control form-control-sm" size="sm" id="firstname" v-model="firstname" placeholder="Nome" required>
                            </div>
                            <div class="col-md-4">
                                <label for="lastname">Cognome</label>
                                <b-form-input type="text" class="form-control form-control-sm" size="sm" id="lastname" v-model="lastname" placeholder="Cognome" required>
                            </div>
                        </div>
                        <div>&nbsp;</div>
                        <div v-if="(edit_add === 'Aggiungi')" class="row">
                            <div class="col-md-12">
                                <label for="edit_password">Password</label>
                                <b-form-input ref="edit_password" type="password" size="sm" id="edit_password" v-model="password" placeholder="Inserisci Password" autocomplete="new-password" required>
                            </div>
                        </div>
                        <div v-if="(edit_add === 'Aggiungi')">&nbsp;</div>
                        <div v-if="(edit_add === 'Aggiungi')" class="row">
                            <div class="col-md-12">
                                <label for="input_confirm">Conferma password</label>
                                <b-form-input type="password" class="form-control form-control-sm" size="sm" id="input_confirm" v-model="confirm_password" placeholder="Conferma Password" required>
                            </div>
                        </div>
                        <div>&nbsp;</div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="email">Email Principale</label>
                                <b-form-input type="email" class="form-control form-control-sm" size="sm" id="email" v-model="email" placeholder="Email" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email2">Email Secondaria (Facoltativa)</label>
                                <b-form-input type="email" class="form-control form-control-sm" size="sm" id="email2" v-model="email2" placeholder="Email secondaria">
                            </div>
                        </div>
                        <div>&nbsp;</div>
                        <div class="row">
                            <div class="col-md-3">
                                <label for="group">Grupppo</label>
                                <div v-if="id_utente === '1'">
                                    <b-form-input type="text" class="form-control form-control-sm" size="sm" id="group_super" value="ADMINS" readonly>
                                </div>
                                <div v-else>
                                    <b-form-select
                                            class="form-control form-control-sm"
                                            size="sm"
                                            id="group"
                                            v-model="group"
                                            :options="opt_gruppo"
                                            text-field="text"
                                            style="padding-top: 1px; padding-bottom: 1px;">
                                    </b-form-select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="gender">Sesso</label>
                                <b-form-select
                                        class="form-control form-control-sm"
                                        size="sm"
                                        id="gender"
                                        v-model="gender"
                                        :options="opt_gender"
                                        text-field="text"
                                        style="padding-top: 1px; padding-bottom: 1px;">
                                </b-form-select>
                            </div>
                            <div class="col-md-3">
                                <label for="phone">Telefono</label>
                                <b-form-input type="text" class="form-control form-control-sm" size="sm" id="phone" v-model="phone">
                            </div>
                            <div class="col-md-4">
                                <label for="mansionee">Mansione</label>
                                <b-form-input type="text" class="form-control form-control-sm" size="sm" id="mansione" v-model="mansione">
                            </div>
                        </div>
                        <div>&nbsp;</div>
                        <div class="row">
                            <div class="form-group col-md-8">
                                <label for="note">Note</label>
                                <b-form-textarea class="form-control form-control-sm" size="sm" id="note" v-model="note" rows="3"></b-form-textarea>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="tipo_lavoratore">Tipo di Lavoratore</label>
                                <b-form-select
                                        class="form-control form-control-sm"
                                        size="sm"
                                        id="tipo_lavoratore"
                                        v-model="tipo_lavoratore"
                                        :options="opt_tipi_lavoratore"
                                        text-field="text"
                                        style="padding-top: 1px; padding-bottom: 1px;">
                                </b-form-select>
                            </div>
                        </div>
                        <div>&nbsp;</div>
                        <div class="row">
                            <div class="col-md-4">
                                <b-form-checkbox
                                        id="active"
                                        v-model="active"
                                        name="active"
                                        value="attivo"
                                        unchecked-value="non_attivo"
                                >
                                    Attivo
                                </b-form-checkbox>
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
                        >Utente Inserito con Successo!
                        </b-alert>
                        <b-alert
                                variant="danger"
                                dismissible
                                fade
                                :show="showErrorAlert"
                                @dismissed="showErrorAlert=false"
                        >Utente non inserito o non salvato.
                        </b-alert>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Chiudi</button>
                        <button type="submit" class="btn btn-primary btn-sm" >SALVA</button>
                    </div>
                </div>
            </form>
        </div>

    </div>

    <!-- FINESTRA MODALE PER LA MODIFICA DELLA PASSWORD -->

    <div class="modal fade bd-example-modal-xl" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true" id="modalEditPassword">
        <div class="modal-dialog modal-xl">
            <form v-on:submit.prevent="salvaPassword">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 style="text-transform: uppercase">Inserisci nuova Password</h1>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-12">
                                <label for="input_password_1">Password</label>
                                <b-form-input type="password" class="form-control form-control-sm" size="sm" id="input_password_1" v-model="edit_password" placeholder="Password" required>
                            </div>
                        </div>
                        <div>&nbsp;</div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="input_confirm_1">Conferma Password</label>
                                <b-form-input type="password" class="form-control form-control-sm" size="sm" id="input_confirm_1" v-model="edit_confirm_password" placeholder="Conferma Password" required>
                            </div>
                        </div>

                    </div>
                    <b-alert
                            variant="success"
                            dismissible
                            fade
                            :show="showSuccessAlert1"
                            @dismissed="showSuccessAlert1=false"
                    >Password salvata!
                    </b-alert>
                    <b-alert
                            variant="danger"
                            dismissible
                            fade
                            :show="showErrorAlert1"
                            @dismissed="showErrorAlert1=false"
                    >Errore di inserimento: Password non salvata!
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
                    <h5 class="modal-title" id="ModalLongTitle">Cancellazione Utente</h5>
                </div>
                <div class="modal-body">
                    <div>
                        <p>Cancella Utente&nbsp;<strong>&nbsp;{{nickname}}</strong>.<br><br>Premere "OK" per continuare...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" v-on:click="closeModalDelete">Annulla</button>
                    <a type="button" class="btn btn-success" data-dismiss="modal" v-on:click="deleteUser">OK</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Avatar-->
    <div class="modal fade" data-backdrop="static" id="modalAvatar" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLongTitle">AVATAR</h5>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <b-avatar variant="info" :src="'<?php echo DOMAIN ?>assets/' + avatar" size="10rem"></b-avatar>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Chiudi</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Invia Email -->
    <div class="modal fade bd-example-modal-xl" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myMediumModalLabel" aria-hidden="true" id="modalInviaEmail">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="text-uppercase">Invia Email per comunicare con un Utente</h1>
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
