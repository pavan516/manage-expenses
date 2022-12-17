<!-- Sidebar -->
<div id="layoutSidenav_nav">
  <nav class="sidenav shadow-right sidenav-dark">

    <!-- Sidebar Links -->
    <div class="sidenav-menu bcolor">
      <div class="nav accordion" id="accordionSidenav">

        <!-- Heading -->
        <div class="sidenav-menu-heading center cwhite"><b class="cwhite fs15">FEATURES</b></div>
        <!-- Heading -->

        <!-- Personal -->
        <?php if($this->session->userdata('feature_personal')) { ?>
          <a class="nav-link" href="<?php echo base_url(); ?>personal">
            <div class="fa fa-user"></div>&nbsp&nbsp&nbsp PERSONAL
          </a>
        <?php } ?>
        <!-- Personal -->

        <!-- Events/Trips -->
        <?php if($this->session->userdata('feature_events')) { ?>
          <a class="nav-link" href="<?php echo base_url(); ?>events">
            <div class="fa fa-id-card"></div>&nbsp&nbsp EVENTS / TRIPS
          </a>
        <?php } ?>
        <!-- Events/Trips -->

        <!-- Accounts -->
        <?php if($this->session->userdata('feature_accounts')) { ?>
          <a class="nav-link" href="<?php echo base_url(); ?>accounts">
            <div class="fas fa-exchange-alt"></div>&nbsp&nbsp ACCOUNTS
          </a>
        <?php } ?>
        <!-- Accounts -->

        <!-- Heading -->
        <div class="sidenav-menu-heading center cwhite"><b class="cwhite fs15">MANAGE</b></div>
        <!-- Heading -->

        <!-- Friends -->
        <a class="nav-link" href="<?php echo base_url(); ?>friends">
          <div class="fas fa-user-friends"></div>&nbsp&nbsp FRIENDS
        </a>
        <!-- Friends -->

        <!-- Profile -->
        <a class="nav-link" href="<?php echo base_url(); ?>profile">
          <div class="fa fa-user"></div>&nbsp&nbsp&nbsp PROFILE
        </a>
        <!-- Profile -->

        <!-- Set Responsibilities -->
        <a class="nav-link" href="<?php echo base_url(); ?>responsibilities/personal">
          <div class="fa fa-tasks"></div>&nbsp&nbsp SET RESPONSIBILITIES
        </a>
        <!-- Set Responsibilities -->

        <!-- Settings -->
        <a class="nav-link" href="<?php echo base_url(); ?>settings">
          <div class="fa fa-cog"></div>&nbsp&nbsp SETTINGS
        </a>
        <!-- Settings -->

        <!-- Notifications -->
        <a class="nav-link" href="<?php echo base_url(); ?>notifications">
          <div class="fa fa-bell"></div>&nbsp&nbsp NOTIFICATIONS
        </a>
        <!-- Notifications -->

        <!-- Logout -->
        <a class="nav-link" href="<?php echo base_url(); ?>auth/logout">
          <div class="fas fa-sign-out-alt" ></div>&nbsp&nbsp LOGOUT
        </a>
        <!-- Logout -->

      </div>
    </div>
    <!-- Sidebar Links -->

    <!-- Sidebar Footer -->
    <div class="sidenav-footer bcolor">
      <div class="sidenav-footer-content">
        <div class="sidenav-footer-subtitle center cwhite pl30"><b>M E</b></div>
        <div class="sidenav-footer-title center cwhite pl40"><b>MANAGE EXPENSES</b></div>
      </div>
    </div>
    <!-- Sidebar Footer -->

  </nav>
</div>
<!-- Sidebar -->
