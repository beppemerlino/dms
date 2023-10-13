function getMimeType(file, fallback = null) {
    const byteArray = (new Uint8Array(file)).subarray(0, 4);
    let header = '';
    for (let i = 0; i < byteArray.length; i++) {
        header += byteArray[i].toString(16);
    }
    switch (header) {
        case "89504e47":
            return "image/png";
        case "47494638":
            return "image/gif";
        case "ffd8ffe0":
        case "ffd8ffe1":
        case "ffd8ffe2":
        case "ffd8ffe3":
        case "ffd8ffe8":
            return "image/jpeg";
        default:
            return fallback;
    }
}


new Vue({
    el: '#app',
    data () {
        return {

            content: {"ops":[],},
            membroSelezionato: [],

            btnPrvDisabled: false,
            btnNextDisabled: false,
            indietro: 'Chiudi',
            avanti: 'Avanti',
            current_page: 1,
            content_email: '',

            avatar: '',
            image:'',
            nome_file: '',
            result: {
                coordinates: null,
                image: null,
            },
            mainProps: { blank: true, blankColor: '#777', width: 250, height: 250, class: 'm1' },
            edit_add: 'Aggiungi',
            id_utente: 0,
            utentiList: [''],
            filter: '',
            currentPage: 1,
            totalRows: 0,
            perPage: 10,
            isBusy: false,

            opt_gruppo: [
                { text: 'SuperAdmins', value: 1 },
                { text: 'Admins', value: 2 },
                { text: 'SuperUsers', value: 3 },
                { text: 'Users', value: 4 }
            ],

            nickname: '',
            title: '',
            firstname: '',
            lastname: '',
            group: 3,
            gender: 1,
            mansione: '',
            email: '',
            email2: '',
            phone: '',
            photo: '',
            file_img: '',
            lang: '',
            note: '',
            active: 'non_attivo',
            gruppo_admin: false,
            super_admin: false,
            password: '',
            confirm_password: '',
            edit_password: '',
            edit_confirm_password: '',
            showErrorAlert: false,
            showSuccessAlert: false,
            showErrorAlert1: false,
            showSuccessAlert1: false,
            showErrorAlertEmail: false,
            showSuccessAlertEmail: false,
            showErrorDelete: false,
            showSuccessDelete: false,
            loadingEmail: false,
            subjectEmail: '',
            tipo_lavoratore: 1,
            opt_tipi_lavoratore: [],
            aggiungi: 'AGGIUNGI',
            opt_gender: [
                { value: '1', text: 'MASCHIO' },
                { value: '2', text: 'FEMMINA' }
            ],
            fields_utenti: [
                { key: 'foto', label: 'AVATAR', tdClass: 'text-center'},
                { key: 'username', label: 'NICKNAME', sortable: true, filterByFormatted: true },
                { key: 'nome', label: 'NOME', sortable: true, filterByFormatted: true },
                { key: 'cognome', label: 'COGNOME', sortable: true, filterByFormatted: true },
                { key: 'sesso', label: 'SESSO', sortable: true, filterByFormatted: true },
                { key: 'group', label: 'GRUPPO', sortable: true, filterByFormatted: true },
                { key: 'attivo', label: 'ATTIVO', sortable: true, filterByFormatted: true },
                { key: 'lavoratore', label: 'LAVORATORE', sortable: true, filterByFormatted: true },
                { key: 'Azioni', label: 'Azioni'}
            ],
        }
    },
    methods: {
        inviaEmail(item){

            this.membroSelezionato = item;

            $('#tabpanel1').addClass('show active');
            $('#tabpanel2').removeClass('show active');

            this.content = {ops:[
                    {attributes: {color:"#0047b2"}, insert: "Ciao " + item.nome + ","},
                    {insert: "\n\n\n"},
                    {attributes: {italic: true, color: "#0047b2", bold: true}, insert: "La Direzione"},
                    {insert: "\n\n"},
                    {attributes:{ alt: "logo INC Ambiente e Territorio Srl"}, insert:{image: "https://www.incaet.it/wp-content/uploads/2019/05/thumbnail_Logo_INC-150x150.png"}},
                    {insert: "\n\n"},
                    {attributes:{ color: "gray", bold: true}, insert: "inc "},
                    {attributes:{ color: "maroon", bold: true}, insert: "ambiente e territorio srl"},
                    {insert: "\n"},
                    {attributes: {color: "gray"},insert: "Corso Roma 118 - 26900 Lodi"},
                    {insert: "\n"},
                    {attributes: {color: "gray"}, insert: "tel. +39 0371 421821"},
                    {insert: "\n"}, {attributes: {bold: true, color: "#1155cc", link: "http://www.incaet.it/"}, insert: "www.incaet.it"}, {insert: "\n"}
                ],
            };

            this.subjectEmail = '';
            this.quill.setContents(this.content);
            this.quill.on('text-change', () => {});

            $('#modalInviaEmail').modal('show');


            this.avanti = "Invia Email";
            this.current_page = 1;

        },


        emailSend(){

            if (this.current_page === 1){

                $('#tabpanel2').addClass('show active');
                $('#tabpanel1').removeClass('show active');

                // Invia Email

                data_json = {
                    subject: this.subjectEmail,
                    body: this.quill.getContents(),
                    nome: this.membroSelezionato['nome'],
                    cognome: this.membroSelezionato['cognome'],
                    email: this.membroSelezionato['email'],
                };

                console.log(JSON.stringify(data_json));
                this.loadingEmail = true;

                 const params = new URLSearchParams();
                 params.append('data', JSON.stringify(data_json));
                 const options = {
                    method: 'POST',
                    headers: { 'content-type': 'application/x-www-form-urlencoded' },
                    data: params,
                    url: './php/invia_email_utente.php',
                    };
                 axios(options)
                 .then(response => {
                    console.log(response.data.messaggio);
                    if (parseInt(response.data.id_utente) === -1){
                        this.showErrorAlertEmail = true;
                        this.showSuccessAlertEmail = false;
                    } else {
                        this.showSuccessAlertEmail = true;
                        this.showErrorAlertEmail = false;
                    }
                    this.loadingEmail = false;
                    })
                 .catch(error => {
                         this.showErrorAlert1 = true;
                         console.error("ERRORE! ", response.data.messaggio);
                    });



                this.avanti = "Chiudi",
                this.current_page = 2;
                return;
            }

            if (this.current_page === 2){

                $('#modalInviaEmail').modal('hide');
                this.membroSelezionato = [];

            }

        },

        openAvatar(item){
            this.avatar = item.photo;
            $('#modalAvatar').modal('show');
        },

        reset() {
            this.$refs['file'].reset();
            this.nome_file = '';
            this.image = {
                src: null,
                type: null,
            };
        },

        updateImage() {
            var image = document.getElementById("img");
            image.src = image.src.split("?")[0] + "?" + new Date().getTime();
        },

        loadImage(event) {
            // Reference to the DOM input element
            const { files } = event.target;
            // Ensure that you have a file before attempting to read it
            if (files && files[0]) {
                // 1. Revoke the object URL, to allow the garbage collector to destroy the uploaded before file
                this.nome_file = files[0].name;
                if (this.image.src) {
                    URL.revokeObjectURL(this.image.src)
                }
                // 2. Create the blob link to the file to optimize performance:
                const blob = URL.createObjectURL(files[0]);

                // 3. The steps below are designated to determine a file mime type to use it during the
                // getting of a cropped image from the canvas. You can replace it them by the following string,
                // but the type will be derived from the extension and it can lead to an incorrect result:
                //
                // this.image = {
                //    src: blob;
                //    type: files[0].type
                // }

                // Create a new FileReader to read this image binary data
                const reader = new FileReader();
                // Define a callback function to run, when FileReader finishes its job
                reader.onload = (e) => {
                    // Note: arrow function used here, so that "this.image" refers to the image of Vue component
                    this.image = {
                        // Set the image source (it will look like blob:http://example.com/2c5270a5-18b5-406e-a4fb-07427f5e7b94)
                        src: blob,
                        // Determine the image type to preserve it during the extracting the image from canvas:
                        type: getMimeType(e.target.result, files[0].type),
                    };
                };
                // Start the reader job - read file as a data url (base64 format)
                reader.readAsArrayBuffer(files[0]);
            }
        },

        onChange({ coordinates, image }) {
            console.log(coordinates, image);
            this.result = {
                coordinates,
                image
            };
        },

        uploadImage(event) {
            const { canvas } = this.$refs.cropper.getResult();
            if (canvas) {
                const formData = new FormData();

                formData.append('nome_file', this.nome_file);
                canvas.toBlob((blob) => {
                    let messaggio = '';
                    formData.append('file', blob);

                    axios.post('php/upload_avatar.php',
                        formData,
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        }
                    ).then(function(data){
                        messaggio = data.data.message;
                        console.log(data.data);
                    }).catch(function(){
                        console.log('FAILURE!!');
                    }).finally(() => {
                        this.photo = './assets/avatars/' + this.nome_file;
                        this.updateImage();
                        console.log(messaggio);
                    })
                }, 'image/jpeg');
            }
        },

        showUserList(){
            this.loading = true;
            this.isBusy = true;
            axios
                .get('./php/lista_utenti.php')
                .then(response => {
                    this.utentiList = response.data.lista_utenti;
                    this.totalRows = response.data.lista_utenti.length;
                    this.gruppo_admin = response.data.gruppo_admin;
                    this.super_admin = response.data.super_admin;
                    this.loading = false;
                    this.isBusy = false;
                });
        },

        showTipiLavoratore(){

            axios
                .get('./php/lista_tipi_lavoratore.php')
                .then(response => {
                    this.opt_tipi_lavoratore = response.data;
                });
        },

        insertUser(){
              // Inserimento nuovo utente
              // Mostra la finestra Modal di Inserimento nuovo Utente

            this.id_utente = 0;
            this.edit_add = 'Aggiungi';
            this.id_utente = '0';
            this.nickname = '';
            this.title = '';
            this.firstname = '';
            this.lastname = '';
            this.group = 3;
            this.gender = 1;
            this.mansione = '';
            this.email = '';
            this.email2 = '';
            this.phone = '';
            this.photo = './assets/avatars/anonimus.jpg';
            this.file_img = null;
            this.lang = 'it';
            this.note = '';
            this.tipo_lavoratore = 1;
            this.active = 'attivo';
            this.password = '';
            this.confirm_password = '';
            this.showErrorAlert = false;
            this.showSuccessAlert = false;

            this.aggiungi = "AGGIUNGI";
            this.reset();
            $('#modalEditUser').modal('show');
        },

        editUser(item){
            // Modifica un utente
            // Mostra la finestra Modal di Modifica Utente
            this.edit_add = 'Modifica';
            this.id_utente = parseInt(item.id);
            this.nickname = item.username;
            this.title = item.titolo;
            this.firstname = item.nome;
            this.lastname = item.cognome;
            this.group = parseInt(item.group);
            this.gender = parseInt(item.sesso);
            this.mansione = item.mansione;
            this.email = item.email;
            this.email2 = item.email2;
            this.phone = item.telefono;
            this.photo = './assets/' + item.foto;
            this.file_img = item.foto;
            this.lang = item.lang;
            this.note = item.note;
            this.tipo_lavoratore = parseInt(item.tipo_lavoratore);
            this.password = '';
            this.confirm_password = '';
            this.showErrorAlert = false;
            this.showSuccessAlert = false;
            if(item.attivo === '1') {
                this.active = "attivo";
            } else {
                this.active = "non_attivo";
            }
            this.reset();
            this.aggiungi = "MODIFICA";
            $('#modalEditUser').modal('show');
        },

        deleteItem(item){
            this.showErrorDelete = false;
            this.showSuccessDelete= false;
            $('#ModalDelete').modal('show');
            this.nickname = item.username;
            this.id_utente = item.id;
        },

        closeModalDelete(){

            this.showErrorDelete = false;
            this.showSuccessDelete= false;
            $('#ModalDelete').modal('hide');

        },

        deleteUser(){
            // cancellazione utente
            // Mostra prima una finestra di conferma
            const params = new URLSearchParams();
            params.append('id_utente', this.id_utente);
            const options = {
                method: 'POST',
                headers: { 'content-type': 'application/x-www-form-urlencoded' },
                data: params,
                url: './php/cancella_utente.php',
            };
            axios(options)
                .then(response => {
                    console.log(response.data.messaggio);
                    if (parseInt(response.data.id_utente) === -1){
                        this.showErrorDelete = true;
                    } else {
                        this.showSuccessDelete = true;
                        this.id_utente = parseInt(response.data.id_utente);
                        this.showUserList();
                    }
                })
                .catch(error => {
                    this.showErrorDelete = true;
                    console.error("ERRORE! ", response.data.messaggio);
                });
        },

        editPassword(item){
            // Modifica solo la password di un utente
            // Mostra la finestra Modal per modificare la password dell'utente
            this.id_utente = item.id;
            this.edit_password = '';
            this.edit_confirm_password = '';
            this.showErrorAlert1 = false;
            this.showSuccessAlert1 = false;
            this.reset();
            $('#modalEditPassword').modal('show');
        },
        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length
            this.currentPage = 1
        },
        onChangeFileUpload(){
            this.file_img = this.$refs['file_input'].files[0];
        },

        salvaDati(){
            // Salva i Dati
            if (this.password === this.confirm_password){

                data_json = {
                    id: parseInt(this.id_utente),
                    username: this.nickname,
                    password: this.password,
                    nome: this.firstname,
                    cognome: this.lastname,
                    sesso: parseInt(this.gender),
                    titolo: this.title,
                    mansione: this.mansione,
                    email: this.email,
                    email2: this.email2,
                    group: parseInt(this.group),
                    telefono: this.phone,
                    note: this.note,
                    tipo_lavoratore: parseInt(this.tipo_lavoratore),
                    attivo: (this.active === 'attivo')? 1 : 2,
                    foto: this.photo,
                    lang: this.lang
                };

                console.log(JSON.stringify(data_json));
                const params = new URLSearchParams();
                params.append('data', JSON.stringify(data_json));
                const options = {
                    method: 'POST',
                    headers: { 'content-type': 'application/x-www-form-urlencoded' },
                    data: params,
                    url: './php/salva_utente.php',
                };
                axios(options)
                    .then(response => {
                        console.log(response.data.messaggio);
                        if (parseInt(response.data.id_utente) === -1){
                            this.showErrorAlert = true;
                        } else {
                            this.showSuccessAlert = true;
                            this.id_utente = parseInt(response.data.id_utente);
                            this.showUserList();
                        }
                    })
                    .catch(error => {
                        this.showErrorAlert = true;
                        console.error("ERRORE! ", response.data.messaggio);
                    });

            } else {

                this.showErrorAlert = true;

            }

        },

        salvaPassword(){
            // Salva Paswword
            if (this.edit_password === this.edit_confirm_password){
                data_json = {
                    id: parseInt(this.id_utente),
                    password: this.edit_password
                };

                console.log(JSON.stringify(data_json));
                const params = new URLSearchParams();
                params.append('data', JSON.stringify(data_json));
                const options = {
                    method: 'POST',
                    headers: { 'content-type': 'application/x-www-form-urlencoded' },
                    data: params,
                    url: './php/salva_password_utente.php',
                };
                axios(options)
                    .then(response => {
                        console.log(response.data.messaggio);
                        if (parseInt(response.data.id_utente) === -1){
                            this.showErrorAlert1 = true;
                        } else {
                            this.showSuccessAlert1 = true;
                            this.id_utente = parseInt(response.data.id_utente);
                        }
                    })
                    .catch(error => {
                        this.showErrorAlert1 = true;
                        console.error("ERRORE! ", response.data.messaggio);
                    });


            } else {
                this.showErrorAlert1 = true;
            }
        }
    },
    mounted(){



        this.quill = new Quill('#editor', {
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    [{ 'color': [] }, { 'background': [] }]
                ]
            },
            placeholder: 'Compose an epic...',
            theme: 'snow'
        });

        this.quill.setContents(this.content);
        this.quill.on('text-change', () => {});

        this.showUserList();
        this.showTipiLavoratore();

    },
    destroyed() {
        // Revoke the object URL, to allow the garbage collector to destroy the uploaded before file
        if (this.image.src) {
            URL.revokeObjectURL(this.image.src);
        }
    },
})
