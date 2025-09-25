<?php $extension = TPO_SERV_LOCAL ? '.js' : '.min.js' ?>
</div>
<!-- End Page-content -->
<!-- Footer -->
<footer class="footer">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-6">
      </div>
      <div class="col-sm-6">
        <div class="text-sm-end d-none d-sm-block">
          <i class="mdi mdi-heart text-danger"></i> 
        </div>
      </div>
    </div>
  </div>
</footer>
<!-- End -Footer -->
</div>
<!-- end main content-->
</div>
<!-- END layout-wrapper -->
<!-- Right Sidebar -->
<div class="right-bar">
  <div data-simplebar class="h-100">
    <div class="rightbar-title d-flex align-items-center p-3">
      <h5 class="m-0 me-2">Theme Customizer</h5>
      <a href="javascript:void(0);" class="right-bar-toggle-close ms-auto">
        <i class="mdi mdi-close noti-icon"></i>
      </a>
    </div>
    <!-- Settings -->
    <hr class="m-0" />
    <div class="p-4">
      <!-- <h6 class="mb-3">Layout</h6>
      <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="layout" id="layout-vertical" value="vertical">
      <label class="form-check-label" for="layout-vertical">Vertical</label>
      </div>
      <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="layout" id="layout-horizontal" value="horizontal">
      <label class="form-check-label" for="layout-horizontal">Horizontal</label>
      </div>-->
      <h6 class="mt-4 mb-3">Layout Mode</h6>

      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="layout-mode" id="layout-mode-light" value="light">
        <label class="form-check-label" for="layout-mode-light">Light</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="layout-mode" id="layout-mode-dark" checked value="dark" >
        <label class="form-check-label" for="layout-mode-dark">Dark</label>
      </div>

      <h6 class="mt-4 mb-3">Layout Width</h6>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="layout-width" id="layout-width-fluid" value="fluid" onchange="document.body.setAttribute('data-layout-size', 'fluid')">
        <label class="form-check-label" for="layout-width-fluid">Fluid</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="layout-width" id="layout-width-boxed" value="boxed" onchange="document.body.setAttribute('data-layout-size', 'boxed')">
        <label class="form-check-label" for="layout-width-boxed">Boxed</label>
      </div>

      <h6 class="mt-4 mb-3">Topbar Color</h6>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="topbar-color" id="topbar-color-light" value="light" onchange="document.body.setAttribute('data-topbar', 'light')">
        <label class="form-check-label" for="topbar-color-light">Light</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="topbar-color" id="topbar-color-dark" value="dark" onchange="document.body.setAttribute('data-topbar', 'dark')">
        <label class="form-check-label" for="topbar-color-dark">Dark</label>
      </div>
      <div id="sidebar-setting">
        <h6 class="mt-4 mb-3 sidebar-setting">Sidebar Size</h6>
        <div class="form-check sidebar-setting mt-1">
          <input class="form-check-input" type="radio" name="sidebar-size" id="sidebar-size-default" value="default" onchange="document.body.setAttribute('data-sidebar-size', 'lg')">
          <label class="form-check-label" for="sidebar-size-default">Default</label>
        </div>
        <div class="form-check sidebar-setting mt-1">
          <input class="form-check-input" type="radio" name="sidebar-size" id="sidebar-size-compact" value="compact" onchange="document.body.setAttribute('data-sidebar-size', 'md')">
          <label class="form-check-label" for="sidebar-size-compact">Compact</label>
        </div>
        <div class="form-check sidebar-setting mt-1">
          <input class="form-check-input" type="radio" name="sidebar-size" id="sidebar-size-small" value="small" onchange="document.body.setAttribute('data-sidebar-size', 'sm')">
          <label class="form-check-label" for="sidebar-size-small">Small (Icon View)</label>
        </div>

        <h6 class="mt-4 mb-3 sidebar-setting">Sidebar Color</h6>

        <div class="form-check sidebar-setting mt-1">
          <input class="form-check-input" type="radio" name="sidebar-color" id="sidebar-color-light" value="light" onchange="document.body.setAttribute('data-sidebar', 'light')">
          <label class="form-check-label" for="sidebar-color-light">Light</label>
        </div>
        <div class="form-check sidebar-setting mt-1">
          <input class="form-check-input" type="radio" name="sidebar-color" id="sidebar-color-dark" value="dark" onchange="document.body.setAttribute('data-sidebar', 'dark')">
          <label class="form-check-label" for="sidebar-color-dark">Dark</label>
        </div>
        <div class="form-check sidebar-setting mt-1">
          <input class="form-check-input" type="radio" name="sidebar-color" id="sidebar-color-brand" value="brand" onchange="document.body.setAttribute('data-sidebar', 'brand')">
          <label class="form-check-label" for="sidebar-color-brand">Brand</label>
        </div>
      </div>

      <!-- <h6 class="mt-4 mb-3">Direction</h6>
      <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="layout-direction"
      id="layout-direction-ltr" value="ltr">
      <label class="form-check-label" for="layout-direction-ltr">LTR</label>
      </div>
      <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="layout-direction"
      id="layout-direction-rtl" value="rtl">
      <label class="form-check-label" for="layout-direction-rtl">RTL</label>
      </div>-->

    </div>

  </div> <!-- end slimscroll-menu-->
