<!DOCTYPE html>
<html>
<head>
    <title>File Viewer</title>
    <!-- Include Ace editor library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ace.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script
  src="https://code.jquery.com/jquery-3.7.1.min.js"
  integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
  crossorigin="anonymous"></script>
    <style>
    
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            overflow: hidden; /* Prevent scroll bars */
        }
        #editor-container {
            position: absolute;
            top: 10%;
            left: 0;
            right: 0;
            bottom: 0;
        }
        #editor {
            height: 100%;
            width: 100%;
        }
        #save-button {
            position: fixed;
            top: 10px;
            right: 10px;
            padding: 10px 20px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            z-index: 1; /* Ensure the save button is on top */
        }
        #save-button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <button id="save-button">Save</button> <!-- Save button to save the file content -->
    <div id="editor-container">
        <div id="editor"></div>
    </div>

    <script>
        // Initialize Ace editor
        var editor = ace.edit("editor");
        editor.setTheme("ace/theme/monokai"); // You can change the theme as needed

        // Get the file path from the URL
        var urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has("file")) {
            var filePath = urlParams.get("file");

            // Determine the file extension
            var fileExtension = filePath.split('.').pop();
            var mode;

            // Map file extensions to Ace editor modes
            switch (fileExtension) {
                case "html":
                    mode = "ace/mode/html";
                    break;
                case "css":
                    mode = "ace/mode/css";
                    break;
                case "js":
                    mode = "ace/mode/javascript";
                    break;
                case "php":
                    mode = "ace/mode/php";
                    break;
                case "py":
                    mode = "ace/mode/python";
                    break;
                case "rb":
                    mode = "ace/mode/ruby";
                    break;
                case "json":
                    mode = "ace/mode/json"; // Support for JSON files
                    break;
                case "sql":
                    mode = "ace/mode/sql"; // Support for JSON files
                    break;
                // Add more cases for other file types as needed
                default:
                    mode = "ace/mode/text"; // Default to plain text if no recognized extension
                    break;
            }

            // Set the Ace editor mode based on the file extension
            editor.getSession().setMode(mode);

            // Make an AJAX request to load the file content
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Set the content of the Ace editor with the file's content
                    editor.setValue(xhr.responseText, -1);
                    editor.gotoLine(1); // Move cursor to the first line
                }
            };

            // Set the file path for the AJAX request
            xhr.open('GET', 'get-file-content.php?file=' + encodeURIComponent(filePath), true);
            xhr.send();
        }

        document.addEventListener('keydown', function (e) {
    if (e.ctrlKey && e.key === 's') {
        // Prevent the default browser save action (e.g., opening the save dialog)
        e.preventDefault();
        
        // Trigger the save action here
        saveFile();
    }
});


$(document).on('click','#save-button',function(){
    saveFile(); 
})

// Function to save the file
function saveFile() {
    // Get the content of the editor
    var fileContent = editor.getValue();

    // Make an AJAX request to save the file content
    var saveXhr = new XMLHttpRequest();
    saveXhr.onreadystatechange = function () {
        if (saveXhr.readyState === 4 && saveXhr.status === 200) {
            // Show a success message using SweetAlert (Swal) with a top-right position
            Swal.fire({
                icon: 'success',
                title: 'File Saved',
                position: 'top-right',
                showConfirmButton: false,
                timer: 1500 // 1.5 seconds
            });
        }
    };

    // Set the file path for the AJAX request to save the content
    saveXhr.open('POST', 'save-file.php', true);
    saveXhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    saveXhr.send('file=' + encodeURIComponent(filePath) + '&content=' + encodeURIComponent(fileContent));
}

    </script>
</body>
</html>
