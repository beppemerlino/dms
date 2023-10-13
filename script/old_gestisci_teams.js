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
    data () {
        return {


            list: message.map((name, index) => {
                return { name, order: index + 1, fixed: true };
            }),
            list2: [],
            editable: true,
            isDragging: false,
            delayedDragging: false,
            user_list: [
                {id:4, teamleader: false, nome:"Arianna", cognome:"Lanzarini", foto:"avatars\/arianna-lanzarini.jpg", fixed: false, order: 2},
                {id:5, teamleader: false, nome:"Emanuele", cognome :"Carano", foto:"avatars\/emanuele.jpg", fixed: false, order: 3},
                {id:8, teamleader: false, nome:"Davide", cognome:"Marangoni", foto:"avatars\/davide.jpg", fixed: false, order: 4},
                {id:9, teamleader: false, nome:"Raffaele", cognome:"Salvi", foto:"avatars\/raffaele.jpg", fixed: false, order: 5},
                {id:11, teamleader: false, nome:"Giovanni",cognome:"Parisi",foto:"avatars\/giovanni.jpg", fixed: false, order: 6},
                {id:12, teamleader: false, nome:"Rosanna", cognome:"Caporusso", foto:"avatars\/rosanna.jpg", fixed: false, order: 7},
                {id:13, teamleader: false, nome:"Lorenzo", cognome:"Cappellini", foto:"avatars\/lorenzo.jpg", fixed: false, order: 8},
                {id:14, teamleader: false, nome:"Stefania",cognome:"Fontanini", foto:"avatars\/stefania.jpg", fixed: false, order: 9},
                {id:15, teamleader: false, nome:"Maria Teresa", cognome: "Salvi", foto:"avatars\/teresa.jpg", fixed: false, order: 10},
                {id:16, teamleader: false, nome:"Federico", cognome :"Presazzi", foto:"avatars\/fede.jpg", fixed: false, order: 11},
                {id:18, teamleader: false, nome:"Arianna", cognome:"Losi", foto:"avatars\/arianna-losi.jpg", fixed: false, order: 12}],
            team_list: [{id:3, teamleader: true, nome:"Monica", cognome: "Ortenzi", foto: "avatars\/monica.jpg", fixed: false, order: 1},],


        }
    },
    methods: {


        orderList() {
            this.list = this.list.sort((one, two) => {
                return one.order - two.order;
            });
        },
        onMove({ relatedContext, draggedContext }) {
            const relatedElement = relatedContext.element;
            const draggedElement = draggedContext.element;
            return (
                (!relatedElement || !relatedElement.fixed) && !draggedElement.fixed && !draggedElement.teamleader
            );
        },


    },
    mounted () {

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