</div>
<!-- /Right-bar -->

<!-- Right bar overlay-->
<div class="rightbar-overlay"></div>

<!-- CONST -->


<script type='text/javascript'>
  let arrData = ["data-layout-mode", "data-layout-size", "data-topbar", "data-sidebar-size", "data-sidebar"];
  for (let atributo of arrData) {
    if (sessionStorage.getItem(atributo) !== null) {
      document.getElementsByTagName("body")[0].setAttribute(atributo, sessionStorage.getItem(atributo));
    }
  }
  // console.log(sessionStorage.getItem('dash-troggle-icon'));

  if (document.getElementById('dashtoggle') == 'true') {
    document.getElementById('dashtoggle').classList.add('show');
    document.getElementById("dash-troggle-icon").setAttribute('aria-expanded', true);
  } else {
    document.getElementById('dashtoggle').classList.remove('show');
    document.getElementById("dash-troggle-icon").setAttribute('aria-expanded', false);
  }

  document.querySelectorAll("input[name='layout-mode']").forEach(function (e) {
    e.addEventListener("change", function (e) {
      e && e.target && e.target.value && ("light" === e.target.value ?
              (sessionStorage.setItem("data-layout-mode", "light"),
                      sessionStorage.setItem("data-topbar", "light"),
                      sessionStorage.setItem("data-sidebar", "light")) :
              (sessionStorage.setItem("data-layout-mode", "dark"),
                      sessionStorage.setItem("data-topbar", "dark"),
                      sessionStorage.setItem("data-sidebar", "dark")));
    });
  });

  function saveBarSession(e) {
    let element = document.getElementById("dash-troggle-icon");
    // console.log(element.getAttribute('aria-expanded'));
    sessionStorage.setItem("aria-expanded", element.getAttribute('aria-expanded'));
  }
  arrData = ['layout-width', 'topbar-color', 'sidebar-size', 'sidebar-color'];
  for (elemt of arrData)
    document.querySelectorAll("input[name='" + elemt + "']").forEach(function (e) {
      e.addEventListener("change", function (e) {
// console.log(e.target);
        switch (e.target.name) {
          case 'layout-width':
            sessionStorage.setItem('data-layout-size', e.target.value);
            break;
          case 'topbar-color':
            sessionStorage.setItem('data-topbar', e.target.value);
            break;
          case 'sidebar-size':
            let valor = e.target.value === 'default' ? 'lg' : (e.target.value === 'compact' ? 'md' : (e.target.value === 'small' ? 'sm' : ''));
            sessionStorage.setItem('data-sidebar-size', valor);
            break;
          case 'sidebar-color':
            sessionStorage.setItem('data-sidebar', e.target.value);
            break;
        }
      });
    });
</script>
<!-- JAVASCRIPT -->
<script src="<?= DIR_MEDIA ?>vadmin/libs/bootstrap/js/bootstrap.bundle<?= $extension ?>"></script>
<script src="<?= DIR_MEDIA ?>vadmin/libs/metismenujs/metismenujs<?= $extension ?>"></script>
<script src="<?= DIR_MEDIA ?>vadmin/libs/simplebar/simplebar<?= $extension ?>"></script>
<script src="<?= DIR_MEDIA ?>vadmin/libs/feather-icons/feather<?= $extension ?>"></script>
<script src="<?= DIR_MEDIA ?>vadmin/libs/sweetalert2/sweetalert2<?= $extension ?>"></script>
<!-- Template -->
<script>const base_url = "<?= base_url() ?>";</script>
<script>const media = "<?= DIR_MEDIA ?>";</script>

<!--<script src="<?= DIR_MEDIA ?>js/time_live<?= $extension ?>"></script>-->
<script src="<?= DIR_MEDIA ?>vadmin/js/app<?= $extension ?>"></script>
<!-- recursos de la pag -->
<?php
if (isset($data['page_functions_js']) && $data['page_functions_js'] != "") {
  foreach ($data["page_functions_js"] as $value) {
    echo '<script type="text/javascript" src="' . DIR_MEDIA . $value . ' "></script>';
  }
}
?>
</body>
</html>
