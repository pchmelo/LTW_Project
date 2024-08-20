function enableUploads() {
    console.log("Uploads enabled");
    document.addEventListener('DOMContentLoaded', function() {
        console.log("DOM loaded");
        var uploadForm = document.querySelector("#uploadForm");
        var imageInput = document.querySelector("#image");
        var uploadButton = document.querySelector("#uploadButton");
        var infoItem = document.querySelector("#infoItem");

        console.log(uploadForm);

        uploadForm.addEventListener("submit", function(event) {
            console.log("submit");
            event.preventDefault();
            var formData = new FormData(this);
            fetch('../actions/action_upload.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (data.startsWith('success')) {
                    var filename = data;
                    infoItem.innerHTML = '<img src="../assets/uploads/' + filename + '" alt="Uploaded image" id="imagemUploaded"/>';
                    infoItem.innerHTML += '<label for="image" id="changeUploadButton">Upload a Different Image</label>';
                    document.querySelector("#imageUrl").value = '../assets/uploads/' + filename;
                } else {
                    infoItem.innerHTML = '<p>' + data + '</p>';
                    infoItem.innerHTML += '<label id="imageLabel" for="image">Product Image</label><input style="display: none;" type="file" id="image" name="image"><label for="image" id="uploadButton" class="uploadButton">Upload</label>';
                }
            });
        });

        infoItem.addEventListener("click", function(event) {
            if (event.target.id === "changeUploadButton") {
                infoItem.innerHTML = '<label id="imageLabel" for="image">Product Image</label><input style="display: none;" type="file" id="image" name="image"><label for="image" id="uploadButton" class="uploadButton">Upload</label>';
            }
        });

        infoItem.addEventListener("change", function(event) {
            if (event.target.id === "image" && event.target.files && event.target.files[0]) {
                var event = new Event('submit');
                uploadForm.dispatchEvent(event);
            }
        });

        uploadButton.addEventListener('click', function() {
            imageInput.click();
        });

        imageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                var event = new Event('submit');
                uploadForm.dispatchEvent(event);
            }
        });
    });
}
