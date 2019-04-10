<?php include('session.php'); ?>
<?php include('public/menubar.php'); ?>

<link href="assets/css/bootstrap-select.css" rel="stylesheet">
<style>
div.ex1 {
    margin-bottom: 8px;
}
</style>
<script src="assets/js/ckeditor/ckeditor.js"></script>

<script src="assets/js/jquery-1.9.1.min.js"></script>

<?php include('public/add-news-form.php'); ?>
<?php include('public/footer.php'); ?>

<script type="text/javascript" src="assets/js/moment-with-locales.min.js"></script>
<script type="text/javascript" src="assets/js/datetimepicker.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#date').bootstrapMaterialDatePicker
        ({
            time: false,
            clearButton: true
        });

        $('#time').bootstrapMaterialDatePicker
        ({
            date: false,
            shortTime: false,
            format: 'HH:mm'
        });

        $('#date-format').bootstrapMaterialDatePicker
        ({
            format: 'YYYY-MM-DD HH:mm:ss'
        });
        $('#date-fr').bootstrapMaterialDatePicker
        ({
            format: 'DD/MM/YYYY HH:mm',
            lang: 'fr',
            weekStart: 1, 
            cancelText : 'ANNULER',
            nowButton : true,
            switchOnClick : true
        });

        $('#date-end').bootstrapMaterialDatePicker
        ({
            weekStart: 0, format: 'DD/MM/YYYY HH:mm'
        });
        $('#date-start').bootstrapMaterialDatePicker
        ({
            weekStart: 0, format: 'DD/MM/YYYY HH:mm', shortTime : true
        }).on('change', function(e, date)
        {
            $('#date-end').bootstrapMaterialDatePicker('setMinDate', date);
        });

        $('#min-date').bootstrapMaterialDatePicker({ format : 'DD/MM/YYYY HH:mm', minDate : new Date() });
        $.material.init()
    });
</script>