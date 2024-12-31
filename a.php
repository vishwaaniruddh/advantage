<html>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js"
        integrity="sha512-6PM0qYu5KExuNcKt5bURAoT6KCThUmHRewN3zUFNaoI6Di7XJPTMoT6K0nsagZKk2OB4L7E3q1uQKHNHd4stIQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</head>

<body>
    <style>
        .codediv {
            background: #eaeaea;
            padding: 20px;
            border-radius: 5px;
        }
    </style>
    <div class="container-fluid">
        <br>
        <a href="#" class="btn btn-warning" id="back"> Back </a>
        <?
        $mydir = $_REQUEST['dir'];
        $mydir = $mydir ? $mydir : '.';
        $server_url = 'https://sarmicrosystems.in/' . str_replace('#', '', $mydir);

        if (is_dir($mydir)) {
            $myfiles = scandir($mydir);
        }


        if ($myfiles) {
            echo '<h2>Folders</h2>
    <ul class="list-group">';
            foreach ($myfiles as $key => $val) {

                if ($val == '.' || $val == '..') {

                } else {
                    $ext = pathinfo($val, PATHINFO_EXTENSION);

                    if (!$ext) {
                        echo '<li class="list-group-item">
                    <a href="#" class="folder_link"><i class="fa-solid fa-folder"></i>' . $val . '</a>
                    </li>';
                    }
                }

            }

        } else {
            echo '<h2>No Folders Here </h2>';
        }
        echo '</ul>';

        echo '<h3>Files</h3>
<ul class="list-group">';

        if ($myfiles) {
            foreach ($myfiles as $key => $val) {

                $val = trim($val);

                if ($val == '.' || $val == '..') {

                } else {
                    $ext = pathinfo($val, PATHINFO_EXTENSION);

                    if ($ext) {


                        if ($ext == "jpg" || $ext == "png" || $ext == "PNG" || $ext == "jpeg" || $ext == "gif" || $ext == "xls" || $ext == "csv" || $ext == "CSV" || $ext == "xlsx") {
                            echo '<li class="list-group-item">
                        <a href="' . $server_url . '/' . $val . '" class="file_link_image" target="_blank"><i class="fa-solid fa-image"></i>' . $val . '</a>
                        </li>';

                        } elseif ($ext == "pdf") {
                            echo '<li class="list-group-item">
                        <a href="' . $server_url . '/' . $val . '" class="file_link_image" target="_blank"><i class="fa-solid fa-file-pdf"></i>' . $val . '</a>
                        </li>';

                        } else if ($ext == 'php') {
                            echo '<li class="list-group-item">
                        <i class="fa-brands fa-php"> <a href="#" class="file_link" ></i>' . $val . '</a>
                        </li>';
                        } else {
                            echo '<li class="list-group-item">
                        <a href="#" class="file_link" ></i>' . $val . '</a>
                        </li>';
                        }
                    }
                }
            }
        }



        echo '</ul>';

        $url = $_GET['dir'];
        $json = file_get_contents(__DIR__ . '/' . $url);

        if ($json) {
            $filename = basename($url);
        }
        ?>
        <div class="codediv">
            <code>
<h3> <? echo $filename; ?></h3>
<xmp><? echo $json; ?>