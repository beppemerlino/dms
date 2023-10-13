const gg = ['Lunedì', 'Mertedì', 'Mercoledì', 'Giovedì', 'Venerdì', 'Sabato', 'Domenica'];
const gg_short = ['Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab', 'Dom'];
const mm = ['Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre'];
const applica = 'Applica';
const annulla = 'Annulla';
const short = 'Scorciatoie';

const datepickerOptions = {
    sundayFirst: false,
    dateLabelFormat: 'dddd, MMMM D, YYYY',
    days: gg,
    daysShort: gg_short,
    monthNames: mm,
    colors: {
        selected: '#005da7',
        inRange: '#8aa5e2',
        selectedText: '#fff',
        text: '#565a5c',
        inRangeBorder: '#5c6eb6',
        disabled: '#fff',
        hoveredInRange: '#678af8'
    },
    texts: {
        apply: applica,
        cancel: annulla,
        keyboardShortcuts: short,
    },
    image: '',

}


Vue.use(window.AirbnbStyleDatepicker, datepickerOptions);

new Vue({
    el: '#app',
    data () {
        return {

            loading: false,
            date1: '',
            date2:'',
            anno: '2022',

            filter: '',
            currentPage: 1,
            totalRows: 0,
            perPage: 20,
            isBusy: false,
            fields_report: [
                { key: 'codice', label: 'CODICE', sortable: true, tdClass: 'text-right'},
                { key: 'tot_ore', label: 'TOTALE ORE', sortable: true, tdClass: 'text-right'},
                { key: 'anno', label: 'ANNO', sortable: true, filterByFormatted: true, tdClass: 'text-right' },
                { key: 'cliente', label: 'CLIENTE', sortable: true, filterByFormatted: true },
                { key: 'localizzazione', label: 'LOCALIZZAZIONE', sortable: true, filterByFormatted: true },
                { key: 'tipo_lavoro', label: 'TIPO LAVORO', sortable: true, filterByFormatted: true },
                { key: 'chiusa', label: 'APERTA', sortable: true, filterByFormatted: true, tdClass: 'text-center' },
            ],
            reportList: [],

        }
    },
    methods: {
        inizializzaDate(){
            this.date1 = $('#date1').val();
            this.date2 = $('#date2').val();
            console.log(this.date1 + this.date2);
        },

        showReportList(date1, date2){
            this.loading = true;
            this.isBusy = true;
            axios
                .get('./php/lista_report.php?date1=' + date1 + "&date2=" + date2)
                .then(response => {
                    this.reportList = response.data.lista_report;
                    this.totalRows = response.data.lista_report.length;
                    this.loading = false;
                    this.isBusy = false;
                });
        },

        cambiaData(){
            this.showreportList(this.date1, this.date2);
        },

        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length
            this.currentPage = 1
        },

        esportaReport(){


            data_json = {
                anno: this.anno,
                start_date: this.date1,
                end_date: this.date2
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
                url: './php/esporta_report_xlsx.php',
                responseType: 'blob',
            };
            axios(options)
                .then(response => {
                    var fileURL = window.URL.createObjectURL(new Blob([response.data]));
                    var fileLink = document.createElement('a');

                    fileLink.href = fileURL;
                    fileLink.setAttribute('download', 'report_' + this.date1 + '_' + this.date2 + '.xlsx');
                    document.body.appendChild(fileLink);

                    fileLink.click();
                })
                .catch(error => {
                    console.error("ERRORE! ", error);
                });

        },

    },
    mounted () {
        this.inizializzaDate();
        this.showReportList(this.date1, this.date2);
    },

    computed: {

    },
    watch: {

    },

})