<!-- Sidebar menu-->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="app-sidebar__user">
        <a href="<?= base_url() ?>usuarios/perfil"><img class="app-sidebar__user-avatar" src="<?= media() . $_SESSION['userData']['foto_user'] ?>" alt="User Image"></a>

        <div>
            <p class="app-sidebar__user-name"><?= $_SESSION['userData']['nombres'] ?></p>
            <p class="app-sidebar__user-designation"><?= $_SESSION['userData']['nombrerol'] ?></p>
        </div>
    </div>
    <ul class="app-menu">
        <li><a class="app-menu__item" href="<?= base_url() ?>Dashboard">
                <i class="app-menu__icon fa fa-dashboard"></i>
                <span class="app-menu__label">Dashboard</span></a>
        </li>
        <?php if ($_SESSION['userPermiso'][1]['ver'] == 1 || $_SESSION['userPermiso'][2]['ver'] == 1) { ?>
            <li class="treeview">
                <!-- <a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Open Profile Menu">-->
                <a class="app-menu__item" href="#" data-toggle="treeview">
                    <i class="app-menu__icon fa fa-users"></i>
                    <span class="app-menu__label">Usuarios</span><i class="treeview-indicator fa fa-angle-right"></i></a>
                <ul class="treeview-menu">
                    <?php if ($_SESSION['userPermiso'][1]['ver'] == 1) { ?>
                        <li><a class="treeview-item" href="<?= base_url() ?>usuarios">
                                <i class="icon fa fa-circle-o"></i> Usuarios</a></li>
                    <?php } ?>       
                    <?php if ($_SESSION['userPermiso'][2]['ver'] == 1) { ?>
                        <li><a class="treeview-item" href="<?= base_url() ?>roles">
                                <i class="icon fa fa-circle-o"></i> Roles </a></li>
                    <?php } ?>  
                </ul>
            </li>
        <?php } ?>  
        <?php if ($_SESSION['userPermiso'][3]['ver'] == 1) { ?>
            <li><a class="app-menu__item" href="<?= base_url() ?>clientes">
                    <i class="app-menu__icon fa fa-user"></i>
                    <span class="app-menu__label">Clientes</span></a>
            </li><?php } ?>

        <?php if ($_SESSION['userPermiso'][4]['ver'] == 1) { ?>
            <li class="treeview">
                <a class="app-menu__item" href="#" data-toggle="treeview">
                    <i class="app-menu__icon fa fa-archive"></i>
                    <span class="app-menu__label">Tienda</span><i class="treeview-indicator fa fa-angle-right"></i></a>
                <ul class="treeview-menu">
                    <li><a class="treeview-item" href="<?= base_url() ?>categorias">
                            <i class="icon fa fa-circle-o"></i>Categorias</a>

                    </li>
                    <li><a class="treeview-item" href="<?= base_url() ?>productos">
                            <i class="icon fa fa-circle-o"></i>Productos</a>

                    </li>
                </ul>
            </li><?php } ?>

        <?php if ($_SESSION['userPermiso'][5]['ver'] == 1) { ?>
            <li><a class="app-menu__item" href="<?= base_url() ?>pedidos">
                    <i class="app-menu__icon fa fa-shopping-cart"></i>
                    <span class="app-menu__label">Pedidos</span></a>
            </li><?php } ?>
        <li><a class="app-menu__item" href="<?= base_url() ?>calendar">
                <i class="app-menu__icon fa fa-calendar"></i>
                <span class="app-menu__label">Calendar</span></a>
        </li>

        <?php if ($_SESSION['userPermiso'][6]['ver'] == 1 || $_SESSION['userPermiso'][7]['ver'] == 1) { ?>
            <li class="treeview">
                <!-- <a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Open Profile Menu">-->
                <a class="app-menu__item" href="#" data-toggle="treeview">
                    <i class="app-menu__icon fa fa-cogs" aria-hidden="true"></i>
                    <span class="app-menu__label">Configuracion</span><i class="treeview-indicator fa fa-angle-right"></i></a>

                <ul class="treeview-menu">
                    <?php if ($_SESSION['userPermiso'][6]['ver'] == 1) { ?>
                        <li><a class="treeview-item" href="<?= base_url() ?>configuracion">
                                <i class="icon fa fa-circle-o"></i> Configuracion</a></li><?php } ?>
                    <?php if ($_SESSION['userPermiso'][8]['ver'] == 1) { ?>
                        <li><a class="treeview-item" href="<?= base_url() ?>configuracion/tiposdepago">
                                <i class="icon fa fa-circle-o"></i> Tipos de Pago </a></li><?php } ?>
                    <?php if ($_SESSION['userPermiso'][7]['ver'] == 1) { ?>
                        <li><a class="treeview-item" href="<?= base_url() ?>modulos">
                                <i class="icon fa fa-circle-o"></i> Modulos</a></li><?php } ?>
                </ul>
            </li>
        <?php } ?>
        <li><a class="app-menu__item" href="<?= base_url() ?>logout">
                <i class="app-menu__icon fa fa-sign-out"></i>
                <span class="app-menu__label"href="<?= base_url() ?>logout">Logout</span></a>
        </li>
    </ul>
</aside>
