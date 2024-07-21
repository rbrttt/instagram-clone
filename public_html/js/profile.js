document.addEventListener("DOMContentLoaded", function() {
    var editProfileBtn = document.getElementById("editProfileBtn");
    var editProfileModal = document.getElementById("editProfileModal");
    var closeModal = document.getElementById("closeModal");
    var followForm = document.getElementById("followForm");

    if (editProfileBtn) {
        editProfileBtn.addEventListener("click", function() {
            editProfileModal.style.display = "block";
        });
    }

    if (closeModal) {
        closeModal.addEventListener("click", function() {
            editProfileModal.style.display = "none";
        });
    }

    window.addEventListener("click", function(event) {
        if (event.target == editProfileModal) {
            editProfileModal.style.display = "none";
        }
    });

    var createPostBtn = document.getElementById("createPostBtn");
    var createPostModal = document.getElementById("createPostModal");
    var closePostModal = document.getElementById("closePostModal");

    if (createPostBtn) {
        createPostBtn.addEventListener("click", function() {
            createPostModal.style.display = "block";
        });
    }

    if (closePostModal) {
        closePostModal.addEventListener("click", function() {
            createPostModal.style.display = "none";
        });
    }

    window.addEventListener("click", function(event) {
        if (event.target == createPostModal) {
            createPostModal.style.display = "none";
        }
    });

    if (followForm) {
        followForm.addEventListener("submit", function(event) {
            event.preventDefault();
            var formData = new FormData(followForm);

            var actionValue = followForm.querySelector('button[name="action"]').value;
            formData.append('action', actionValue);

            console.log("Form submitted");
            for (var pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }

            fetch('follow.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log("Raw response received", response);
                return response.json();
            })
            .then(data => {
                console.log("Parsed response received", data);
                if (data.success) {
                    console.log("Success:", data);
                    document.querySelector('.profile-stats .stat:nth-child(2) .number').innerText = data.follower_count;
                    document.querySelector('.profile-stats .stat:nth-child(3) .number').innerText = data.following_count;

                    var followButton = followForm.querySelector('button');
                    followButton.innerText = data.action === 'follow' ? 'Unfollow' : 'Follow';
                    followButton.value = data.action === 'follow' ? 'unfollow' : 'follow';
                } else {
                    console.error("Error:", data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }
});
