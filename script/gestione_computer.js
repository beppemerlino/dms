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


            /** Campi v-model del PC ############################ */

            id_computer: 0,
            nome: '',
            vendor: '',
            model: '',
            cpu_1: '',
            cpu_2: '',
            operative_system: '',
            keyboard: '',
            mouse: '',
            foto: '',
            id_workstation: '',
            serial_number: '',
            rif_cespite: '',
            part_number: '',
            form_factory: '',
            ram_size: '',
            primary_disk_size: '',
            secondary_disk_size: '',
            dvd_rom: '',
            video_card: '',
            bluetooth: '',
            ethernet_1: '',
            ethernet_2: '',
            ip_address_1: '',
            ip_address_2: '',
            mac_ethernet_1: '',
            mac_ethernet_2: '',
            hdmi_port: '',
            dvi_port: '',
            display_port: '',
            mdisplay_port: '',
            thunderbolt_port: '',
            wifi_card: '',
            audio_card: '',
            num_usb: '',
            num_usb_3: '',
            power_supply: '',
            power_cell: '',

            opt_formfactory: [
                { text: 'Desktop', value: 'Desktop' },
                { text: 'Laptop', value: 'Laptop' },
                { text: 'Tower', value: 'Tower' },
                { text: 'Mini Tower', value: 'Mini Tower' },
                { text: 'Tiny Client', value: 'Tiny Client' },
                { text: 'All in One', value: 'All in One' },
                { text: 'Maxy Tower', value: 'Maxy Tower' },
                { text: 'Rack', value: 'Rack' }
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

            computer_list: [],

            fields_table_computer: [
                {key: "nome", label: "NOME", sortable: true, filterByFormatted: true },
                {key: "vendor", label: "MARCA", sortable: true, filterByFormatted: true },
                {key: "model", label: "MODELLO", sortable: true, filterByFormatted: true },
                {key: "nome_workstation", label: "WORKSTATION", sortable: true, filterByFormatted: true },
                {key: "foto", label: "FOTO"},
                {key: "serial_number", label: "SERIALE", sortable: true, filterByFormatted: true },
                {key: "operative_system", label: "SISTEMA OPERATIVO", sortable: true, filterByFormatted: true },
                {key: "actions", label: ""},
            ],

            //Modal
            titolo_modal: '',

            // Filtro
            filter: '',

            opt_workstation: [],

            computer_sw: '',

            software_list: [''],

            fields_table_sw_computer: [
                {key: "vendor", label: "MARCA", sortable: true, filterByFormatted: true },
                {key: "model", label: "MODELLO", sortable: true, filterByFormatted: true },
                {key: "serial_number", label: "SERIALE", sortable: true, filterByFormatted: true },
                {key: "description", label: "DESCRIZIONE", sortable: true, filterByFormatted: true },
                {key: "expired_date", label: "DATA SCADENZA", sortable: true, filterByFormatted: true }
            ],

            totalRows_sw: 0,
            rowsInPage_sw: 20,
            pageNumber_sw: 1,
            isBusy_sw: false,

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

        listaSoftware(item){

            this.software_list = [];
            this.isBusy_sw = true;
            this.totalRows_sw = 0;

            data_json = {
                id_computer: item.id_computer
            };

            console.log(JSON.stringify(data_json));

            const params = new URLSearchParams();
            params.append('data', JSON.stringify(data_json));

            const options = {
                method: 'POST',
                headers: { 'content-type': 'application/x-www-form-urlencoded' },
                data: params,
                url: './php/lista_sw_computer.php',
            };
            axios(options)
                .then(response => {

                    if (response.status === 200){

                            this.software_list = response.data.lista;
                            this.totalRows_sw = this.software_list.length;

                    }

                })
                .finally(response => {
                    this.isBusy_sw = false;
                });

        },

        listaComputer(){
            this.computer_list = [];
            this.isBusy = true;
            this.totalRows = 0;

            const options = {
                method: 'POST',
                headers: { 'content-type': 'application/x-www-form-urlencoded' },
                url: './php/lista_computer.php',
            };
            axios(options)
                .then(response => {

                    if (response.status === 200){

                        this.computer_list = response.data.lista_pc;
                        this.totalRows = this.computer_list.length;

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
                formData.append('storage', '../assets/pcs');
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
                        this.foto = 'pcs/' + this.nome_file;
                        this.updateImage();
                        console.log(messaggio);
                    })
                }, 'image/jpeg');
            }
        },

        onChange({ coordinates, image }) {
            //console.log(coordinates, image);
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

        insertPc(){

            this.id_computer = 0;
            this.nome = '';
            this.vendor = '';
            this.model = '';
            this.cpu_1 = '';
            this.cpu_2 = '';
            this.operative_system = '';
            this.keyboard = '';
            this.mouse = '';
            this.foto = 'pcs/computer.jpg';
            this.file_img = null;
            this.id_workstation = null;
            this.serial_number = '';
            this.rif_cespite = '';
            this.part_number = '';
            this.form_factory = null;
            this.ram_size = '';
            this.primary_disk_size = '';
            this.secondary_disk_size = '';
            this.dvd_rom = '';
            this.video_card = '';
            this.bluetooth = '';
            this.ethernet_1 = '';
            this.ethernet_2 = '';
            this.ip_address_1 = '';
            this.ip_address_2 = '';
            this.mac_ethernet_1 = '';
            this.mac_ethernet_2 = '';
            this.hdmi_port = '';
            this.dvi_port = '';
            this.display_port = '';
            this.mdisplay_port = '';
            this.thunderbolt_port = '';
            this.wifi_card = '';
            this.audio_card = '';
            this.num_usb = '';
            this.num_usb_3 = '';
            this.power_supply = '';
            this.power_cell = '';

            this.showErrorAlert = false;
            this.showSuccessAlert = false;

            this.aggiungi = "AGGIUNGI";
            this.reset();
            $('#modalEditPc').modal('show');
        },

        viewItemSw(item){

            this.listaSoftware(item);
            this.computer_sw = item.nome;
            $('#modalSwPc').modal('show');

        },

        editPc(item){

            this.id_computer            = item.id_computer         ;
            this.nome                   = item.nome                ;
            this.vendor                 = item.vendor              ;
            this.model                  = item.model               ;
            this.cpu_1                  = item.cpu_1               ;
            this.cpu_2                  = item.cpu_2               ;
            this.operative_system       = item.operative_system    ;
            this.keyboard               = item.keyboard            ;
            this.mouse                  = item.mouse               ;
            this.foto                   = item.foto                ;
            this.file_img               = item.file_img            ;
            this.id_workstation         = item.id_workstation      ;
            this.serial_number          = item.serial_number       ;
            this.rif_cespite            = item.rif_cespite         ;
            this.part_number            = item.part_number         ;
            this.form_factory           = item.form_factory        ;
            this.ram_size               = item.ram_size            ;
            this.primary_disk_size      = item.primary_disk_size   ;
            this.secondary_disk_size    = item.secondary_disk_size ;
            this.dvd_rom                = item.dvd_rom             ;
            this.video_card             = item.video_card          ;
            this.bluetooth              = item.bluetooth           ;
            this.ethernet_1             = item.ethernet_1          ;
            this.ethernet_2             = item.ethernet_2          ;
            this.ip_address_1           = item.ip_address_1        ;
            this.ip_address_2           = item.ip_address_2        ;
            this.mac_ethernet_1         = item.mac_ethernet_1      ;
            this.mac_ethernet_2         = item.mac_ethernet_2      ;
            this.hdmi_port              = item.hdmi_port           ;
            this.dvi_port               = item.dvi_port            ;
            this.display_port           = item.display_port        ;
            this.mdisplay_port          = item.mdisplay_port       ;
            this.thunderbolt_port       = item.thunderbolt_port    ;
            this.wifi_card              = item.wifi_card           ;
            this.audio_card             = item.audio_card          ;
            this.num_usb                = item.num_usb             ;
            this.num_usb_3              = item.num_usb_3           ;
            this.power_supply           = item.power_supply        ;
            this.power_cell             = item.power_cell          ;

            this.showErrorAlert = false;
            this.showSuccessAlert = false;


            this.reset();
            this.aggiungi = "MODIFICA";
            $('#modalEditPc').modal('show');
        },

        deleteItem(item){
            this.showErrorDelete = false;
            this.showSuccessDelete= false;
            $('#ModalDelete').modal('show');
            this.nome = item.nome;
            this.id_computer = item.id_computer;
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

        deletePc(){
            // cancellazione utente
            // Mostra prima una finestra di conferma
            const params = new URLSearchParams();
            params.append('id_computer', this.id_computer);
            const options = {
                method: 'POST',
                headers: { 'content-type': 'application/x-www-form-urlencoded' },
                data: params,
                url: './php/cancella_pc.php',
            };
            axios(options)
                .then(response => {
                    console.log(response.data.messaggio);

                    this.showSuccessDelete = true;
                    this.id_computer = parseInt(response.data.id_computer);
                    this.listaComputer();

                })
                .catch(error => {
                    if (axios.isAxiosError(error) && error.response) {
                        this.showErrorAlert = true;
                        this.messaggio = error.response;
                    }
                });
        },

        salvaDati(){
            // Salva i Dati
                data_json = {
                    id_computer: parseInt(this.id_computer),
                    nome: this.nome,
                    vendor: this.vendor,
                    model: this.model,
                    cpu_1: this.cpu_1,
                    cpu_2: this.cpu_2,
                    operative_system: this.operative_system,
                    keyboard: this.keyboard,
                    mouse: this.mouse,
                    foto: this.foto,
                    id_workstation: this.id_workstation,
                    serial_number: this.serial_number,
                    rif_cespite: this.rif_cespite,
                    part_number: this.part_number,
                    form_factory: this.form_factory,
                    ram_size: this.ram_size,
                    primary_disk_size: this.primary_disk_size,
                    secondary_disk_size: this.secondary_disk_size,
                    dvd_rom: this.dvd_rom,
                    video_card: this.video_card,
                    bluetooth: this.bluetooth,
                    ethernet_1: this.ethernet_1,
                    ethernet_2: this.ethernet_2,
                    ip_address_1: this.ip_address_1,
                    ip_address_2: this.ip_address_2,
                    mac_ethernet_1: this.mac_ethernet_1,
                    mac_ethernet_2: this.mac_ethernet_2,
                    hdmi_port: this.hdmi_port,
                    dvi_port: this.dvi_port,
                    display_port: this.display_port,
                    mdisplay_port: this.mdisplay_port,
                    thunderbolt_port: this.thunderbolt_port,
                    wifi_card: this.wifi_card,
                    audio_card: this.audio_card,
                    num_usb: this.num_usb,
                    num_usb_3: this.num_usb_3,
                    power_supply: this.power_supply,
                    power_cell: this.power_cell
                };

                console.log(JSON.stringify(data_json));

                const params = new URLSearchParams();
                params.append('data', JSON.stringify(data_json));
                const options = {
                    method: 'POST',
                    headers: { 'content-type': 'application/x-www-form-urlencoded' },
                    data: params,
                    url: './php/salva_computer.php',
                };
                axios(options)
                    .then(response => {
                        console.log(response.data.messaggio);
                        this.messaggio = response.data.messaggio;

                       if (this.messaggio !== "Pc Modificato" && this.messaggio !== "Pc Inserito"){
                           this.showErrorAlert = true;

                       } else {
                           this.showSuccessAlert = true;
                           this.id_computer = parseInt(response.data.id);
                           this.listaComputer();
                       }

                    })
                    .catch(error => {

                            if (axios.isAxiosError(error) && error.response) {
                                this.showErrorAlert = true;
                                this.messaggio = error.response;
                            }

                    });


        },

        onFiltered(filteredItems) {

            this.totalRows = filteredItems.length;
            this.currentPage = 1;
        },


    },

    mounted(){
        this.listaComputer();
        this.listaWorkstation();

        //$('#modalEditSave').modal('show');

    },
    destroyed() {
        // Revoke the object URL, to allow the garbage collector to destroy the uploaded before file
        if (this.image.src) {
            URL.revokeObjectURL(this.image.src);
        }
    },
})
