// Get modal elements
var modal = document.getElementById("editProfileModal");
var btn = document.getElementById("editProfileBtn");
var span = document.getElementById("closeModal");

// Open modal
btn.onclick = function() {
    modal.style.display = "block";
}

// Close modal
span.onclick = function() {
    modal.style.display = "none";
}

// Close modal when clicking outside of it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
