<!DOCTYPE html>
<html>

<head>

    <base href="http://localhost/">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <!-- STYLESHEETS -->
    <link rel="stylesheet" href="assets/design/scss/gen.css">

    <!-- SCRIPTS -->
    <script src="assets/design/js/jquery.js"></script>
    <script src="assets/design/js/core.js"></script>

    <title><?php if (isset($pageTitle)) echo $pageTitle;
            else echo "Kein Titel"; ?></title>

</head>

<body>

    <script>
        setTimeout(() => {

            reloadContent("[data-element='captcha']");
        }, 100)
    </script>

    <div data-element="error" id="errorResponse" class="error-module">
        <div class="popper shd-1">
            <p></p>
        </div>
    </div>