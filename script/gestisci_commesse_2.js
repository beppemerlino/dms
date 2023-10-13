new Vue({
    el: '#app',
    data () {
        return {




        }
    },
    methods: {


        nextWeek(){
            this.dp.startDate = this.dp.startDate.addDays(7);
            this.dp.update();
        },

        previousWeek(){
            this.dp.startDate = this.dp.startDate.addDays(-7);
            this.dp.update();
        },

    },
    mounted () {

        this.dp = new DayPilot.Calendar("dp");

// view
        this.dp.startDate = "2022-06-04";
        this.dp.viewType = "Week";
        this.dp.locale = "it-it";

// event creating
        this.dp.onTimeRangeSelected = function (args) {
            const name = prompt("New event name:", "Commessa");
            if (!name) return;
            const e = new DayPilot.Event({
                start: args.start,
                end: args.end,
                id: DayPilot.guid(),
                text: name
            });
            this.dp.events.add(e);
            this.dp.clearSelection();
            //console.log(JSON.stringify(dp.events));
        };

        this.dp.eventDeleteHandling = "Update";
        this.dp.onEventDelete = function (args) {
            /*
                if (!confirm("Do you really want to delete this event?")) {
                    args.preventDefault();
                } else {
                    dp.onEventDeleted = function (args) {
                        console.log(JSON.stringify(dp.events));
                    };
                }
            */


        };




        this.dp.init();

        const e = new DayPilot.Event({
            start: new DayPilot.Date("2022-06-01T12:00:00"),
            end: new DayPilot.Date("2021-06-01T12:00:00").addHours(3).addMinutes(15),
            id: "1",
            text: "Special event"
        });
        this.dp.events.add(e);


    },

    computed: {



    },
    watch: {



    },

})