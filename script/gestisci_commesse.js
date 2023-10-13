const locations = ['Europe/London', 'Europe/Berlin', 'Europe/Helsinki'];

const getLocations = function (date) {
    const timeZones = DevExpress.utils.getTimeZones(date);
    return timeZones.filter((timeZone) => locations.indexOf(timeZone.id) !== -1);
};

const showToast = function(event, value, type) {
    DevExpress.ui.notify(`${event} "${value}" task`, type, 800);
}


const currentDate = new Date;

const demoLocations = getLocations(currentDate);

let scheduler;

//const commesseData = [{id:"Commessa 282", startDate:"2022-06-13T06:00:00.000Z", endDate:"2022-06-13T12:00:00.000Z"}];

async function postData(url = '', data = {}) {
    // Default options are marked with *
    const response = await fetch(url, {
        method: 'POST', // *GET, POST, PUT, DELETE, etc.
        mode: 'cors', // no-cors, *cors, same-origin
        cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
        credentials: 'same-origin', // include, *same-origin, omit
        headers: {
            'Content-Type': 'application/json'
            // 'Content-Type': 'application/x-www-form-urlencoded',
        },
        redirect: 'follow', // manual, *follow, error
        referrerPolicy: 'no-referrer', // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
        body: JSON.stringify(data) // body data type must match "Content-Type" header
    });
    return response.json(); // parses JSON response into native JavaScript objects
}


const getCommessaById = function (id) {
    return DevExpress.data.query(commesseData)
        .filter('id', id)
        .toArray()[0];
}

const schedulerCommesse = function(data) {
    const {form} = data;
    let commessaInfo = getCommessaById(data.appointmentData.id) || {};
    let {startDate} = data.appointmentData;


    form.option('items', [{
        label: {
            text: 'Commessa',
        },
        editorType: 'dxSelectBox',
        dataField: 'id',
        editorOptions: {
            items: commesseData,
            displayExpr: 'text',
            valueExpr: 'id',
            onValueChanged(args) {
                commessaInfo = getCommessaById(args.value);

                //TODO controllare questa istruzione...
                form.updateData('text', commessaInfo?.text +" - " + commessaInfo?.cliente);
                form.updateData('cliente', commessaInfo?.cliente);
                form.updateData('tipo_lavoro', commessaInfo?.tipo_lavoro);
            },
        },
    },{
        label: {
            text: 'Cliente',
        },
        name: 'cliente',
        dataField: 'cliente',
        editorType: 'dxTextBox',
        editorOptions: {
            value: commessaInfo.cliente,
            readOnly: true,
        },
    }, {
        name: 'startDate',
        dataField: 'startDate',
        editorType: 'dxDateBox',
        editorOptions: {
            width: '100%',
            type: 'datetime',
            readOnly: true,
        },
    }, {
        name: 'endDate',
        dataField: 'endDate',
        editorType: 'dxDateBox',
        editorOptions: {
            width: '100%',
            type: 'datetime',
            readOnly: true,
        },
    },{
        label: {
            text: 'Note',
        },
        name: 'note',
        dataField: 'note',
        editorType: 'dxTextArea',
        editorOptions: {
            width: '100%',
            readOnly: false,
        },
    },{
        label: {
            text: 'Tipo Lavoro',
        },
        name: 'tipo_lavoro',
        dataField: 'tipo_lavoro',
        editorType: 'dxTextArea',
        editorOptions: {
            value: commessaInfo.tipo_lavoro,
            readOnly: true,
        },
    },{
        label: {
            text: 'id_rendicontazione',
        },
        dataField: 'id_rendicontazione',
        editorType: 'dxTextBox',
        editorOptions: {
            value: commessaInfo.id_rendicontazione,
            readOnly: true,
        }

    },
    ]);
}

const functionInsertData = (e) => {
    if (e.appointmentData.id === undefined) {
        alert("Non è stata selezionata alcuna Commessa!");
        myScheduler.deleteAppointment(e.appointmentData);
        return;
    }
    return {id: e.appointmentData.id, id_rendicontazione: e.appointmentData.id_rendicontazione, startDate: e.appointmentData.startDate, endDate: e.appointmentData.endDate, note: e.appointmentData.note}
}

const functionDataSource = function(){
    console.log(JSON.stringify(myScheduler.getDataSource().items()));

}




