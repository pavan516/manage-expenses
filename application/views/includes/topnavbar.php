<!-- Pre-Loader -->
<div id="loader" class="center"></div>
<!-- Pre-Loader -->

<!-- Top Navbar -->
<nav class="topnav navbar navbar-expand shadow navbar-light bg-white bcolor center" id="sidenavAccordion">

  <!-- Menu Icon -->
  <button class="btn btn-icon btn-transparent-dark order-1 order-lg-0 mr-lg-2" id="sidebarToggle" href="#">
    <i data-feather="menu" class="fs1"></i>
  </button>
  <!-- Menu Icon -->

  <!-- Right Side Menu -->
  <ul class="navbar-nav align-items-center ml-auto">

    <!-- Refresh -->
    <li class="nav-item dropdown no-caret mr-3">
      <a href="JavaScript: location.reload(true);" class="btn btn-icon btn-transparent-dark dropdown-toggle"><i class="fas fa-sync-alt fs22"></i></a>
    </li>
    <!-- Refresh -->

    <!-- Notifications -->
    <li class="nav-item dropdown no-caret mr-3 dropdown-notifications">
      <a class="btn btn-icon btn-transparent-dark dropdown-toggle" href="<?php echo base_url(); ?>notifications" role="button">
        <i class="fa fa-bell fs22"></i> <span class="badge" id="load_unread_notifications_count"></span>
      </a>
    </li>
    <!-- Notifications -->

    <!-- Debits / credits -->
    <li class="nav-item dropdown no-caret mr-3">
      <a href="<?php echo base_url(); ?>accounts" class="btn btn-icon btn-transparent-dark dropdown-toggle"><i class="fas fa-exchange-alt fs22"></i></a>
    </li>
    <!-- Debits / Credits -->

    <!-- Friends -->
    <li class="nav-item dropdown no-caret mr-3">
      <a href="<?php echo base_url(); ?>friends" class="btn btn-icon btn-transparent-dark dropdown-toggle"><i class="fas fa-user-friends fs22"></i></a>
    </li>
    <!-- Friends -->

    <!-- Profile Menu -->
    <li class="nav-item dropdown no-caret mr-2 dropdown-user">
      <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownUserImage" href="javascript:void(0);" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <?php if(!empty($this->session->userdata('image'))) { ?>
          <img class="img-fluid" src="<?php echo base_url().$this->config->item('user_images').$this->session->userdata('image'); ?>">
        <?php } else { ?>
          <img class="img-fluid" src="<?php echo base_url().$this->config->item('user_images')."default.jpg"; ?>">
        <?php } ?>
      </a>
      <div class="dropdown-menu dropdown-menu-right border-0 shadow animated--fade-in-up" aria-labelledby="navbarDropdownUserImage">

        <!-- User Image | Name | email -->
        <h6 class="dropdown-header d-flex align-items-center">
          <?php if(!empty($this->session->userdata('image'))) { ?>
            <img class="dropdown-user-img" src="<?php echo base_url().$this->config->item('user_images').$this->session->userdata('image'); ?>">
          <?php } else { ?>
            <img class="dropdown-user-img" src="<?php echo base_url().$this->config->item('user_images')."default.jpg"; ?>">
          <?php } ?>
          <div class="dropdown-user-details">
            <div class="dropdown-user-details-name"><?php echo $this->session->userdata('name'); ?> <?php if($this->session->userdata('verified') == 1) { ?><i class="fa fa-check-circle app_color_green" aria-hidden="true"></i> <?php } ?></div>
            <div class="dropdown-user-details-email"><?php echo $this->session->userdata('email'); ?></div>
          </div>
        </h6>
        <!-- User Image | Name | email -->

        <!-- Break Line -->
        <div class="dropdown-divider"></div>
        <!-- Break Line -->

        <!-- Profile -->
        <a class="dropdown-item" href="<?php echo base_url(); ?>profile">
          <div class="dropdown-item-icon"><i data-feather="settings"></i></div>
          Profile
        </a>
        <!-- Profile -->

        <!-- Logout -->
        <a class="dropdown-item" href="<?php echo base_url(); ?>auth/logout">
          <div class="dropdown-item-icon"><i data-feather="log-out"></i></div>
          Logout
        </a>
        <!-- Logout -->

      </div>
    </li>
    <!-- Profile Menu -->

  </ul>
  <!-- Right Side Menu -->

</nav>
<!-- Top Navbar -->

<!-- Include Loader -->
<div class="se-pre-con"></div>
<!-- Include Loader -->

<!-- Send Request -->
<script>
$(document).ready(function (e) {
  /** Load Un-read Notifications count */
  load_unread_notifications_count();
});
</script>
<!-- Send Request -->