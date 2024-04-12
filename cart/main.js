// confirm dialog for updates
document.addEventListener('DOMContentLoaded', function() {
    // add click event listener to all elements with class 'action-confirm'
    const confirmLinks = document.querySelectorAll('.action-confirm');
    confirmLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            // 'data-url' is HTML5 custom data attribute ('data-*')
            const url = e.target.getAttribute('data-url');
            if (confirm("Are you sure you want to update this?")) {
                // if user confirms, browser will navigate to the url in 'data-url' attribute
                window.location.href = url;
            }
        });
    });
});