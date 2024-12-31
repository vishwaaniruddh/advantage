<?

$base_url = "http://clarity.advantagesb.com/";


if ($_SERVER["HTTPS"] == "on") {
    // Get the current URL without the protocol
    $urlWithoutProtocol = preg_replace("/^https:/i", "http:", $_SERVER["REQUEST_URI"]);
    header("Location: http://clarity.advantagesb.com");
// $base_url = "http://clarity.advantagesb.com/";

    exit;
}



?>