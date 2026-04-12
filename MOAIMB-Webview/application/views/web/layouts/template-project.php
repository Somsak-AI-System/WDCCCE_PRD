<?php global $site_URL; ?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Ai-CRM</title>
  <link rel="icon" type="image/png" href="<?php echo base_url('assets/img/icon/AICRM.ico'); ?>" sizes="32x32">
  <!-- Bootstrap core CSS -->

  <link href="<?php echo site_url('assets/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet">
  <link href="<?php echo site_url('assets/bootstrap/bootstrap-side-modals.css'); ?>" rel="stylesheet">

  <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.1.4/dist/css/datepicker.min.css'>
  
  <!-- Custom fonts for this template -->
  <link href="<?php echo site_url('assets/fontawesome/css/all.min.css'); ?>" rel="stylesheet" type="text/css">

  <link href="<?php echo site_url('assets/phosphor/Icon Font/CSS/animation.css'); ?>" rel="stylesheet" type="text/css">
  <link href="<?php echo site_url('assets/phosphor/Icon Font/CSS/phosphor.css'); ?>" rel="stylesheet" type="text/css">
  <link href="<?php echo site_url('assets/phosphor/Icon Font/CSS/phosphor-codes.css'); ?>" rel="stylesheet" type="text/css">

  <link href="<?php echo site_url('assets/select2/css/select2.css'); ?>" rel="stylesheet">

  <link href="<?php echo site_url('assets/css/feather-icon.css'); ?>" rel="stylesheet">
  <link href="<?php echo site_url('assets/css/phosphor-icon.css'); ?>" rel="stylesheet">
  <link href="<?php echo site_url('assets/css/custom.css?t='.time()); ?>" rel="stylesheet">

  <!-- Bootstrap core JavaScript -->
  <script src="<?php echo site_url('assets/jquery/jquery.min.js'); ?>"></script>
  <script src="<?php echo site_url('assets/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
  <script src="<?php echo site_url('assets/bootstrap-4.6.1/js/bootstrap.bundle.min.js'); ?>"></script>

  <script src='https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.1.4/dist/js/datepicker-full.min.js'></script>

  <!-- SweetAlert2 11.3.5 -->
  <script src="<?php echo site_url('assets/sweetalert2/sweetalert2.all.min.js'); ?>"></script>
  <script src="<?php echo site_url('assets/sweetalert2/sweetalert2.min.js'); ?>"></script>
  <link href="<?php echo site_url('assets/sweetalert2/sweetalert2.min.css'); ?>" rel="stylesheet" type="text/css">
  <script>
    const SITE_URL = '<?php echo $site_URL; ?>'
  </script>

  <!-- Bootstrap MultiSelect -->
  <script src="<?php echo site_url('assets/multiselect/multiselect-dropdown.js'); ?>"></script>
  <script src="<?php echo site_url('assets/script.js?v='.time()); ?>"></script>

  <script src="<?php echo site_url('assets/select2/js/select2.js'); ?>"></script>

  <script src="<?php echo site_url('assets/mixitup/mixitup.min.js'); ?>"></script>
  
</head>

<body id="page-top">
  <div class="container-fluid">
    
    <?php echo $template['body']; ?>

  </div>
</body>

</html>