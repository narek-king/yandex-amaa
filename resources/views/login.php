<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AMAA Email Parser</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

</head>
<body>

<div class="container">
    <div class="row justify-content-md-center">
        <div class="col col-lg-12">
            <form class="form-horizontal" id="form" action="/prepare">
                <fieldset>

                    <!-- Form Name -->
                    <legend>Login with AMAA, LYD, ECA or Avedisian School domain</legend>

                    <!-- Button -->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="submit">Login with yandex </label>
                        <div class="col-md-4">
                            <button type="button" onclick="location.href='<?php echo $link; ?>';" class="btn btn-warning">Log in</button>
                        </div>
                    </div>

                </fieldset>
            </form>
        </div>

    </div>
</div>


<script src="//code.jquery.com/jquery-3.1.0.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>