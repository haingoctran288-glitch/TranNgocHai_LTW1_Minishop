document.addEventListener("DOMContentLoaded", function() {
    const imgInput = document.getElementById("image");
    if (imgInput) {
        imgInput.addEventListener("change", function () {
            const preview = document.getElementById("preview");
            if (!preview) return;
            preview.innerHTML = "";
            const files = this.files;
            for (let i = 0; i < files.length; i++) {
                const img = document.createElement("img");
                img.src = URL.createObjectURL(files[i]);
                img.width = 150;
                img.className = "img-thumbnail me-2";
                preview.appendChild(img);
            }
        });
    }

    const imgsInput = document.getElementById("images");
    if (imgsInput) {
        imgsInput.addEventListener("change", function () {
            const preview = document.getElementById("preview_images");
            if (!preview) return;
            preview.innerHTML = "";
            const files = this.files;
            for (let i = 0; i < files.length; i++) {
                const img = document.createElement("img");
                img.src = URL.createObjectURL(files[i]);
                img.width = 100;
                img.className = "img-thumbnail me-2 mb-2";
                preview.appendChild(img);
            }
        });
    }
});
