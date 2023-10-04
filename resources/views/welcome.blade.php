<!DOCTYPE html>
<html>

<head>
    <title>Laravel</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <div class="render">
        <div class="d-flex justify-content-end">
            <div class="me-2">
                <button class="btn btn-success show_table">Add New</button>
            </div>
            <div>
                <button id="bulkDelete" data-url="{{ route('user.bulkdelete') }}" class="btn btn-danger btn-cencel">Bulk
                    Delete</button>
            </div>
        </div>
        <table class="table table-bordered">
            <tr>
                <th>No</th>
                <th>Select</th>
                <th>Name</th>
                <th>Contact no</th>
                <th>Hobby</th>
                <th>Catagory</th>
                <th>Profile pic</th>
                <th width="280px">Action</th>
            </tr>
            <tbody id="reload_data" class="reload_data">
                @include('tableData')
            </tbody>

        </table>
    </div>

    <div class="container form_render hide">

        <div class="row mt-5 mb-5">
            <div class="col-10 offset-1 mt-5">
                <div class="card">
                    <div class="card-header bg-primary">
                        <h3 class="text-white">Add User</h3>
                    </div>
                    <div class="card-body">

                        @if (Session::has('success'))
                            <div class="alert alert-success">
                                {{ Session::get('success') }}
                            </div>
                        @endif

                        <div class="">
                            <form id="formData" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <meta name="csrf-token" content="{{ csrf_token() }}">

                                @include('form')
                                <div class="form-group text-center mt-2">
                                    <button class="btn btn-success btn-submit">Submit</button>
                                    <button type="button" class="btn btn-danger">Cencel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
<style>
    .hide {
        display: none !important;
    }
</style>
<script src="{{ asset('resources/js/jquery.js') }}"></script>
<script src="{{ asset('resources/js/swal.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
<script>
    $(document).on('submit', '#formData', function(e) {

        e.preventDefault();
        var formData = new FormData(this);
        var name = $('.add_name').val();
        var phone = $('.add_phone').val();
        var check = 1;

        if (name.length == 0) {
            alert("Name field is required");
            return false;
        }

        if (phone.length == 0) {
            alert("phone field is required");
            return false;
        }

        if (check === 1) {
            $.ajax({
                type: 'POST',
                url: "{{ route('save.data') }}",
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(data) {
                    if (data.success == true) {
                        swal('User Added Successfully !');
                        $('#formData')[0].reset();
                        $('.form_render').addClass("hide");
                        $("#reload_data").load("{{ route('user.reload') }}");
                    } else {
                        alert('Error');
                    }
                },
            });
        } else {
            alert('Validation error');
        }
    });


    $(document).on('click', '.show_table', function() {
        $('.form_render').toggleClass("hide");
    });


    $(document).on('click', '.delete_record', function() {
        var data_url = $(this).attr("data-url");
        event.preventDefault();
        swal({
                title: `Are you sure you want to delete this record?`,
                text: "If you delete this, it will be gone forever.",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {

                    $.ajax({
                        url: data_url,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(data) {
                            if (data.success) {
                                swal("Record has been Deleted Successfully !");
                                $("#reload_data").load("{{ route('user.reload') }}");
                            } else {
                                swal("Error,Please Try Again");
                            }
                        }
                    });
                }
            });
    });


    var selectedHobbies = [];

    $(document).ready(function() {
        // Event handler for checkboxes
        $(document).on('change', 'input[name="hobby[]"]', function() {
            // Get the value of the checked checkbox
            var checkboxValue = $(this).val();

            // Check if the checkbox is checked or unchecked
            if ($(this).is(':checked')) {
                // Add the value to the selectedHobbies array if checked
                selectedHobbies.push(checkboxValue);
            } else {
                // Remove the value from the selectedHobbies array if unchecked
                var index = selectedHobbies.indexOf(checkboxValue);
                if (index !== -1) {
                    selectedHobbies.splice(index, 1);
                }
            }
        });
        // Edit user hide and show and ajax update functionality Js
        $(document).on('click', '.edit_user', function() {

            var $row = $(this).closest('tr');
            var $defaultView = $row.find('.default-view');
            var $editView = $row.find('.edit-view');
            $defaultView.hide();
            $editView.show();
            $row.data('edit-mode', true);
            toggleEditMode($row);
        });

        $(document).on('click', '.btn-cancel', function() {
            // var $row = $(this).closest('tr');
            var $row = $(this).closest('tr');
            var $defaultView = $row.find('.default-view');
            var $editView = $row.find('.edit-view');
            $defaultView.show();
            $editView.hide();
            // Update the edit mode flag
            $row.data('edit-mode', false);
            toggleViewMode($row);
        });

        $(document).on('click', '.btn-submit', function() {
            var $row = $(this).closest('tr');
            var userId = $row.data('user-id');
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            // Collect input values from the row
            var name = $row.find('input[name="name"]').val();
            var phone = $row.find('input[name="phone"]').val();
            var categoryId = $row.find('select[name="catagory"]').val();
            var image = $('#image').prop('files')[0];

            if (name.length == 0) {
                swal("name field is required");
                return false;
            }
            if (phone.length == 0) {
                swal("Contact no field is required");
                return false;
            }

            if (selectedHobbies.length === 0) {
                swal("At least one hobby is required");
                return false;
            }

            if (categoryId === null || categoryId.length === 0) {
                swal("Category field is required.");
                return false;
            }
            if (!image) {
                swal("Image field is required");
                return false;
            }

            var formData = new FormData();
            formData.append('name', name);
            formData.append('phone', phone);
            formData.append('hobbies', JSON.stringify(selectedHobbies));
            formData.append('catagory', categoryId);
            formData.append('image', $row.find('input[name="image"]')[0].files[0]);

            // var userData = {
            //     name: name,
            //     phone: phone,
            //     hobbies: hobbies,
            //     catagory: categoryId,
            // };
            $.ajax({
                url: '/update_user/' + userId,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    toggleViewMode($row);
                    swal("Record has been Updated Successfully !");
                    $("#reload_data").load("{{ route('user.reload') }}");
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });

    });


    function toggleEditMode($row) {
        // Toggle input fields and buttons
        $row.find('.table_data').hide();
        $row.find('input, select').removeClass('hide');
        $row.find('.btn-submit, .btn-cancel').removeClass('hide');
    }

    function toggleViewMode($row) {
        // Toggle input fields and buttons
        $row.find('.table_data').show();
        $row.find('input, select').addClass('hide');
        $row.find('.btn-submit, .btn-cancel').addClass('hide');
    }

    // bulk delete functionality for user delete
    $(document).on('click', '#bulkDelete', function() {
        var url = $(this).attr("data-url");
        var selectedUserIds = [];
        $('.checkbox:checked').each(function() {
            selectedUserIds.push($(this).data('user-id'));
        });

        if (selectedUserIds.length === 0) {
            swal('No users selected for deletion.', '', 'warning');
            return false;
        }

        swal({
            title: 'Bulk Delete Confirmation',
            text: 'Are you sure you want to delete the selected users?',
            icon: 'warning',
            buttons: {
                cancel: 'Cancel',
                confirm: 'Yes, delete',
            },
            dangerMode: true,
        }).then((confirmed) => {
            if (confirmed) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        user_ids: selectedUserIds
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content'),
                    },
                    success: function(response) {
                        if (response.success) {
                            swal("Record has been Deleted Successfully !");
                            $("#reload_data").load(
                                "{{ route('user.reload') }}");
                        } else {
                            swal('Error deleting users.', '', 'error');
                        }
                    },
                });
            }
        });
    });
</script>
