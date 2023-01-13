<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Templates;
use Illuminate\Http\Request;
use Alert;
use File;
use DataTables;
use Form;

class TemplatesController extends Controller
{

    public function __construct()
    {
        //setTimeZone();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        if (!auth()->user()->can('View Templates')) {
            $request->session()->flash('error', 'You Dont have permission to access this page.');
                return redirect()->back();
        }
        $user = \auth()->user();
        $data = array();
        if ($request->ajax()) {
            $db_record = Templates::orderByDesc('id');

            $datatable = DataTables::of($db_record);


            $datatable = $datatable->editColumn('template_type', function ($row) {
                return '<label class="badge badge-success">Email</label>';
            });


            $datatable = $datatable->editColumn('status', function ($row) {
                $status = '<i class="badge badge-primary"></i>';
                if ($row->is_active == 1) {
                    $status = ' <label class="badge badge-success">Active</label>';
                } else if ($row->is_active == 0) {
                    $status = '<label class="badge badge-warning">Inactive</label>';
                } else {
                    $status = '<label class="badge badge-danger">Deleted</label>';
                }

                return $status;
            });

            $datatable->addColumn('action', function ($row) {
                $actions = '';

                $actions .= Form::open([
                    'method' => 'POST',
                    'url' => ['admin/templates/update-status'],
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

                $actions .= '&nbsp;<a class="btn btn-primary btn-icon" href="' . url("admin/templates/" . encode($row->id) . '/edit') . '" title="Edit"><i class="fa fa-pencil-alt"></i></a>';

                $actions .= '&nbsp;' . Form::open([
                    'method' => 'DELETE',
                    'url' => ['admin/templates', encode($row->id)],
                    'style' => 'display:inline'
                ]);

                $actions .= Form::button('<i class="fa fa-trash fa-fw" title="Delete Seller"></i>', ['class' => 'delete-form-btn btn btn-default btn-icon']);
                $actions .= Form::submit('Delete', ['class' => 'hidden deleteSubmit']);

                $actions .= Form::close();
                return $actions;
            });

            $datatable = $datatable->rawColumns(['template_type', 'status', 'action']);
            $datatable = $datatable->make(true);
            return $datatable;
        }
        return view('admin.templates.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        if (!auth()->user()->can('Add Templates')) {
            $request->session()->flash('error', 'You Dont have permission to access this page.');
                return redirect()->back();
        }
        $data = [];
        $data['action'] = "Add";
        return view('admin.templates.edit')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        if (!auth()->user()->can('Update Templates')) {
            $request->session()->flash('error', 'You Dont have permission to access this page.');
                return redirect()->back();
        }
        $data = [];
        $id = decode($id);
        $data['action'] = "Edit";

        $data['template'] = Templates::findOrFail($id);
        return view('admin.templates.edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {

        $input = $request->all();

        if ($input['template_type'] == 2) {
            $input['content'] = $input['content_sms'];
        } else {
            $input['content'] = $input['content'];
        }
        unset($input['content_sms']);

        if ($input['action'] == 'Edit') {
            $template = Templates::findOrFail($input['id']);
            $template->update($input);
            //EVENT LOG START
            \App\Events\UserEvents::dispatch('admin', 26, '', ['item_id' => $template->id]);
            Alert::success('Success', 'Template updated successfully!')->persistent('Close')->autoclose(5000);
        } else {

            $template = Templates::create($input);
            //EVENT LOG START
            \App\Events\UserEvents::dispatch('admin', 25, '', ['item_id' => $template->id]);
            Alert::success('Success', 'Template added successfully!')->persistent('Close')->autoclose(5000);
        }

        if ($request->hasFile('attachment')) {
            $old_attachment = $input['old_attachment'];
            $destinationPath = 'uploads/templates'; // upload path
            $attachment = $request->file('attachment'); // file
            $extension = $attachment->getClientOriginalExtension() == 'jfif' ? 'png' : $attachment->getClientOriginalExtension(); // getting image extension
            $fileName = $template->id . '-' . time() . '.' . $extension; // renameing image
            $attachment->move($destinationPath, $fileName); // uploading file to given path
            //remove old image
            if ($old_attachment) {
                File::delete($destinationPath . '/' . $old_attachment);
            }
            //insert image record
            $temp_attachment['attachment'] = $fileName;
            $template->update($temp_attachment);
        }

        return redirect('admin/templates');
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
        if (!auth()->user()->can('Delete Templates')) {
            $request->session()->flash('error', 'You Dont have permission to access this page.');
                return redirect()->back();
        }
        $id = decode($id);

        $data = array(
            'is_active' => 2,
        );

        Templates::whereId($id)->update($data);
        //EVENT LOG START
        \App\Events\UserEvents::dispatch('admin', 27, '', ['item_id' => $id]);
        //        Templates::destroy($id);
        Alert::success('Success', 'Template deleted successfully!')->persistent('Close')->autoclose(5000);

        return redirect('admin/templates');
    }

    // Update Status
    public function update_status(Request $request)
    {
        if (!auth()->user()->can('Update Templates')) {
            $request->session()->flash('error', 'You Dont have permission to access this page.');
                return redirect()->back();
        }
        $input = $request->all();

        $is_active = $input['is_active'];
        $id = decode($input['id']);

        if ($is_active <> '' && $id <> '') {
            $data = array(
                'is_active' => $is_active,
            );

            Templates::whereId($id)->update($data);
            //EVENT LOG START
            \App\Events\UserEvents::dispatch('admin', 28, '', ['item_id' => $id]);
            Alert::success('Success', 'Template status updated successfully!')->persistent('Close')->autoclose(5000);
        } else {
            Alert::error('Error', 'Error occured. Template status not updated!')->persistent('Close')->autoclose(5000);
        }
        return redirect()->back();
    }
}
