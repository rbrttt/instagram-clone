document.addEventListener('DOMContentLoaded', function() {
    // Simulate an AJAX call to fetch posts
    const posts = [
        { id: 1, image: 'post1.jpg', description: 'First post' },
        { id: 2, image: 'post2.jpg', description: 'Second post' },
        // Add more posts as needed
    ];

    const profilePostsContainer = document.querySelector('.profile-posts');

    posts.forEach(post => {
        const postElement = document.createElement('div');
        postElement.classList.add('post');
        postElement.innerHTML = `
            <img src="${post.image}" alt="Post Image">
            <p>${post.description}</p>
        `;
        profilePostsContainer.appendChild(postElement);
    });
});
