<nav class="topnav navbar navbar-expand shadow navbar-dark bg-white" id="sidenavAccordion">
<a  href="/<?php echo DIR; ?>/"><img src="assets/img/Logo_INC_Intranet.svg" height="55" style="margin-left: 5px;" /></a>
        <button class="btn btn-icon btn-transparent-dark order-1 order-lg-0 mr-lg-2" id="sidebarToggle" href="#" style="margin-left: 10px;"><i data-feather="menu"></i></button>
 
    <ul class="navbar-nav align-items-center ml-auto">

        <li class="nav-item dropdown no-caret mr-2 dropdown-user">
            <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownUserImage" href="javascript:void(0);" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img class="img-fluid" src="assets/<?php echo $foto; ?>" /></a>
            <div class="dropdown-menu dropdown-menu-right border-0 shadow animated--fade-in-up" aria-labelledby="navbarDropdownUserImage">
                <h6 class="dropdown-header d-flex align-items-center">
                    <input type="hidden" name="id_operatore" id="id_operatore" value="<?php echo $id_utente; ?>">
                    <img class="dropdown-user-img" src="assets/<?php echo $foto; ?>" />
                    <div class="dropdown-user-details">
                        <div class="dropdown-user-details-name"><?php echo $nome; ?></div>
                        <div class="dropdown-user-details-email"><?php echo $email; ?></div>
                    </div>
                </h6>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#!" data-toggle="modal" data-target="#logoutModal">
                    <div class="dropdown-item-icon"><i data-feather="log-out"></i></div>
                    Logout
                </a>
            </div>
        </li>
    </ul>
</nav>



