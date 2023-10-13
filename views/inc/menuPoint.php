<?php

class menuPoint
{
    private $nome_scheda;
    private $arr_submenu = array();
    private $collapsed;
    private $icon;
    private $show;


    public function __construct(string $nome_scheda, array $arr_submenu, string $collapsed, string $icon, string $show){

        $this->nome_scheda = $nome_scheda;
        $this->arr_submenu = $arr_submenu;
        $this->collapsed = $collapsed;
        $this->icon = $icon;
        $this->show = $show;

    }

    public function renderMenu(){


        $sub_menu = "";

        foreach ($this->arr_submenu as $submenu) {

            $sub_menu .= "<a class=\"nav-link\" href=\"".$submenu['href']."\">".$submenu['caption']."</a>".chr(13).chr(10);

        }

        $_menu = "<a class=\"nav-link ".$this->collapsed."\" href=\"javascript:void(0);\" data-toggle=\"collapse\" data-target=\"#collapse".ucfirst(strtolower($this->nome_scheda))."\" aria-expanded=\"false\" aria-controls=\"collapse".ucfirst(strtolower($this->nome_scheda))."\">
    <div class=\"nav-link-icon\"><img src=\"assets/img/icons/".$this->icon.".svg\" height=\"16\" /></div>
    ".strtoupper($this->nome_scheda)."
                                <div class=\"sidenav-collapse-arrow\"><i class=\"fas fa-angle-down\"></i></div>
                            </a>
                            <div class=\"collapse ".$this->show." \" id=\"collapse".ucfirst(strtolower($this->nome_scheda))."\" data-parent=\"#accordionSidenav\">
                                <nav class=\"sidenav-menu-nested nav accordion\" id=\"accordionSidenavPages\">
                                    ".$sub_menu."
                                </nav>
                            </div>";

        return $_menu;


    }


}