<div id="layoutSidenav_nav">
                <nav class="sidenav shadow-right sidenav-light">
                    <div class="sidenav-menu">

                        <div class="nav accordion" id="accordionSidenav">
                        <?php foreach (ARRAY_ROUTE as $value) {?>
                            <div class="sidenav-menu-heading"><?php echo $value['Categoria'] ?></div>

                            <?php
                            $array_menu = $value['ListaMenu'];

                            foreach ($array_menu as $menu) {

                                $array = $menu['Submenu'];
                                $flag = false;

                                for ($i = 0; $i < count($array); $i ++){

                                    if ($menupoint == $array[$i]['href']){
                                        $flag = true;

                                    }

                                }

                                foreach($menu['Gruppo'] as $group){

                                    if ($group == $_SESSION['NOME.GRUPPO']){

                                        if ($flag){

                                            $menu_point = new menuPoint($menu['Scheda'], $array, "", $menu['Icon'], "show");
                                            echo $menu_point->renderMenu();

                                        } else {

                                            $menu_point = new menuPoint($menu['Scheda'], $array, "collapsed", $menu['Icon'], "");
                                            echo $menu_point->renderMenu();

                                        }
                                    }

                                }


                            }

                        }?>



                        </div>
                    </div>
                    <div class="sidenav-footer">
                        <div class="sidenav-footer-content">
                            	<div class="sidenav-footer-subtitle">DMS Data Management System:</div>
                            	<div class="sidenav-footer-title">Version: <?php echo VERSION ?></div>
						<div class="sidenav-footer-subtitle">INTERNAL VERSION</div>
                        </div>
                    </div>
                </nav>
            </div>
