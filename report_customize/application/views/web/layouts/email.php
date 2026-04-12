<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title><?php echo $template['title']; ?></title>
  <link rel="icon" type="image/png" href="<?php echo base_url('assets_email/img/icon/192x192.png'); ?>" sizes="32x32">
  <!-- Bootstrap core CSS -->
  <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"> -->
  <link href="<?php echo site_url('assets_email/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet">
  <link href="<?php echo site_url('assets_email/bootstrap/bootstrap-side-modals.css'); ?>" rel="stylesheet">

  <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.1.4/dist/css/datepicker.min.css'>

  <!-- Custom fonts for this template -->
  <link href="<?php echo site_url('assets_email/fontawesome/css/all.min.css'); ?>" rel="stylesheet" type="text/css">

  <link href="<?php echo site_url('assets_email/phosphor/Icon Font/CSS/animation.css'); ?>" rel="stylesheet" type="text/css">
  <link href="<?php echo site_url('assets_email/phosphor/Icon Font/CSS/phosphor.css'); ?>" rel="stylesheet" type="text/css">
  <link href="<?php echo site_url('assets_email/phosphor/Icon Font/CSS/phosphor-codes.css'); ?>" rel="stylesheet" type="text/css">

  <link href="<?php echo site_url('assets_email/select2/css/select2.css'); ?>" rel="stylesheet">

  <link href="<?php echo site_url('assets_email/css/feather-icon.css'); ?>" rel="stylesheet">
  <link href="<?php echo site_url('assets_email/css/phosphor-icon.css'); ?>" rel="stylesheet">
  <link href="<?php echo site_url('assets_email/css/custom.css?t='.time()); ?>" rel="stylesheet">

  <!-- Bootstrap core JavaScript -->
  <script src="<?php echo site_url('assets_email/jquery/jquery.min.js'); ?>"></script>
  <script src="<?php echo site_url('assets_email/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
  <script src="<?php echo site_url('assets_email/bootstrap-4.6.1/js/bootstrap.bundle.min.js'); ?>"></script>

  <script src='https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.1.4/dist/js/datepicker-full.min.js'></script>

  <!-- SweetAlert2 11.3.5 -->
  <script src="<?php echo site_url('assets_email/sweetalert2/sweetalert2.all.min.js'); ?>"></script>
  <script src="<?php echo site_url('assets_email/sweetalert2/sweetalert2.min.js'); ?>"></script>
  <link href="<?php echo site_url('assets_email/sweetalert2/sweetalert2.min.css'); ?>" rel="stylesheet" type="text/css">

  <!-- Bootstrap MultiSelect -->
  <link href="<?php echo site_url('assets_email/bootstrap-multiselect/css/bootstrap-multiselect.min.css'); ?>" rel="stylesheet" type="text/css">
  <script src="<?php echo site_url('assets_email/bootstrap-multiselect/js/bootstrap-multiselect.min.js'); ?>"></script>
  <script src="<?php echo site_url('assets_email/script.js'); ?>"></script>

  <script src="<?php echo site_url('assets_email/select2/js/select2.js'); ?>"></script>
</head>

<body id="page-top">
  <div class="container-fluid">
    <?php echo $template['body']; ?>
  </div>
</body>

</html>