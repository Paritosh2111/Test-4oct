<?php

namespace App\Http\Controllers;

use App\Models\Catagory;
use App\Models\Hobby;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(){

        $users = User::with('hobbies','catagory')->get();
        $catagories = Catagory::all();
        $hobbies = Hobby::all();
        return view('welcome',compact('catagories','users','hobbies'));
    }

    public function save(Request $request)
    {
        // dd($request->all());
        $rules = [
            'name' => 'required',
            'phone' => 'required',
            // 'phone' => 'required|numeric|digits:10',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->hasFile('image')) {
            $randomFileName = '/user_' . rand(1000, 9999) . '_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
            $path = $request->file('image')->storeAs('public/images', $randomFileName);
            $url = asset('storage/images/' . $randomFileName);
        }

        $data = User::create([
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'catagory_id' => $request->input('catagory'),
            'image_path' => $randomFileName,
        ]);

        if($request->hobby != null){
            $data_id = User::findorfail($data->id);
            $hobbies = $request->hobby;
            $data_id->hobbies()->attach($hobbies);
        }
        return response(['success' => true, 'data' => $data]);
    }

    public function delete($id){

        $data = User::findorfail($id);
        $data->delete();
        if($data){
            return response()->json(['success'=>true,'data' => $data]);
        }else{
            return response()->json(['success'=>false]);
        }
    }

    public function reloadData(){
        $users = User::with('hobbies')->get();
        $hobbies = Hobby::all();
        $catagories = Catagory::all();

        return view('tableData',compact('users','hobbies','catagories'))->render();
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'catagory' => 'required|integer',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust image validation rules as needed
        ]);

        $user = User::findOrFail($id);

        $user->name = $request->input('name');
        $user->phone = $request->input('phone');
        $user->catagory_id = $request->input('catagory');

        if ($request->has('hobbies')) {
            $hobbies = json_decode($request->input('hobbies'), true);
            $user->hobbies()->sync($hobbies);
        } else {
            $user->hobbies()->detach();
        }


        if ($request->hasFile('image')) {
            if ($user->image_path) {
                Storage::delete('public/images/' . $user->image_path);
            }
            $imagePath = $request->file('image')->store('public/images');
            $user->image_path = basename($imagePath);
        }
        $user->save();
        return response()->json(['success'=>true]);
    }

    public function bulk_delete(Request $request)
    {
        $data = $request->input('user_ids');
        if (!empty($data)) {
            User::whereIn('id', $data)->delete();
            return response()->json(['success' => true]);
        }else{
            return response()->json(['success' => false]);
        }
    }
}
