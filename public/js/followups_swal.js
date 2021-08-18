// add followup
document.querySelector('.add-followup-btn').addEventListener('click', async () => {
    const { value: formValues } = await Swal.fire({
        title: 'Add new followup',
        html:
            `<div class="py-1 bg-indigo-100 mb-1 rounded">
                <label class="block text-indigo-700 font-bold px-2">Date & Time</label>
                <input
                    class="w-11/12 px-2 py-1 mx-3 bg-indigo-100 border-b-2 border-gray-500 focus:border-indigo-700 outline-none"
                    id="addFollowupDateTime" type="datetime-local" />
            </div>
            <div class="py-1 bg-indigo-100 mb-1 rounded">
                <label class="block text-indigo-700 font-bold px-2">Remark</label>
                <input
                    class="w-11/12 px-2 py-1 mx-3 bg-indigo-100 border-b-2 border-gray-500 focus:border-indigo-700 outline-none"
                    id="addFollowupRemark" type="text" />
            </div>
            <div class="py-1 bg-indigo-100 mb-1 rounded">
                <label class="block text-indigo-700 font-bold px-2">Outcome</label>
                <input
                    class="w-11/12 px-2 py-1 mx-3 bg-indigo-100 border-b-2 border-gray-500 focus:border-indigo-700 outline-none"
                    id="addFollowupOutcome" type="text" />
            </div>`,
        focusConfirm: false,
        showCancelButton: true,
        confirmButtonColor: '#007BFF',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Add',
        preConfirm: () => {
            return [
                document.querySelector('#addFollowupDateTime').value,
                document.querySelector('#addFollowupRemark').value,
                document.querySelector('#addFollowupOutcome').value
            ]
        }
    });

    if (formValues) {
        let data = {
            date_time: formValues[0],
            remark: formValues[1],
            outcome: formValues[2],
            enquiry_id: document.querySelector('#enquiryid').value,
        };

        let url = document.querySelector('#addfollowupurl').value;
        let csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        fetch(url, {
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
                    'Success!',
                    'Successfully created a followup for current enquiry.',
                    'success'
                ).then(() => {
                    window.location.reload();
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


// edit followups
document.querySelectorAll('.follow-up-edit-btn').forEach(btn => {
    btn.addEventListener('click', async (e) => {
        let followupId = e.currentTarget.parentNode.id;

        let oldDateTime = document.querySelector(`#date_time${followupId}`).value;
        let oldRemark = document.querySelector(`#remark${followupId}`).value;
        let oldOutcome = document.querySelector(`#outcome${followupId}`).value;

        const { value: formValues } = await Swal.fire({
            title: 'Edit followup',
            html:
                `<div class="py-1 bg-indigo-100 mb-1 rounded">
                    <label class="block text-indigo-700 font-bold px-2">Date & Time</label>
                    <input
                        class="w-11/12 px-2 py-1 mx-3 bg-indigo-100 border-b-2 border-gray-500 focus:border-indigo-700 outline-none"
                        id="addFollowupDateTime" type="datetime-local" value="${oldDateTime}" />
                </div>
                <div class="py-1 bg-indigo-100 mb-1 rounded">
                    <label class="block text-indigo-700 font-bold px-2">Remark</label>
                    <input
                        class="w-11/12 px-2 py-1 mx-3 bg-indigo-100 border-b-2 border-gray-500 focus:border-indigo-700 outline-none"
                        id="addFollowupRemark" type="text" value="${oldRemark}" />
                </div>
                <div class="py-1 bg-indigo-100 mb-1 rounded">
                    <label class="block text-indigo-700 font-bold px-2">Outcome</label>
                    <input
                        class="w-11/12 px-2 py-1 mx-3 bg-indigo-100 border-b-2 border-gray-500 focus:border-indigo-700 outline-none"
                        id="addFollowupOutcome" type="text" value="${oldOutcome}" />
                </div>`,
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonColor: '#007BFF',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Add',
            preConfirm: () => {
                return [
                    document.querySelector('#addFollowupDateTime').value,
                    document.querySelector('#addFollowupRemark').value,
                    document.querySelector('#addFollowupOutcome').value
                ]
            }
        });

        if (formValues) {
            let data = {
                date_time: formValues[0],
                remark: formValues[1],
                outcome: formValues[2],
                enquiry_id: document.querySelector('#enquiryid').value,
            };

            let url = document.querySelector(`#editfollowupurl${followupId}`).value;
            let csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            fetch(url, {
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
                        'Success!',
                        'Successfully updated the selected followup.',
                        'success'
                    ).then(() => {
                        window.location.reload();
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


// delete followups
document.querySelectorAll('.follow-up-delete-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
        let itemId = e.currentTarget.parentNode.id;
        let deleteUrl = document.querySelector(`#deletefollowupurl${itemId}`).value;
        let csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        try {
            document.querySelector('#closedRedirectUrl').value
        } catch (error) {
            console.log(error);
        }

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#007BFF',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
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
                            'Deleted!',
                            'The requested followup was deleted successfully.',
                            'success'
                        ).then(() => {
                            window.location.reload();
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
