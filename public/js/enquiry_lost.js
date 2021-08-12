// handle 'mark enquiry as lost'
document.querySelectorAll('.enquiry-lost-btn').forEach(btn => {
    btn.addEventListener('click', async (e) => {
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

        const { value: lostRemark } = await Swal.fire({
            title: 'Lost remark',
            input: 'text',
            inputLabel: 'Why was the enquiry lost?',
            showCancelButton: true,
            inputValidator: (value) => {
                if (!value) {
                    return 'Remark cannot be empty!';
                }
            }
        });

        if (lostRemark) {
            let data = {
                lost_remark: lostRemark
            };

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
                        body: JSON.stringify(data),
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

        }

    });
});
