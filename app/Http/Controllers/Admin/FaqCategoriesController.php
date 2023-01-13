<?php
 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FaqCategories;
use Illuminate\Http\Request;
use Alert;
use Image;
use Illuminate\Support\Facades\Hash;
use File;
use View;
use DataTables;
use Form;
use Illuminate\Support\Facades\Validator;

class FaqCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = [];

        if ($request->ajax()) {
            $data = FaqCategories::get();
            $datatable = Datatables::of($data);
            $datatable = $datatable->editColumn('status', function ($row) {
                if ($row->is_active == 1)
                    return '<label class="badge badge-success">Active</label>';
                else
                    return '<label class="badge badge-warning">Inactive</label>';
                
            });


            $datatable->addColumn('action', function ($row) {
                $actions = '';

                $actions .= Form::open([
                    'method' => 'POST',
                    'url' => ['admin/faq-categories/update-status'],
                    'style' => 'display:table;margin-right:10px;',
                    'class' => 'float-sm-right',
                    'id' => 'statusForm' . $row->id
                ]);
                $actions .= Form::hidden('id', encode($row->id));
                $actions .= Form::select('is_active', [
                    '0' => 'Inactive',
                    '1' => 'Active',
                ], $row->is_active, ['class' => 'form-control', 'onchange' => '$(form).submit();']);
                $actions .= Form::close();

                $actions .= '&nbsp;<a class="btn btn-primary btn-icon" href="' . url("admin/faq-categories/" . encode($row->id) . '/edit') . '" title="Edit"><i class="fa fa-pencil-alt"></i></a>';

                $actions .= '&nbsp;' . Form::open([
                    'method' => 'DELETE',
                    'url' => ['admin/faq-categories', encode($row->id)],
                    'style' => 'display:inline'
                ]);

                $actions .= Form::button('<i class="fa fa-trash fa-fw" title="Delete Category"></i>', ['class' => 'delete-form-btn btn btn-default btn-icon']);
                $actions .= Form::submit('Delete', ['class' => 'hidden deleteSubmit']);

                $actions .= Form::close();
                return $actions;
            });



            $datatable = $datatable->rawColumns(['status', 'action']);
            return $datatable->make(true);
        }

        return view('admin.faqCategories.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];
        $data['action'] = "Add";
       

        if (old()) {
            $data['faqCategory'] = old();
        }

        return view('admin.faqCategories.form')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

 
        $input = $request->all();

        if ($input['action'] == 'Edit') {
            
        $this->validate($request, [
                'title' => ['required', 'string','max:255']
              ]);

            $FaqCategories = FaqCategories::findOrFail($input['id']);
            
            $FaqCategories->update($input);
           
            \App\Events\UserEvents::dispatch('admin', 20, '', ['item_id' => $FaqCategories->id]);
            Alert::success('Success', 'Faq Category updated successfully.')->persistent('Close')->autoclose(5000);
        } else {
           
            $this->validate($request, [
                'title' => ['required', 'string', 'max:255']
               
            ]);

           
            $FaqCategories = FaqCategories::create($input);
            //EVENT LOG START
            \App\Events\UserEvents::dispatch('admin', 20, '', ['item_id' => $FaqCategories->id]);
           
            Alert::success('Success', 'Faq Category added successfully.')->persistent('Close')->autoclose(5000);
        }
               
        return redirect('admin/faq-categories');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = decode($id);
        $data['faqCategory'] = FaqCategories::findOrFail($id);
        $data['action'] = "Edit";
        return view('admin.faqCategories.form')->with($data);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */

    public function destroy($id)
    {
   
        $id = decode($id);
         FaqCategories::destroy($id);
        //EVENT LOG START
        \App\Events\UserEvents::dispatch('admin', 20, '', ['item_id' => $id]);
        Alert::success('Success', 'Faq Category deleted successfully!')->persistent('Close')->autoclose(5000);
        return redirect()->back();
    }

    // Update Status
    public function update_status(Request $request)
    {
        $input = $request->all();

        $is_active = $input['is_active'];
        $id = decode($input['id']);

        if ($is_active <> '' && $id <> '') {
            $data = array(
                'is_active' => $is_active,
            );

            FaqCategories::whereId($id)->update($data);
            //EVENT LOG START
        \App\Events\UserEvents::dispatch('admin', 20, '', ['item_id' => $id]);
            Alert::success('Success', 'status updated successfully!')->persistent('Close')->autoclose(5000);
        } else {
            Alert::error('Error', 'Error occured. Status not updated!')->persistent('Close')->autoclose(5000);
        }
        return redirect()->back();
    }


}
