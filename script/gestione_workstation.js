new Vue({
    el: '#app',
    data () {
        return {

            // Tabella
            isBusy: false,
            totalRows: 0,
            rowsInPage: 20,
            pageNumber: 1,

            workstation_list: [],

            fields_table_workstation: [
                {key: "nome", label: "Nome", sortable: true, filterByFormatted: true },
                {key: "ubicazione", label: "Ubicazione", sortable: true, filterByFormatted: true },
                {key: "image", label: "Immagine"},
                {key: "blueprint", label: "Blueprint"},
                {key: "actions", label: ""},
            ],

            //Modal
            titolo_modal: '',





            // Filtro
            filter: '',


        }
    },
    methods: {

        listaWorkstation(){


        },


        modificaComputer(item){
            this.titolo_modal = "Modifica";



        },


        utenteNonLoggato(){

            window.open("/", "_self");
        },

        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length;
            this.currentPage = 1;
        },
    },
    mounted(){

    },
    destroyed() {
        // Revoke the object URL, to allow the garbage collector to destroy the uploaded before file
        if (this.image.src) {
            URL.revokeObjectURL(this.image.src);
        }
    },
})
