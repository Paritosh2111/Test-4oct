<style>
    .field-container {
        padding: 10px 0;
        border-bottom: 1px solid #ccc;
        margin-bottom: 10px;
    }

    .field-container:first-child {
        border-top: none; /* Remove the top border from the first field */
    }

    .form-group {
        margin-bottom: 0; /* Remove margin to avoid extra spacing */
    }
</style>

<div class="container">

        <div class="row field-container">
            <div class="col-md-6">
                <div class="form-group">
                    <strong>Name: <span class="text-danger">*</span></strong>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <input type="text" name="name" class="form-control add_name" placeholder="Enter name">
                    <span class="text-danger name_error"></span>
                </div>
            </div>
        </div>

        <div class="row field-container">
            <div class="col-md-6">
                <div class="form-group">
                    <strong>Contact no. <span class="text-danger">*</span></strong>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <input type="text" name="phone" class="form-control add_phone" placeholder="Enter phone">
                    <span class="text-danger phone_error"></span>
                </div>
            </div>
        </div>

        <div class="row field-container">
            <div class="col-md-6">
                <div class="form-group">
                    <strong>Hobby:</strong>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <div class="hobby-checkboxes">
                        @if ($hobbies)
                            @foreach ($hobbies as $hobby)
                                <div class="form-check">
                                    <input class="form-check-input" name="hobby[]" type="checkbox" value="{{ $hobby->id }}">
                                    <label class="form-check-label" for="hobby_{{ $hobby->id }}">{{ $hobby->name }}</label>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row field-container">
            <div class="col-md-6">
                <div class="form-group">
                    <strong>Catagory: <span class="text-danger">*</span></strong>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <select class="form-control catagory" name="catagory" required>
                        <option selected disabled value="">Select a category</option>
                        @foreach ($catagories as $catagory)
                            <option value="{{ $catagory->id }}">{{ $catagory->name }}</option>
                        @endforeach
                    </select>
                    <span class="text-danger catagory_error"></span>
                </div>
            </div>
        </div>

        <div class="row field-container">
            <div class="col-md-6">
                <div class="form-group">
                    <strong>Picture:</strong>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <input type="file" name="image" id="image" class="form-control image" accept="image/*" placeholder="Upload image">
                </div>
            </div>
        </div>
</div>
