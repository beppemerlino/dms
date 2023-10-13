new Vue({
    el: '#app',
    data () {
        return {
            filter: '',
            currentPage: 1,
            totalRows: 0,
            perPage: 20,
            isBusy: false,
            fields_commesse: [
                { key: 'codice', label: 'CODICE', tdClass: 'text-left'},
                { key: 'anno', label: 'ANNO', sortable: true, filterByFormatted: true },
                { key: 'cliente', label: 'CLIENTE', sortable: true, filterByFormatted: true },
                { key: 'localizzazione', label: 'LOCALIZZAZIONE', sortable: true, filterByFormatted: true },
                { key: 'tipo_lavoro', label: 'TIPO LAVORO', sortable: true, filterByFormatted: true },
                { key: 'chiusa', label: 'APERTA', sortable: true, filterByFormatted: true },
                { key: 'assegnata', label: 'TEAM'},
                { key: 'Azioni', label: 'Azioni'}
            ],
            commesseList: [],
            idCommessaDaEliminare: '',
            CodiceCommessaDaEliminare: '',
            id: "0",
            codice: "",
            anno: "",
            cliente: "",
            localizzazione: "",
            tipo_lavoro: "",
            chiusa: "0",
            teams: [],
            selected_teams: [],
            options_teams:[
                { text: 'Team-1', value: '1' },
                { text: 'Team-2', value: '2' },
                { text: 'Team-3', value: '3' },
                { text: 'Team-4', value: '4' }
            ],
            titolo: "LISTA COMMESSE",


            fields_table_cli: [
                { key: 'cliente', label: 'Nome', sortable: true }
            ],

            clientiList: [''],
            currentPage_cli: 1,
            totalRows_cli: 1,
            perPage_cli: 10,
            loading_cli: true,

            fields_table_tip: [
                { key: 'tipo_lavoro', label: 'Tipo Lavoro', sortable: true }
            ],

            tipiLavoroList: [''],
            currentPage_tip: 1,
            totalRows_tip: 1,
            perPage_tip: 10,
            loading_tip: true,

            save_response: '',
            loading_save: false,
        }
    },
    methods: {
        showCommesseList(){
            this.loading = true;
            this.isBusy = true;
            axios
                .get('./php/lista_commesse.php')
                .then(response => {
                    this.commesseList = response.data.lista_commesse;
                    this.totalRows = response.data.lista_commesse.length;
                    this.loading = false;
                    this.isBusy = false;
                });
        },

        showTeamList(){
            this.loading = true;
            this.isBusy = true;
            axios
                .get('./php/lista_teams.php')
                .then(response => {
                    this.options_teams = response.data.options_teams;
                });
        },

        estendiCommessa(item){

            axios
                .get('./php/num_commessa_esteso.php?codice=' + item.codice)
                .then(response => {
                    this.codice = response.data.next_codice;
                });

            const today = new Date();
            const year = today.getUTCFullYear();

            this.id = "0";
            this.anno = year;
            this.cliente = item.cliente;
            this.localizzazione = item.localizzazione;
            this.tipo_lavoro = "";
            this.chiusa = "0";
            this.selected_teams = [];
            this.titolo = "NUOVA ESTENSIONE DI COMMESSA";
            $('#tabpanel2').addClass('show active');
            $('#tabpanel1').removeClass('show active');

        },

        editCommessa(item){

            const array = item.teams;
            this.selected_teams = [];

            for(let x = 0; x < array.length; x ++){

                this.selected_teams.push(array[x].id_team);

            }

            this.id = item.id;
            this.codice = item.codice;
            this.anno = item.anno;
            this.cliente = item.cliente;
            this.localizzazione = item.localizzazione;
            this.tipo_lavoro = item.tipo_lavoro;
            this.chiusa = item.chiusa;
            this.titolo = "MODIFICA COMMESSA " + item.codice;
            $('#tabpanel2').addClass('show active');
            $('#tabpanel1').removeClass('show active');

        },

        deleteCommessa(){

            data_json = {
                id: this.idCommessaDaEliminare,
            };


            $('#ModalSave').modal('show');
            this.loading_save = true;
            const params = new URLSearchParams();
            params.append('data', JSON.stringify(data_json));
            const options = {
                method: 'POST',
                headers: { 'content-type': 'application/x-www-form-urlencoded' },
                data: params,
                url: './php/cancella_commessa.php',
            };
            axios(options)
                .then(response => {
                    this.save_response = response.data.messaggio;
                    this.loading_save = false;
                })
                .catch(error => {
                    this.save_response = error.message;
                    console.error("ERRORE! ", error);
                });


        },

        eliminaCommessa(item){

            this.idCommessaDaEliminare = item.id;
            this.CodiceCommessaDaEliminare = item.codice;
            $('#ModalDelete').modal('show');

        },

        salvaCommessa(){

            data_json = {
                id: this.id,
                codice: this.codice,
                anno: this.anno,
                cliente: this.cliente,
                localizzazione: this.localizzazione,
                tipo_lavoro: this.tipo_lavoro,
                teams: this.selected_teams,
                chiusa: this.chiusa
            };

            console.log(JSON.stringify(data_json));
            $('#ModalSave').modal('show');
            this.loading_save = true;
            const params = new URLSearchParams();
            params.append('data', JSON.stringify(data_json));
            const options = {
                method: 'POST',
                headers: { 'content-type': 'application/x-www-form-urlencoded' },
                data: params,
                url: './php/salva_commessa.php',
            };
            axios(options)
                .then(response => {
                    this.save_response = response.data.messaggio;
                    this.id = response.data.id_commessa;
                    this.loading_save = false;
                })
                .catch(error => {
                    this.save_response = error.message;
                    console.error("ERRORE! ", error);
                });

        },

        chiudiSalva(){

            this.save_response = "";
            $('#ModalSave').modal('hide');
            this.showCommesseList();

            this.annullaCommessa();
        },

        nuovaCommessa(){

            axios
                .get('./php/num_commessa.php')
                .then(response => {
                    this.codice = response.data.next_codice;
                });

            const today = new Date();
            const year = today.getUTCFullYear();

            this.id = "0";
            this.anno = year;
            this.cliente = "";
            this.localizzazione = "";
            this.tipo_lavoro = "";
            this.chiusa = "0";
            this.selected_teams = [];
            this.titolo = "NUOVA COMMESSA";
            $('#codice').prop('readonly', false);
            $('#anno').prop('readonly', false);
            $('#tabpanel2').addClass('show active');
            $('#tabpanel1').removeClass('show active');

        },

        annullaCommessa(){

            this.titolo = "LISTA COMMESSE";
            $('#tabpanel1').addClass('show active');
            $('#tabpanel2').removeClass('show active');

        },

        cercaCliente(){

            $('#modalClienti').modal('show');
            this.loading_cli = true;
            axios
                .get('./php/lista_clienti.php?keyword=' + this.cliente)
                .then(response => {
                    this.totalRows_cli = response.data.lista_clienti.length;
                    this.clientiList = response.data.lista_clienti;
                    this.loading_cli = false;
                });
        },

        insertCliente(item){

            this.cliente = item.cliente;

            $('#modalClienti').modal('hide');
        },

        cercaTipoLavoro(){

            $('#modalTipiLavoro').modal('show');
            this.loading_tip = true;
            axios
                .get('./php/lista_tipi_lavoro.php?keyword=' + this.tipo_lavoro)
                .then(response => {
                    this.totalRows_tip = response.data.lista_tipi_lavoro.length;
                    this.tipiLavoroList = response.data.lista_tipi_lavoro;
                    this.loading_tip = false;
                });
        },

        insertTipoLavoro(item){

            this.tipo_lavoro = item.tipo_lavoro;

            $('#modalTipiLavoro').modal('hide');
        },

        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length
            this.currentPage = 1
        }


    },
    mounted () {
        this.showCommesseList();
        this.showTeamList();
    },

    computed: {

    },
    watch: {

    },

})