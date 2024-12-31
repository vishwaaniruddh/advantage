<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html>

<head>
    <title>File Manager</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        h1 {
            text-align: center;
        }

        .item-list {
            list-style-type: none;
            padding: 0;
        }

        .item-list li {
            margin: 5px 0;
        }

        .folder-icon {
            width: 20px;
            vertical-align: middle;
        }

        .file-icon {
            width: 20px;
            vertical-align: middle;
        }

        .item-name {
            margin-left: 10px;
            vertical-align: middle;
        }
    </style>
</head>

<body>


    <div class="container">
        <h2>Upload a File</h2>
        <form id="uploadForm" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
            <input type="file" name="fileToUpload" id="fileToUpload">
            <input type="submit" value="Upload File" id="submitBtn">
        </form>

    </div>

    <div class="container">
        <h1>File Manager</h1>
        <ul class="item-list">
            <?php
            // Get the current directory
            $currentDir = __DIR__;
            $relativeDir = '';

            // Check if a 'dir' parameter is present in the URL
            if (isset($_GET['dir'])) {
                // Sanitize the 'dir' parameter to prevent directory traversal attacks
                $selectedDir = realpath($currentDir . '/' . $_GET['dir']);
                if (strpos($selectedDir, $currentDir) === 0 && is_dir($selectedDir)) {
                    $currentDir = $selectedDir;
                    $relativeDir = $_GET['dir'] . '/';
                } else {
                    die('Invalid directory');
                }
            }

            $contents = scandir($currentDir);
            $folders = [];
            $files = [];

            foreach ($contents as $item) {
                if ($item != '.' && $item != '..') {
                    if (is_dir($currentDir . '/' . $item)) {
                        $folders[] = $item;
                    } else {
                        $files[] = $item;
                    }
                }
            }

            sort($folders); // Sort folders in ascending order
            sort($files); // Sort files in ascending order
            
            $items = [];

            // Add folders to the combined array
            foreach ($folders as $folder) {
                $items[] = [
                    'type' => 'folder',
                    'name' => $folder,
                ];
            }

            // Add files to the combined array
            foreach ($files as $file) {
                $items[] = [
                    'type' => 'file',
                    'name' => $file,
                ];
            }

            // Sort the combined array by type (folders first) and then by name in ascending order
            usort($items, function ($a, $b) {
                if ($a['type'] === $b['type']) {
                    return strcasecmp($a['name'], $b['name']);
                }
                return $a['type'] === 'folder' ? -1 : 1;
            });

            // Display the sorted items
            foreach ($items as $item) {
                if ($item['type'] === 'folder') {
                    echo '<li>';
                    echo '<a href="./main.php?dir=' . urlencode($relativeDir . $item['name']) . '">';
                    echo '<img src="folder-icon.png" alt="Folder" class="folder-icon">';
                    echo '<span class="item-name">' . $item['name'] . '</span>';
                    echo '</a>';
                    echo '</li>';
                } elseif ($item['type'] === 'file') {
                    echo '<li>';
                    echo '<a href="#" class="open-file" data-filename="' . $item['name'] . '">';
                    echo '<img src="file-icon.png" alt="File" class="file-icon">';
                    echo '<span class="item-name">' . $item['name'] . '</span>';
                    echo '</a>';
                    echo '</li>';
                }
            }
            ?>
        </ul>
    </div>









    <script>

        // Handle click event for opening files in a new tab
        var openFileLinks = document.querySelectorAll('.open-file');

        openFileLinks.forEach(function (link) {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                var filename = e.currentTarget.getAttribute('data-filename');
                var dirParam = '';

                // Check if the 'dir' parameter is present in the URL
                var urlParams = new URLSearchParams(window.location.search);
                if (urlParams.has("dir")) {
                    dirParam = urlParams.get("dir");
                }

                // Construct the file's URL based on 'dirParam' or use the root directory
                var fileUrl = dirParam ? './' + dirParam + '/' + filename : './' + filename;

                // Open the file in a new tab
                window.open('./view-file.php?file=' + encodeURIComponent(fileUrl), '_blank');
            });
        });


    </script>
</body>

</html>



<?php
// PHP code for handling file uploads
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["fileToUpload"])) {


    $uploadDirectory = $_GET["dir"] ?? "";
    $targetFile = $uploadDirectory . "/" . basename($_FILES["fileToUpload"]["name"]);
    // Check if the file already exists
    if (file_exists($targetFile)) {
        $response = [
            'success' => false,
            'message' => 'Sorry, the file already exists.'
        ];
        echo json_encode($response);
        exit;
    }
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
        $response = [
            'success' => true,
            'message' => 'The file ' . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . ' has been uploaded.'
        ];
        echo json_encode($response);
        exit;
    } else {
        $response = [
            'success' => false,
            'message' => 'Sorry, there was an error uploading your file.'
        ];
        echo json_encode($response);
        exit;
    }
}
?>