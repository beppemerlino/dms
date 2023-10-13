<!-- Main page content-->
<div id="app" class="container mt-5">
    <!-- Custom page header alternative example-->
    <div class="d-flex justify-content-between align-items-sm-center flex-column flex-sm-row mb-4">
        <div class="mr-4 mb-3 mb-sm-0">

            <div class="small">
                <?php
                function giorno($d){

                    $d_ex = explode("-", $d);
                    $d_ts = mktime(0,0,0,$d_ex[1],$d_ex[2],$d_ex[0]);
                    $num_gg = (int)date("N",$d_ts);

                    $giorno=array('','lunedì','martedì','mercoledì','giovedì','venerdì','sabato','domenica');
                    return $giorno[$num_gg];
                }


                echo '<span class="font-weight-500 text-primary">'.giorno(date('Y-m-d')).'</span>';
                echo ' &middot; ' . date('d/m/Y') . ' &middot; ' . date('H:i');
                ?>
            </div>
        </div>

    </div>

    <div class="fluid container">
        <div class="form-group form-group-lg panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Sortable control</h3>
            </div>
            <div class="panel-body">
                <div class="checkbox">
                    <label><input type="checkbox" v-model="editable">Enable drag and drop</label>
                </div>
                <!--<button type="button" class="btn btn-default" @click="orderList">Sort by original order</button>-->
            </div>
        </div>

        <div class="col-md-3">

        </div>

        <div class="col-md-3">

        </div>

        <div class="list-group col-md-3">

        </div>
        <div class="list-group col-md-3">

        </div>
    </div>

    <b-container class="bv-example-row">
        <b-row>
            <b-col>
                <draggable class="list-group" tag="ul" v-model="team_list" v-bind="dragOptions" :move="onMove" @start="isDragging=true" @end="isDragging=false">
                    <transition-group type="transition" :name="'flip-list'">
                        <li class="list-group-item" v-for="element in team_list" :key="element.order">

                            <b-avatar button variant="info" :src="'http://localhost:8099/dms/assets/' + element.foto"></b-avatar>
                            &nbsp;{{element.nome}}&nbsp;-&nbsp;{{element.cognome}}
                        </li>
                    </transition-group>
                </draggable>
            </b-col>
            <b-col>
                <draggable element="span" v-model="user_list" v-bind="dragOptions" :move="onMove">
                    <transition-group name="no" class="list-group" tag="ul">
                        <li class="list-group-item" v-for="element in user_list" :key="element.order">

                            <b-avatar button variant="info" :src="'http://localhost:8099/dms/assets/' + element.foto"></b-avatar>
                            &nbsp;{{element.nome}}&nbsp;-&nbsp;{{element.cognome}}
                        </li>
                    </transition-group>
                </draggable>
            </b-col>
            <b-col><pre>{{listString}}</pre></b-col>
            <b-col><pre>{{list2String}}</pre></b-col>
        </b-row>
    </b-container>


    <!--<button class="btn btn-teal btn-sm" v-on:click="inviaEmail"><i class="fas fa-envelope"></i>&nbsp;&nbsp;<span>Invia Email</span></button>-->


</div>