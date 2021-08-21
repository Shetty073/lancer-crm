
document.querySelectorAll('.mark-as-paid-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
        let payment_id = e.currentTarget.parentNode.id;
        let payUrl = document.querySelector(`#payurl${payment_id}`).value;
        let csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        let redirectUrl = window.location.href;

        try {
            redirectUrl = document.querySelector('#closedRedirectUrl').value;
        } catch (error) {
            console.log(error);
        }

        Swal.fire({
            title: 'Are you sure that you want to mark this due as paid?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4338CA',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(payUrl, {
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
                            'Success!',
                            'Successfully marked the due as paid.',
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
