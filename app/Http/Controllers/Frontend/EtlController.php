<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Countries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Image;
use File;
use App\Models\Currency;

class EtlController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['verified']);
    }

    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $data = [];
        $data['profile'] = auth()->user();
        return view('frontend.etl.etl')->with($data);
    }

    /**
     * etl
     *
     * @param  mixed $request
     * @return void
     */
    public function etl(Request $request)
    {
        $User = auth()->user();
    
        $validation = $request->validate([
            'etl_information' => ['required'],
        ]);

        DB::beginTransaction();
        try {
            $input = $request->all();

            $input['approved_status'] = 0;
            $User->update($input);

            //MAKE DIRECTORY
            $upload_path = public_path() . '/storage/uploads/users/' . $User->id;
            if (!File::exists(public_path() . '/storage/uploads/users/' . $User->id)) {

                File::makeDirectory($upload_path, 0777, true);
            }

            if (!empty($request->files) && $request->hasFile('etl_images')) {
                $images = [];
                foreach ($request->file('etl_images') as $file) {
                    $type      = strtolower($file->getClientOriginalExtension());
                    $size      = $file->getSize();
                    $file_name = $file->getClientOriginalName();
                    $size_mbs  = ($size / 1024) / 1024;
                    $file_temp_name = str_replace(' ','-',$file_name);
                    $path = public_path('storage/uploads/users/') . $User->id . '/' . $file_temp_name;
                    if (in_array($type, ['jpg', 'jpeg', 'png', 'gif', 'jfif'])) {
                        if ($size_mbs >= 2) {
                            Image::make($file)->resize(1024, null, function ($constraint) {
                                $constraint->aspectRatio();
                            })->save($path);
                        } else {
                            Image::make($file)->resize(1024, null, function ($constraint) {
                                $constraint->aspectRatio();
                            })->save($path);
                        }
                    } else {
                        $file->move($path, $file_temp_name);
                    }
                    $images[] = $file_temp_name;
                }
                if (count($images) > 0) {
                    $User->etl_images = implode(',', $images);
                    $User->save();
                }
            }




            DB::commit();

            $request->session()->flash('success', 'ETL updated successfully and waiting for Admin approval.');

            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            $request->session()->flash('error', $e->getMessage());

            return redirect()->back()->withErrors($validation)->withInput();
        }
    }
}
