const message = [
    "vue.draggable",
    "draggable",
    "component",
    "for",
    "vue.js 2.0",
    "based",
    "on",
    "Sortablejs"
];


new Vue({
    el: '#app',
    data() {
        return {


            list: message.map((name, index) => {
                return {name, order: index + 1, fixed: true};
            }),
            list2: [],
            editable: true,
            isDragging: false,
            delayedDragging: false,
            user_list: [
                {
                    id: 4,
                    teamleader: false,
                    nome: "Arianna",
                    cognome: "Lanzarini",
                    foto: "avatars\/arianna-lanzarini.jpg",
                    fixed: false,
                    order: 2
                },
                {
                    id: 5,
                    teamleader: false,
                    nome: "Emanuele",
                    cognome: "Carano",
                    foto: "avatars\/emanuele.jpg",
                    fixed: false,
                    order: 3
                },
                {
                    id: 8,
                    teamleader: false,
                    nome: "Davide",
                    cognome: "Marangoni",
                    foto: "avatars\/davide.jpg",
                    fixed: false,
                    order: 4
                },
                {
                    id: 9,
                    teamleader: false,
                    nome: "Raffaele",
                    cognome: "Salvi",
                    foto: "avatars\/raffaele.jpg",
                    fixed: false,
                    order: 5
                },
                {
                    id: 11,
                    teamleader: false,
                    nome: "Giovanni",
                    cognome: "Parisi",
                    foto: "avatars\/giovanni.jpg",
                    fixed: false,
                    order: 6
                },
                {
                    id: 12,
                    teamleader: false,
                    nome: "Rosanna",
                    cognome: "Caporusso",
                    foto: "avatars\/rosanna.jpg",
                    fixed: false,
                    order: 7
                },
                {
                    id: 13,
                    teamleader: false,
                    nome: "Lorenzo",
                    cognome: "Cappellini",
                    foto: "avatars\/lorenzo.jpg",
                    fixed: false,
                    order: 8
                },
                {
                    id: 14,
                    teamleader: false,
                    nome: "Stefania",
                    cognome: "Fontanini",
                    foto: "avatars\/stefania.jpg",
                    fixed: false,
                    order: 9
                },
                {
                    id: 15,
                    teamleader: false,
                    nome: "Maria Teresa",
                    cognome: "Salvi",
                    foto: "avatars\/teresa.jpg",
                    fixed: false,
                    order: 10
                },
                {
                    id: 16,
                    teamleader: false,
                    nome: "Federico",
                    cognome: "Presazzi",
                    foto: "avatars\/fede.jpg",
                    fixed: false,
                    order: 11
                },
                {
                    id: 18,
                    teamleader: false,
                    nome: "Arianna",
                    cognome: "Losi",
                    foto: "avatars\/arianna-losi.jpg",
                    fixed: false,
                    order: 12
                }],
            team_user_list: [{
                id: 3,
                teamleader: true,
                nome: "Monica",
                cognome: "Ortenzi",
                foto: "avatars\/monica.jpg",
                fixed: false,
                order: 1
            },],
            fields_teams: [
                {key: 'nome_team', label: 'NOME TEAM', tdClass: 'text-left'},
                {key: 'team_leader', label: 'TEAM LEADER', sortable: true, filterByFormatted: true},
                {key: 'note', label: 'NOTE', sortable: true, filterByFormatted: true},
                {key: 'Azioni', label: 'Azioni'}
            ],
            edit_add: 'Aggiungi',
            id_team: 0,
            teams_list: [''],
            filter: '',
            currentPage: 1,
            totalRows: 0,
            perPage: 10,
            isBusy: false,
            subjectEmail: '',
            loadingEmail: false,
            showErrorAlertEmail: false,
            showSuccessAlertEmail: false,
            showErrorDelete: false,
            showSuccessDelete: false,
            btnPrevDisabled: false,
            btnNextDisabled: false,
            avanti: 'Avanti',
            indietro: 'Indietro',
            teamSelezionato: [],
            nometeam: '',
            opt_teamleaders: [''],
            teamleader: null,
            note: '',
            nome_team: '',
            page_team: 1,
            esitoSalvataggio: '',
            content: {
                ops: [],
            },


        }
    },
    methods: {
        showTeamList() {
            this.loading = true;
            this.isBusy = true;
            axios
                .get('./php/lista_teams.php')
                .then(response => {
                    this.teams_list = response.data.lista_team;
                    this.opt_teamleaders = response.data.teamleaders;
                    this.totalRows = response.data.lista_team.length;
                    this.loading = false;
                    this.isBusy = false;
                });
        },

        inviaEmail(item) {

            this.teamSelezionato = item;

            $('#tabpanel1').addClass('show active');
            $('#tabpanel2').removeClass('show active');

            this.content = {
                ops: [
                    {
                        attributes: {color: "#0047b2"},
                        insert: "A tutti i membri del team " + item.nome_team.toUpperCase() + ","
                    },
                    {insert: "\n\n\n"},
                    {attributes: {italic: true, color: "#0047b2", bold: true}, insert: "La Direzione"},
                    {insert: "\n\n"},
                    {
                        attributes: {alt: "logo INC Ambiente e Territorio Srl"},
                        insert: {image: "https://www.incaet.it/wp-content/uploads/2019/05/thumbnail_Logo_INC-150x150.png"}
                    },
                    {insert: "\n\n"},
                    {attributes: {color: "gray", bold: true}, insert: "inc "},
                    {attributes: {color: "maroon", bold: true}, insert: "ambiente e territorio srl"},
                    {insert: "\n"},
                    {attributes: {color: "gray"}, insert: "Corso Roma 118 - 26900 Lodi"},
                    {insert: "\n"},
                    {attributes: {color: "gray"}, insert: "tel. +39 0371 421821"},
                    {insert: "\n"}, {
                        attributes: {bold: true, color: "#1155cc", link: "http://www.incaet.it/"},
                        insert: "www.incaet.it"
                    }, {insert: "\n"}
                ],
            };

            this.subjectEmail = '';
            this.quill.setContents(this.content);
            this.quill.on('text-change', () => {
            });

            $('#modalInviaEmail').modal('show');


            this.avanti = "Invia Email";
            this.current_page = 1;

        },

        emailSend() {

            if (this.current_page === 1) {

                $('#tabpanel2').addClass('show active');
                $('#tabpanel1').removeClass('show active');

                // Invia Email

                data_json = {
                    subject: this.subjectEmail,
                    body: this.quill.getContents(),
                    id_team: this.teamSelezionato['id_team'],
                };

                console.log(JSON.stringify(data_json));
                this.loadingEmail = true;

                const params = new URLSearchParams();
                params.append('data', JSON.stringify(data_json));
                const options = {
                    method: 'POST',
                    headers: {'content-type': 'application/x-www-form-urlencoded'},
                    data: params,
                    url: './php/invia_email_team.php',
                };
                axios(options)
                    .then(response => {
                        console.log(response.data.messaggio);
                        console.log(JSON.stringify(response.data.lista_destinatari));

                        this.showSuccessAlertEmail = true;
                        this.showErrorAlertEmail = false;

                        this.loadingEmail = false;
                    })
                    .catch(error => {
                        this.showErrorAlertEmail = true;
                        this.showSuccessAlertEmail = false;
                        console.error("ERRORE! ", response.data.messaggio);
                    });

                this.avanti = "Chiudi",
                    this.current_page = 2;
                return;
            }

            if (this.current_page === 2) {

                $('#modalInviaEmail').modal('hide');
                this.teamSelezionato = [];


            }


        },

        deleteItem(item) {
            this.showErrorDelete = false;
            this.showSuccessDelete = false;
            $('#ModalDelete').modal('show');
            this.nometeam = item.nome_team;
            this.id_team = item.id_team;
        },

        deleteTeam() {
            // cancellazione team
            // Mostra prima una finestra di conferma
            const params = new URLSearchParams();
            params.append('id_team', this.id_team);
            const options = {
                method: 'POST',
                headers: {'content-type': 'application/x-www-form-urlencoded'},
                data: params,
                url: './php/cancella_team.php',
            };
            axios(options)
                .then(response => {
                    console.log(response.data.messaggio);
                    if (parseInt(response.data.id_team) === -1) {
                        this.showErrorDelete = true;
                    } else {
                        this.showSuccessDelete = true;
                        this.id_utente = parseInt(response.data.id_team);
                        this.showTeamList();
                    }
                })
                .catch(error => {
                    this.showErrorDelete = true;
                    console.error("ERRORE! ", response.data.messaggio);
                });
        },

        closeModalDelete() {

            this.showErrorDelete = false;
            this.showSuccessDelete = false;
            $('#ModalDelete').modal('hide');

        },

        insertTeam() {

            this.id_team = 0;
            this.nome_team = '';
            this.note = '';
            this.teamleader = null;
            this.edit_add = "AGGIUNGI";
            $('#modalTeam').modal('show');
            this.avanti = "Avanti";
            this.indietro = "Chiudi";
            this.btnNextDisabled = true;

        },

        editTeam(item) {

            this.id_team = item.id_team;
            this.teamleader = item.id_teamleader;
            this.nome_team = item.nome_team;
            this.note = item.note;
            this.edit_add = "MODIFICA";
            $('#modalTeam').modal('show');
            this.avanti = "Avanti";
            this.indietro = "Chiudi";

        },

        attivaButton() {
            this.btnNextDisabled = (this.teamleader === null);
        },

        caricaListaTeamleader(id_teamleader) {

            axios
                .get('./php/new_user_list.php?id_teamleader=' + id_teamleader)
                .then(response => {
                    this.user_list = response.data.lista_utenti;
                    console.log(JSON.stringify(this.user_list, null, 2));
                    this.team_user_list = response.data.lista_teamleader;
                    console.log(JSON.stringify(this.team_user_list, null, 2));
                });

        },

        caricaTeam(id_team, id_teamleader) {

            axios
                .get('./php/load_team.php?id_team=' + id_team + '&id_teamleader=' + id_teamleader)
                .then(response => {
                    this.user_list = response.data.lista_utenti;
                    console.log(JSON.stringify(this.user_list, null, 2));
                    this.team_user_list = response.data.lista_utenti_team;
                    console.log(JSON.stringify(this.team_user_list, null, 2));
                });

        },

        nextTeam() {

            if (this.page_team === 1) {

                if (this.edit_add === "MODIFICA") {

                    this.caricaTeam(this.id_team, this.teamleader);

                } else {

                    this.caricaListaTeamleader(this.teamleader);
                }

                //console.log(JSON.stringify(this.user_list, null, 2));
                //console.log(JSON.stringify(this.team_user_list, null, 2));

                this.page_team = 2;
                $('#tabpanel2').addClass('show active');
                $('#tabpanel1').removeClass('show active');
                $('#tabpanel3').removeClass('show active');
                this.avanti = "Salva";
                this.indietro = "indietro";
                return;
            }

            if (this.page_team === 2) {


                this.page_team = 3;
                $('#tabpanel3').addClass('show active');
                $('#tabpanel2').removeClass('show active');
                $('#tabpanel1').removeClass('show active');
                this.avanti = "Chiudi";
                this.indietro = "indietro";
                this.salvaTeam();
                return;
            }

            if (this.page_team === 3) {

                $('#modalTeam').modal('hide');
                this.page_team = 1;
                $('#tabpanel1').addClass('show active');
                $('#tabpanel2').removeClass('show active');
                $('#tabpanel3').removeClass('show active');
                this.avanti = "Avanti";
                this.indietro = "Chiudi";

            }


        },

        previousTeam() {

            if (this.page_team === 3) {

                this.page_team = 2;
                $('#tabpanel2').addClass('show active');
                $('#tabpanel1').removeClass('show active');
                $('#tabpanel3').removeClass('show active');
                this.avanti = "Salva";
                this.indietro = "indietro";
                return;
            }

            if (this.page_team === 2) {

                this.page_team = 1;
                $('#tabpanel1').addClass('show active');
                $('#tabpanel2').removeClass('show active');
                $('#tabpanel3').removeClass('show active');
                this.avanti = "Avanti";
                this.indietro = "Chiudi";
                return;
            }

            if (this.page_team === 1) {

                $('#modalTeam').modal('hide');
                $('#tabpanel1').addClass('show active');
                $('#tabpanel2').removeClass('show active');
                $('#tabpanel3').removeClass('show active');
                this.avanti = "Avanti";
                this.indietro = "Chiudi";

            }

        },

        salvaTeam() {
            data_json = {
                id_team: this.id_team,
                nome_team: this.nome_team,
                note: this.note,
                user_list: this.team_user_list
            };

            console.log(JSON.stringify(data_json));
            const params = new URLSearchParams();
            params.append('data', JSON.stringify(data_json));
            const options = {
                method: 'POST',
                headers: {'content-type': 'application/x-www-form-urlencoded'},
                data: params,
                url: './php/salva_team.php',
            };
            axios(options)
                .then(response => {
                    console.log(response.data.messaggio);
                    this.id_team = parseInt(response.data.id_team);
                    this.esitoSalvataggio = response.data.messaggio;
                    this.showTeamList();

                })
                .catch(error => {
                    console.error("ERRORE! ", response.data.messaggio);
                    this.esitoSalvataggio = "Qualcosa è andata storta!";

                });

        },

        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length
            this.currentPage = 1
        },

        orderList() {
            this.list = this.list.sort((one, two) => {
                return one.order - two.order;
            });
        },
        onMove({relatedContext, draggedContext}) {
            const relatedElement = relatedContext.element;
            const draggedElement = draggedContext.element;
            return (
                (!relatedElement || !relatedElement.fixed) && !draggedElement.fixed && !draggedElement.teamleader
            );
        },


    },
    mounted() {
        this.quill = new Quill('#editor', {
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{list: 'ordered'}, {list: 'bullet'}],
                    [{'indent': '-1'}, {'indent': '+1'}],
                    [{'color': []}, {'background': []}]
                ]
            },
            placeholder: 'Compose an epic...',
            theme: 'snow'
        });

        this.quill.setContents(this.content);
        this.quill.on('text-change', () => {
        });

        this.showTeamList();

    },

    computed: {
        dragOptions() {
            return {
                animation: 0,
                group: "description",
                disabled: !this.editable,
                ghostClass: "ghost"
            };
        },
        listString() {
            return JSON.stringify(this.user_list, null, 2);
        },
        list2String() {
            return JSON.stringify(this.team_list, null, 2);
        }
    },
    watch: {
        isDragging(newValue) {
            if (newValue) {
                this.delayedDragging = true;
                return;
            }
            this.$nextTick(() => {
                this.delayedDragging = false;
            });
        }
    },

})