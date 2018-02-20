<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AMAA Email Parser</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/bootstrap-table.min.css">


</head>
<body>
<script>
    var host = '<?php echo env('APP_HOST'); ?>';
</script>
<div class="container">
    <div class="row justify-content-md-center">
        <div class="col col-lg-12">

            <fieldset>

                <!-- Form Name -->
                <legend>Select EXCEL file </legend>

                <!-- File Button -->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="filebutton">Upload Excel (xlsx format only)</label>
                    <div class="col-md-4">
                        <input id="filebutton" name="filebutton" class="input-file" type="file">
                        <i class="fas fa-cloud-download-alt" style="font-size: 20px"></i>&nbsp; Download Example
                        <a href="/assets/Example.xlsx" download="example">
                            <img border="0" src="/assets/excel.ico" alt="example" width="50">

                        </a>
                    </div>
                </div>

            </fieldset>

            <form class="form-horizontal" id="form" action="/prepare">
                <fieldset>

                    <!-- Form Name -->
                    <legend>Or Add One by One</legend>

                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="name">Name (format surname name)</label>
                        <div class="col-md-4">
                            <input id="name" name="textinput" type="text" placeholder="name"
                                   class="form-control input-md">
                            <span class="help-block">
                            </span>
                        </div>
                    </div>

                    <!-- Select Basic -->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="domain">Select Domain</label>
                        <div class="col-md-4">
                            <select id="domain" name="domain" class="form-control">
                                <option value="amaa.am">AMAA</option>
                                <option value="avedisianschool.am">Avedisian school</option>
                                <option value="lyd.am">Live Your Dream</option>
                                <option value="eca.am">Evangelical Church</option>
                            </select>
                        </div>
                    </div>

                    <!-- Button -->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="submit">Submit form</label>
                        <div class="col-md-4">
                            <button id="submit" type="submit" form="form" value="Submit" class="btn btn-primary"><i class="fas fa-cogs"></i> Prepare</button>
                        </div>
                    </div>

                </fieldset>
            </form>
        </div>

    </div>
    <div class="row">

        <div id="toolbar">
            <button id="button" class="btn btn-danger"><i class="fas fa-trash-alt" style="font-size: 19px"></i></button>
            <button id="create" class="btn btn-success"><i class="far fa-save" style="font-size: 19px"></i> start creation</button>
        </div>

        <div class="col-12 col-md-auto">
            <table id="table"></table>
        </div>

    </div>
</div>
<?php echo $world; ?>

<script src="//code.jquery.com/jquery-3.1.0.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/bootstrap-table.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/locale/bootstrap-table-en-US.min.js"></script>
<script src="//unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
<script type="text/javascript" src="/assets/js/export/libs/FileSaver/FileSaver.min.js"></script>
<script type="text/javascript" src="/assets/js/export/libs/js-xlsx/xlsx.core.min.js"></script>
<script src="/assets/js/export/tableExport.js"></script>
<script src="/assets/js/export/export.js"></script>
<script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
<script type="text/javascript" src="<?php echo url('/assets/js/script.js') ?>"></script>
</body>
</html>