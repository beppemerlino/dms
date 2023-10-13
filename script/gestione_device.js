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

            utentiList: [''],
            currentPage: 1,
            perPage: 10,
            file_img: null,

            opt_gruppo: [
                { text: 'SuperAdmins', value: 1 },
                { text: 'Admins', value: 2 },
                { text: 'SuperUsers', value: 3 },
                { text: 'Users', value: 4 }
            ],

            opt_typehd:[
                { text: 'HARD DISK', value: 'HARD DISK' },
                { text: 'SSD', value: 'SSD' }
            ],


            opt_raid:[
                { text: 'RAID 1', value: 'RAID 1' },
                { text: 'RAID 2', value: 'RAID 2' },
                { text: 'RAID 3', value: 'RAID 3' },
                { text: 'RAID 4', value: 'RAID 4' },
                { text: 'RAID 5', value: 'RAID 5' },
                { text: 'RAID 6', value: 'RAID 6' }
            ],


            /** Campi v-model del Device ############################ */

            id_device: 0,
            nome: '',
            vendor: '',
            model: '',
            foto: '',
            id_workstation: null,
            serial_number: '',
            rif_cespite: '',
            part_number: '',
            description: '',

            opt_formfactory: [
                { text: 'Desktop', value: 'Desktop' },
                { text: 'Rack', value: 'Rack' },
                { text: 'Tower', value: 'Tower' },
                { text: 'Mini Tower', value: 'Mini Tower' },
                { text: 'Tiny Client', value: 'Tiny Client' },
                { text: 'Maxy Tower', value: 'Maxy Tower' }
            ],


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
            messaggio: '',

            aggiungi: 'AGGIUNGI',

            // Tabella
            isBusy: false,
            totalRows: 0,
            rowsInPage: 20,
            pageNumber: 1,

            device_list: [],

            fields_table_nas: [
                {key: "nome", label: "NOME", sortable: true, filterByFormatted: true },
                {key: "vendor", label: "MARCA", sortable: true, filterByFormatted: true },
                {key: "model", label: "MODELLO", sortable: true, filterByFormatted: true },
                {key: "nome_workstation", label: "WORKSTATION", sortable: true, filterByFormatted: true },
                {key: "foto", label: "FOTO"},
                {key: "serial_number", label: "SERIALE", sortable: true, filterByFormatted: true },
                {key: "description", label: "DESCRIZIONE", sortable: true, filterByFormatted: true },
                {key: "actions", label: ""},
            ],

            //Modal
            titolo_modal: '',

            // Filtro
            filter: '',

            opt_workstation: [],

        }
    },
    methods: {

        listaWorkstation() {
            axios
                .get('./php/lista_option_workstation.php')
                .then(response => {
                    this.opt_workstation = response.data.lista;
                });
        },

        listaDevice(){
            this.device_list = [];
            this.isBusy = true;
            this.totalRows = 0;

            const options = {
                method: 'POST',
                headers: { 'content-type': 'application/x-www-form-urlencoded' },
                url: './php/lista_device.php',
            };
            axios(options)
                .then(response => {

                    if (response.status === 200){

                        this.device_list = response.data.lista_device;
                        this.totalRows = this.device_list.length;

                    }

                })
                .finally(response => {
                    this.isBusy = false;
                });

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

        uploadImage(event) {
            const { canvas } = this.$refs.cropper.getResult();
            if (canvas) {
                const formData = new FormData();

                formData.append('nome_file', this.nome_file);
                formData.append('storage', '../assets/devices');
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
                        this.foto = 'devices/' + this.nome_file;
                        this.updateImage();
                        console.log(messaggio);
                    })
                }, 'image/jpeg');
            }
        },

        onChange({ coordinates, image }) {
            console.log(coordinates, image);
            this.result = {
                coordinates,
                image
            };
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

        insertDevice(){

            this.id_device = 0;

            this.nome = '';
            this.vendor = '';
            this.model = '';
            this.foto = 'devices/device.jpg';
            this.id_workstation = '';
            this.serial_number = '';
            this.rif_cespite = '';
            this.part_number = '';
            this.description = '';

            this.showErrorAlert = false;
            this.showSuccessAlert = false;

            this.aggiungi = "AGGIUNGI";
            this.reset();
            $('#modalEditDevice').modal('show');
        },

        editDevice(item){

            this.id_device              = item.id_device        ;
            this.nome                   = item.nome             ;
            this.vendor                 = item.vendor           ;
            this.model                  = item.model            ;
            this.foto                   = item.foto             ;
            this.id_workstation         = item.id_workstation   ;
            this.serial_number          = item.serial_number    ;
            this.rif_cespite            = item.rif_cespite      ;
            this.part_number            = item.part_number      ;
            this.description            = item.description      ;

            this.showErrorAlert = false;
            this.showSuccessAlert = false;


            this.reset();
            this.aggiungi = "MODIFICA";
            $('#modalEditDevice').modal('show');
        },

        deleteItem(item){
            this.showErrorDelete = false;
            this.showSuccessDelete= false;
            $('#ModalDelete').modal('show');
            this.nome = item.nome;
            this.id_device = item.id_device;
        },

        closeModalDelete(){

            this.showErrorDelete = false;
            this.showSuccessDelete= false;
            $('#ModalDelete').modal('hide');

        },

        openAvatar(item){
            this.avatar = item.foto;
            $('#modalAvatar').modal('show');
        },

        deleteDevice(){
            // cancellazione device
            // Mostra prima una finestra di conferma
            const params = new URLSearchParams();
            params.append('id_device', this.id_device);
            const options = {
                method: 'POST',
                headers: { 'content-type': 'application/x-www-form-urlencoded' },
                data: params,
                url: './php/cancella_device.php',
            };
            axios(options)
                .then(response => {
                    console.log(response.data.messaggio);

                    this.showSuccessDelete = true;
                    this.id_device = parseInt(response.data.id_device);
                    this.listaDevice();

                })
                .catch(error => {
                    this.showErrorDelete = true;
                    console.error("ERRORE! ", response.data.messaggio);
                });
        },

        salvaDati(){
            // Salva i Dati


                data_json = {
                    id_device        : this.id_device        ,
                    nome             : this.nome             ,
                    vendor           : this.vendor           ,
                    model            : this.model            ,
                    foto             : this.foto             ,
                    id_workstation   : this.id_workstation   ,
                    serial_number    : this.serial_number    ,
                    rif_cespite      : this.rif_cespite      ,
                    part_number      : this.part_number      ,
                    description      : this.description
                };

                console.log(JSON.stringify(data_json));

                const params = new URLSearchParams();
                params.append('data', JSON.stringify(data_json));
                const options = {
                    method: 'POST',
                    headers: { 'content-type': 'application/x-www-form-urlencoded' },
                    data: params,
                    url: './php/salva_device.php',
                };
                axios(options)
                    .then(response => {
                        console.log(response.data.messaggio);
                        this.messaggio = response.data.messaggio;
                        if (this.messaggio !== "Device Modificato" && this.messaggio !== "Device Inserito"){
                            this.showErrorAlert = true;
                        } else {
                            this.showSuccessAlert = true;
                            this.id_device = parseInt(response.data.id);
                            this.listaDevice();
                        }
                    })
                    .catch(error => {
                        this.showErrorAlert = true;
                        console.error("ERRORE! ", response.data.messaggio);
                    });


        },

        utenteNonLoggato(){
            //Kikka l'utente fuori dalla pagina in modo da forzare il login
            window.open("/", "_self");
        },

        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length;
            this.currentPage = 1;
        },
    },
    mounted(){
        this.listaDevice();
        this.listaWorkstation();

    },
    destroyed() {
        // Revoke the object URL, to allow the garbage collector to destroy the uploaded before file
        if (this.image.src) {
            URL.revokeObjectURL(this.image.src);
        }
    },
})
