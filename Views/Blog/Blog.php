<?= headerTienda($data);?>


<!-- Title page -->
<section class="bg-img1 txt-center p-lr-15 p-tb-92" style="background-image: url('<?= DIR_MEDIA ?>images/bg-02.jpg');">
    <h2 class="ltext-105 cl0 txt-center">
        Blog
    </h2>
</section>	


<!-- Content page -->
<section class="bg-transparent p-t-62 p-b-10">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-lg-9 p-b-30">
                <div class="p-r-45 p-r-0-lg">
                    <?php foreach ($data['entradas'] as $entrada) { ?>
                        <!-- item blog -->
                        <div class="p-b-0 m-b-53 bg0">
                            <a href="<?= $entrada['url']?>" class="hov-img0 how-pos5-parent">
                                <img loading="lazy" loading="lazy" class="img-portada-blog" src="<?= $entrada['img']?>" alt="<?= $entrada['titulo']?>">

                                <div class="flex-col-c-m size-123 bg9 how-pos5">
                                    <span class="ltext-107 cl2 txt-center">
                                        <?= strftime('%d', strtotime($entrada['datecreated'])) ?>
                                    </span>

                                    <span class="stext-109 cl3 txt-center">
                                        <?= strftime('%b %Y', strtotime($entrada['datecreated'])) ?>
                                    </span>
                                </div>
                            </a>

                            <div class="p-t-32">
                                <h4 class="p-b-15">
                                    <a href="<?= $entrada['url']?>" class="ltext-108 cl2 hov-cl1 trans-04">
                                        <?= $entrada['titulo']?>
                                    </a>
                                </h4>

                                <p class="stext-117 cl6">
                                    <?= $entrada['descripcion']?>
                                </p>

                                <div class="flex-w flex-sb-m p-t-18">
                                    <span class="flex-w flex-m stext-111 cl2 p-r-30 m-tb-10">
                                        <span>
                                            <span class="cl4">Por</span> <?= $entrada['autor']?>  
                                            <span class="cl12 m-l-4 m-r-6">|</span>
                                        </span>

<!--                                        <span>
                                            StreetStyle, Fashion, Couple  
                                            <span class="cl12 m-l-4 m-r-6">|</span>
                                        </span>-->

                                       <!-- <span>
                                                8 Comments
                                            </span>-->
                                    </span>

                                    <a href="<?= $entrada['url']?>" class="stext-101 cl2 hov-cl1 trans-04 m-tb-10">
                                        Continuar leyendo

                                        <i class="fa fa-long-arrow-right m-l-9"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                    <?php } ?>
                    <!-- Pagination -->
                    <div class="flex-l-m flex-w w-full p-t-10 m-lr--7">
                        <a href="#" class="flex-c-m how-pagination1 trans-04 m-all-7 active-pagination1">
                            1
                        </a>

                        <a href="#" class="flex-c-m how-pagination1 trans-04 m-all-7">
                            2
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-lg-3 p-b-30">
                <div class="side-menu bg0 p-lr-15 p-tb-10">
                    <div class="bor17 of-hidden pos-relative">
                        <input class="stext-103 cl2 plh4 size-116 p-l-28 p-r-55" type="text" name="search" placeholder="Search">

                        <button class="flex-c-m size-122 ab-t-r fs-18 cl4 hov-cl1 trans-04">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>

                    <div class="p-t-55">
                        <h4 class="mtext-112 cl2 p-b-33">
                            Categories
                        </h4>

                        <ul>
                            <li class="bor18">
                                <a href="#" class="dis-block stext-115 cl6 hov-cl1 trans-04 p-tb-8 p-lr-4">
                                    Fashion
                                </a>
                            </li>

                            <li class="bor18">
                                <a href="#" class="dis-block stext-115 cl6 hov-cl1 trans-04 p-tb-8 p-lr-4">
                                    Beauty
                                </a>
                            </li>

                            <li class="bor18">
                                <a href="#" class="dis-block stext-115 cl6 hov-cl1 trans-04 p-tb-8 p-lr-4">
                                    Street Style
                                </a>
                            </li>

                            <li class="bor18">
                                <a href="#" class="dis-block stext-115 cl6 hov-cl1 trans-04 p-tb-8 p-lr-4">
                                    Life Style
                                </a>
                            </li>

                            <li class="bor18">
                                <a href="#" class="dis-block stext-115 cl6 hov-cl1 trans-04 p-tb-8 p-lr-4">
                                    DIY & Crafts
                                </a>
                            </li>
                        </ul>
                    </div>
                    <!-- Productos -->
                    <div class="p-t-65">
                        <h4 class="mtext-112 cl2 p-b-33">
                            Productos Destacados
                        </h4>

                        <ul>
                             <?php foreach ($data['productos_destacados'] as $producto) { ?>
                            <li class="flex-w flex-t p-b-30">
                                <a href="<?= $producto['ruta']?>" class="wrao-pic-w size-214 hov-ovelay1 m-r-20">
                                    <img loading="lazy" class="img-mini-prod" src="<?= $producto['url_img']?>" alt="<?= $producto['nombre']?>" >
                                </a>

                                <div class="size-215 flex-col-t p-t-8">
                                    <a href="<?= $producto['ruta']?>" class="stext-116 cl8 hov-cl1 trans-04" title="<?= $producto['nombre'] ?>">
                                        <?= $producto['nombre']?>
                                    </a> a

                                    <span class="stext-116 cl6 p-t-20">
                                       <?= formatMoney($producto['precio'])?>
                                    </span>
                                </div>
                            </li>
                             <?php } ?>
                     
                        </ul>
                    </div>

                    <div class="p-t-55">
                        <h4 class="mtext-112 cl2 p-b-20">
                            Archive
                        </h4>

                        <ul>
                            <li class="p-b-7">
                                <a href="#" class="flex-w flex-sb-m stext-115 cl6 hov-cl1 trans-04 p-tb-2">
                                    <span>
                                        July 2018
                                    </span>

                                    <span>
                                        (9)
                                    </span>
                                </a>
                            </li>

                            <li class="p-b-7">
                                <a href="#" class="flex-w flex-sb-m stext-115 cl6 hov-cl1 trans-04 p-tb-2">
                                    <span>
                                        June 2018
                                    </span>

                                    <span>
                                        (39)
                                    </span>
                                </a>
                            </li>

                            <li class="p-b-7">
                                <a href="#" class="flex-w flex-sb-m stext-115 cl6 hov-cl1 trans-04 p-tb-2">
                                    <span>
                                        May 2018
                                    </span>

                                    <span>
                                        (29)
                                    </span>
                                </a>
                            </li>

                            <li class="p-b-7">
                                <a href="#" class="flex-w flex-sb-m stext-115 cl6 hov-cl1 trans-04 p-tb-2">
                                    <span>
                                        April  2018
                                    </span>

                                    <span>
                                        (35)
                                    </span>
                                </a>
                            </li>

                            <li class="p-b-7">
                                <a href="#" class="flex-w flex-sb-m stext-115 cl6 hov-cl1 trans-04 p-tb-2">
                                    <span>
                                        March 2018
                                    </span>

                                    <span>
                                        (22)
                                    </span>
                                </a>
                            </li>

                            <li class="p-b-7">
                                <a href="#" class="flex-w flex-sb-m stext-115 cl6 hov-cl1 trans-04 p-tb-2">
                                    <span>
                                        February 2018
                                    </span>

                                    <span>
                                        (32)
                                    </span>
                                </a>
                            </li>

                            <li class="p-b-7">
                                <a href="#" class="flex-w flex-sb-m stext-115 cl6 hov-cl1 trans-04 p-tb-2">
                                    <span>
                                        January 2018
                                    </span>

                                    <span>
                                        (21)
                                    </span>
                                </a>
                            </li>

                            <li class="p-b-7">
                                <a href="#" class="flex-w flex-sb-m stext-115 cl6 hov-cl1 trans-04 p-tb-2">
                                    <span>
                                        December 2017
                                    </span>

                                    <span>
                                        (26)
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="p-t-50">
                        <h4 class="mtext-112 cl2 p-b-27">
                            Tags
                        </h4>

                        <div class="flex-w m-r--5">
                            <a href="#" class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
                                Fashion
                            </a>

                            <a href="#" class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
                                Lifestyle
                            </a>

                            <a href="#" class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
                                Denim
                            </a>

                            <a href="#" class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
                                Streetstyle
                            </a>

                            <a href="#" class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
                                Crafts
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>	

<?= footerTienda($data) ?>