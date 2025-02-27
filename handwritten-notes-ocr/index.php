<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Handwritten Text Recognition System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .upload-box {
            border: 2px dashed #3498db;
            padding: 20px;
            text-align: center;
            background-color: #f8f9fa;
        }

        .file-list {
            list-style: none;
            padding: 0;
        }

        .file-list li {
            padding: 5px;
            background: #e9ecef;
            margin: 5px 0;
            border-radius: 5px;
        }

        #imagePreview {
            max-width: 100%;
            height: auto;
            margin-top: 10px;
            display: none;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="row">
                    <div class="col-md-6">
                        <h2 class="text-center mb-4">Upload Handwritten Notes</h2>
                        <form id="uploadForm" enctype="multipart/form-data">
                            <div class="upload-box p-4 mb-3 rounded">
                                <input type="file" name="file" id="fileInput" class="form-control" accept="image/*,application/pdf">
                                <img id="imagePreview" class="border rounded mt-2" />
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Convert to Text</button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <h3 class="text-center mb-4">Extracted Text:</h3>
                        <div id="textOutput" class="border p-3 rounded bg-light" style="min-height: 200px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const fileInput = document.getElementById('fileInput');
        const imagePreview = document.getElementById('imagePreview');
        const textOutput = document.getElementById('textOutput');

        // Function to reset preview and output
        function resetUI() {
            imagePreview.style.display = 'none';
            imagePreview.src = "";
            textOutput.innerHTML = "";
        }

        // Show preview when a file is selected
        fileInput.addEventListener('change', function(event) {
            resetUI(); // Reset the UI when selecting a new file

            const file = event.target.files[0];
            if (file) {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    imagePreview.style.display = 'none';
                }
            }
        });

        // Handle file upload
        document.getElementById('uploadForm').addEventListener('submit', function(event) {
            event.preventDefault();
            let formData = new FormData();

            if (fileInput.files.length === 0) {
                alert("Please upload an image or PDF!");
                return;
            }

            formData.append("file", fileInput.files[0]);

            fetch("process.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.text())
                .then(text => {
                    textOutput.innerHTML = text;
                })
                .catch(error => console.error("Error:", error));
        });
    </script>
</body>

</html>