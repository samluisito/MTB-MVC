<?=
headerTienda($data);
$entrada = $data['entrada']
?>
<!-- Content page -->
<section class="bg0 p-t-52 p-b-20">
   <div class="container">
      <div class="row">
         <div class="col-md-8 col-lg-9 p-b-80">
            <!-- Articulo  -->

            <div class="p-r-45 p-r-0-lg">
               <div class="wrap-pic-w how-pos5-parent">
                  <img loading="lazy" class="img-portada-blog" src="<?= $entrada['img_url'] ?>" alt="<?= $entrada['titulo'] ?>">
                  <div class="flex-col-c-m size-123 bg9 how-pos5">
                     <span class="ltext-107 cl2 txt-center">
                        <?= strftime('%d', strtotime($entrada['datecreated'])) ?>
                     </span>
                     <span class="stext-109 cl3 txt-center">
                        <?= strftime('%b %Y', strtotime($entrada['datecreated'])) ?>
                     </span>
                  </div>
               </div>
               <div class="p-t-32">
                  <span class="flex-w flex-m stext-111 cl2 p-b-19">
                     <span>
                        <span class="cl4">Por</span> <?= $entrada['autor'] ?>  
                        <span class="cl12 m-l-4 m-r-6">|</span>
                     </span>
                     <span>
                        <?= strftime('%d %b, %Y', strtotime($entrada['datecreated'])) ?> 
                        <span class="cl12 m-l-4 m-r-6">|</span>
                     </span>
<!--                            <span>
                     <?= $entrada['tags'] ?>
                                <span class="cl12 m-l-4 m-r-6">|</span>
                            </span>-->
<!--                            <span>
                                8 Comments
                            </span>-->
                  </span>                  
                  <h4 class="ltext-109 cl2 p-b-28">  <?= $entrada['titulo'] ?>  </h4>
                  <div class="stext-117 cl6 p-b-26"><?= $entrada['descripcion'] ?></div>
                  <p class="stext-117 cl6 p-b-26"><?= $entrada['txt_entrada'] ?></p>
               </div>
               <!-- Comentario -->
               <!--<div class="p-t-40">
                   <h5 class="mtext-113 cl2 p-b-12">
                       Leave a Comment
                   </h5>

                   <p class="stext-107 cl6 p-b-40">
                       Your email address will not be published. Required fields are marked *
                   </p>

                   <form>
                       <div class="bor19 m-b-20">
                           <textarea class="stext-111 cl2 plh3 size-124 p-lr-18 p-tb-15" name="cmt" placeholder="Comment..."></textarea>
                       </div>

                       <div class="bor19 size-218 m-b-20">
                           <input class="stext-111 cl2 plh3 size-116 p-lr-18" type="text" name="name" placeholder="Name *">
                       </div>

                       <div class="bor19 size-218 m-b-20">
                           <input class="stext-111 cl2 plh3 size-116 p-lr-18" type="text" name="email" placeholder="Email *">
                       </div>

                       <div class="bor19 size-218 m-b-30">
                           <input class="stext-111 cl2 plh3 size-116 p-lr-18" type="text" name="web" placeholder="Website">
                       </div>

                       <button class="flex-c-m stext-101 cl0 size-125 bg3 bor2 hov-btn3 p-lr-15 trans-04">
                           Post Comment
                       </button>
                   </form>
               </div>-->
            </div>
         </div>
         <!-- Barra lateral  -->
         <div class="col-md-4 col-lg-3 p-b-80">
            <div class="side-menu">
               <div class="bor17 of-hidden pos-relative">
                  <input class="stext-103 cl2 plh4 size-116 p-l-28 p-r-55" type="text" name="search" placeholder="Search">

                  <button class="flex-c-m size-122 ab-t-r fs-18 cl4 hov-cl1 trans-04">
                     <i class="fa fa-search"></i>
                  </button>
               </div>



               <!-- Productos -->
               <div class="p-t-65">
                  <h4 class="mtext-112 cl2 p-b-33">
                     Productos Destacados
                  </h4>

                  <ul>
                     <?php foreach ($data['productos_destacados'] as $producto) { ?>
                        <li class="flex-w flex-t p-b-30">
                           <a href="<?= $producto['ruta'] ?>" class="wrao-pic-w size-214 hov-ovelay1 m-r-20">
                              <img loading="lazy" class="img-mini-prod" src="<?= $producto['url_img'] ?>" alt="<?= $producto['nombre'] ?>">
                           </a>

                           <div class="size-215 flex-col-t p-t-8">
                              <a href="<?= $producto['ruta'] ?>" class="stext-116 cl8 hov-cl1 trans-04" title="<?= $producto['nombre'] ?>" >
                                 <?= $producto['nombre'] ?>
                              </a>

                              <span class="stext-116 cl6 p-t-20">
                                 <?= formatMoney($producto['precio']) ?>
                              </span>
                           </div>
                        </li>
                     <?php } ?>
                  </ul>
               </div>
               <!--                    
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
                   </div>-->
            </div>
         </div>
      </div>
   </div>
</section>	



<?= footerTienda($data); ?>