const myScheduler = new DevExpress.ui.dxScheduler(document.getElementById("widget"), {
    accessKey:undefined,
    adaptivityEnabled:false,
    appointmentCollectorComponent:null,
    appointmentCollectorRender:null,
    appointmentCollectorTemplate:"appointmentCollector",
    appointmentComponent:null,
    appointmentDragging: {},
    appointmentRender:null,
    appointmentTemplate:"item",
    appointmentTooltipComponent:null,
    appointmentTooltipRender:null,
    appointmentTooltipTemplate:"appointmentTooltip",
    cellDuration:60,
    crossScrollingEnabled:true,
    currentDate:new Date,
    currentView:"week",
    customizeDateNavigatorText:function(e) {
        var formatOptions = {
            year: 'numeric',
            month: 'numeric',
            day: 'numeric',
            timeZone: 'UTC'
        };
        var formattedStartDate = e.startDate.toLocaleString("it", formatOptions);
        var formattedEndDate = e.endDate.toLocaleString("it", formatOptions);
        var view = scheduler.option("currentView");
        if(view === "day" | "timelineDay")
            return formattedStartDate;
        if(view === "month" )
            return e.startDate.toLocaleString("it", { year: 'numeric', month: 'numeric' });
        return formattedStartDate + " - " + formattedEndDate;
    },
    dataCellComponent:null,
    dataCellRender:null,
    dataCellTemplate:null,
    dataSource: new DevExpress.data.DataSource({
        store: schedulerData,
    }),
    dateCellComponent:null,
    dateCellRender:null,
    dateCellTemplate:null,
    dateSerializationFormat:undefined,
    descriptionExpr:"description",
    disabled:false,
    editing: {},
    elementAttr:{},
    endDateExpr:"endDate",
    endDateTimeZoneExpr:"endDateTimeZone",
    endDayHour:22,
    firstDayOfWeek:1,
    focusStateEnabled:true,
    groupByDate:false,
    groups:[],
    height:undefined,
    hint:undefined,
    indicatorUpdateInterval:300000,
    max:undefined,
    maxAppointmentsPerCell:"auto",
    min:new Date('2022-06-01T00:00:00.000Z'),
    noDataText:"No data to display",
    onAppointmentAdded:function(e) {

        if (e.appointmentData.id === undefined) {
            alert("Non è stata selezionata alcuna Commessa!");
            myScheduler.deleteAppointment(e.appointmentData);
            return;
        }

        //console.log(JSON.stringify(functionInsertData(e)));
        postData('php/inserisci_rendicontazione.php', {id: e.appointmentData.id, id_rendicontazione: 0, startDate: e.appointmentData.startDate, endDate: e.appointmentData.endDate, note: e.appointmentData.note})
            .then(data => {
                console.log(data); // JSON data parsed by `data.json()` call
                e.appointmentData.id_rendicontazione = parseInt(data.id_rendicontazione);
            });
        showToast("Aggiunto!", e.appointmentData.text, "success");
    },
    onAppointmentAdding:null,
    onAppointmentClick:null,
    onAppointmentContextMenu:null,
    onAppointmentDblClick:null,
    onAppointmentDeleted:function(e) {
        //console.log(JSON.stringify(functionInsertData(e)));
        postData('php/cancella_rendicontazione.php', {id: e.appointmentData.id, id_rendicontazione: e.appointmentData.id_rendicontazione, startDate: e.appointmentData.startDate, endDate: e.appointmentData.endDate, note: e.appointmentData.note})
            .then(data => {
                console.log(data); // JSON data parsed by `data.json()` call
            });
        showToast("Cancellata", e.appointmentData.text, "warning");
    },
    onAppointmentDeleting:null,
    onAppointmentFormOpening:schedulerCommesse,
    onAppointmentRendered:null,
    onAppointmentUpdated:function (e) {

        //console.log(JSON.stringify(functionInsertData(e)));
        postData('php/inserisci_rendicontazione.php', {id: e.appointmentData.id, id_rendicontazione: e.appointmentData.id_rendicontazione, startDate: e.appointmentData.startDate, endDate: e.appointmentData.endDate, note: e.appointmentData.note})
            .then(data => {
                console.log(data); // JSON data parsed by `data.json()` call
            });
        showToast("Modificato!", e.appointmentData.text, "success");

    },
    onAppointmentUpdating:null,
    onCellClick:null,
    onCellContextMenu:null,
    onContentReady:null,
    onDisposing:null,
    onInitialized:function(e) {
        scheduler = e.component;
        },
    onOptionChanged:null,
    recurrenceEditMode:"dialog",
    recurrenceExceptionExpr:"recurrenceException",
    recurrenceRuleExpr:"recurrenceRule",
    remoteFiltering: true,
    resourceCellComponent:null,
    resourceCellRender:null,
    resourceCellTemplate:null,
    resources: [{
        fieldExpr: 'id',
        dataSource: commesseData,
        useColorAsDefault: true,
    }],
    rtlEnabled:false,
    scrolling: {},
    shadeUntilCurrentTime:false,
    showAllDayPanel:false,
    showCurrentTimeIndicator:true,
    startDateExpr:"startDate",
    startDateTimeZoneExpr:"startDateTimeZone",
    startDayHour:7,
    tabIndex:0,
    textExpr:"text",
    timeCellComponent:null,
    timeCellRender: null,
    timeCellTemplate: null,
    timeZone:demoLocations[1].id,
    useDropDownViewSwitcher:false,
    views: [],
    visible:true,
    width:undefined
});

