<script>
    var site_url = function(url){
        var url = '<?php echo site_url(); ?>'+url;
        return url;
    }
    var REPORT_SERVICE = 'http://192.168.0.4:7080/';
    var REPORT_PATH = 'birt-viewwer/frameset?__showtitle=false&__report=suna/';

    var USERID = '<?php echo USERID; ?>';
    var USERNAME = '<?php echo USERNAME; ?>';
    var COMPUTER_NAME = '<?php echo COMPUTER_NAME; ?>';

    var INFO = '<?php echo $this->lang->line('info'); ?>';
    var ERROR = '<?php echo $this->lang->line('error'); ?>';
    var SUCCESS = '<?php echo $this->lang->line('success') ?>';
    var WARNING = ' <?php echo $this->lang->line('warning') ?> ';
    var ALERT = '<?php echo $this->lang->line('alert') ?>';
</script>