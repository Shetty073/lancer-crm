// universal delete event handler (used in multiple pages)
document.querySelectorAll('.entry-delete-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
        let itemId = e.currentTarget.parentNode.id;
        let deleteUrl = document.querySelector(`#deleteUrl${itemId}`).value;
        let csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        let deletedBtnText = document.querySelector('#deletedBtnText').value;
        let deletedTitle = document.querySelector('#deletedTitle').value;
        let deletedMsg = document.querySelector('#deletedMsg').value;

        let redirectUrl = window.location.href;

        try {
            redirectUrl = document.querySelector('#closedRedirectUrl').value;
        } catch (error) {
            console.log(error);
        }

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4338CA',
            cancelButtonColor: '#d33',
            confirmButtonText: deletedBtnText
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(deleteUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                        'X-CSRF-Token': csrfToken,
                    },
                })
                .then((response) => {
                    if (response.ok) {
                        return true;
                    } else {
                        return false;
                    }
                })
                .then(function (data) {
                    if (data === true) {
                        Swal.fire(
                            deletedTitle,
                            deletedMsg,
                            'success'
                        ).then(() => {
                            window.location.href = redirectUrl;
                        });
                    } else {
                        // show error message if needed
                        Swal.fire(
                            'Failed',
                            'Request was unsuccessful. Try again later...',
                            'error'
                        ).then(() => {
                            window.location.reload();
                        });
                    }
                });

            }
        });

    });
});
