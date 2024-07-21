// Existing script
document.addEventListener("DOMContentLoaded", function() {
    var editProfileBtn = document.getElementById("editProfileBtn");
    var editProfileModal = document.getElementById("editProfileModal");
    var closeModal = document.getElementById("closeModal");

    editProfileBtn.addEventListener("click", function() {
        editProfileModal.style.display = "block";
    });

    closeModal.addEventListener("click", function() {
        editProfileModal.style.display = "none";
    });

    window.addEventListener("click", function(event) {
        if (event.target == editProfileModal) {
            editProfileModal.style.display = "none";
        }
    });

    var createPostBtn = document.getElementById("createPostBtn");
    var createPostModal = document.getElementById("createPostModal");
    var closePostModal = document.getElementById("closePostModal");

    createPostBtn.addEventListener("click", function() {
        createPostModal.style.display = "block";
    });

    closePostModal.addEventListener("click", function() {
        createPostModal.style.display = "none";
    });

    window.addEventListener("click", function(event) {
        if (event.target == createPostModal) {
            createPostModal.style.display = "none";
        }
    });
});
