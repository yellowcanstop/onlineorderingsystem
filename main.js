// querySelectorAll returns NodeList (can use forEach)
// apply confirmation dialog to all forms with class 'action-confirm'
// loop over and add event listener to each form
// used in manageorders.php
const confirmForms = document.querySelectorAll('.action-confirm');
confirmForms.forEach(function(form) {
    form.addEventListener('submit', function(e) {
        if (!confirm('Are you sure you want to proceed with this action?')) {
            e.preventDefault();
        }
    });
});