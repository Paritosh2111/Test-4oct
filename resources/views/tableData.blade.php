@foreach ($users as $data)
    <tr data-user-id="{{ $data->id }}">
        <td>{{ $loop->index + 1 }}</td>
        <td><input type="checkbox" class="checkbox" data-user-id="{{ $data->id }}"></td>
        <td>
            <div class="table_data">{{ $data->name }}</div><input type="text" name="name"
                class="form-control name hide" placeholder="Enter name" required>
        </td>

        <td>
            <div class="table_data">{{ $data->phone }}</div><input type="text" name="phone"
                class="form-control phone hide" placeholder="Enter phone" required>
        </td>

        <td class="default-view">
            @foreach ($data->hobbies as $hobby)
                <div>
                    <input hidden class="form-check-input" name="hobby[]" type="checkbox" value="{{ $hobby->id }}"
                        checked disabled>
                    <label style="opacity:3.5" class="form-check-label">{{ $hobby->name }}</label>
                </div>
            @endforeach
        </td>
        <td class="edit-view" style="display: none;">
            @foreach ($hobbies as $hobby)
                <input class="form-check-input hide" name="hobby[]" type="checkbox" value="{{ $hobby->id }}"
                    {{ in_array($hobby->id, $data->hobbies->pluck('id')->toArray()) ? 'checked' : '' }} required>
                <div class="">{{ $hobby->name }}</div>
            @endforeach
        </td>


        <td>
            @if ($data->catagory)
                <div class="table_data">{{ $data->catagory->name }}</div>
                <select class="form-control catagory hide" name="catagory" required>
                    <option selected disabled value="">Select a catagory</option>
                    @foreach ($catagories as $catagory)
                        <option value="{{ $catagory->id }}">{{ $catagory->name }}</option>
                    @endforeach
                </select>
            @else
                <select class="form-control catagory hide" name="catagory" required>
                    <option selected disabled value="">Select a category</option>
                    @foreach ($catagories as $catagory)
                        <option value="{{ $catagory->id }}">{{ $catagory->name }}</option>
                    @endforeach
                </select>
                No Data Available
            @endif
        </td>
        <td>
            <img src="{{ asset('storage/images/' . $data->image_path) }}" alt="Image Description" width="100px">
            <input type="file" name="image" id="image" accept="jpeg, png, jpg, gif" class="form-control image hide" accept="image/*"
                placeholder="Upload image" required>
        </td>
        <td>
            <a data-url="{{ route('user.edit', $data->id) }}" data-id="{{ $data->id }}"
                class="btn btn-primary edit_user">Edit</a>
            <button class="btn btn-success btn-submit hide">Submit</button>
            <button class="btn btn-danger btn-cancel hide">Cancel</button>
            <a data-url="{{ route('user.delete', $data->id) }}" data-id="{{ $data->id }}"
                class="btn btn-danger delete_record">Delete</a>
        </td>
    </tr>
@endforeach
