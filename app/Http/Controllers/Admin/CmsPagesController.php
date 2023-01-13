<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsPages;
use Illuminate\Http\Request;
use Alert;
use DataTables;
use Form;

class CmsPagesController extends Controller
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
        if (!auth()->user()->can('View CMS Pages')) {
            $request->session()->flash('error', 'You Dont have permission to access this page.');
                return redirect()->back();
        }
        $data = array();
        if ($request->ajax()) {
            $db_record = CmsPages::orderByDesc('id');

            $datatable = DataTables::of($db_record);
            $datatable = $datatable->editColumn('url', function ($row) {
                return '<a href="' . url($row->seo_url) . '" target="_blank" >Go to Page</a>';
            });

            $datatable = $datatable->editColumn('is_home', function ($row) {
                if ($row->is_home == 1) {
                    $status = ' <label class="badge badge-success">Yes</label>';
                } else {
                    $status = '<label class="badge badge-warning">No</label>';
                }

                return $status;
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

            $datatable = $datatable->addColumn('action', function ($row) {
                $actions = '';

                $actions .= Form::open([
                    'method' => 'POST',
                    'url' => ['admin/cms-pages/update-status'],
                    'style' => 'display:table;margin-right:10px;',
                    'class' => 'float-sm-left',
                    'id' => 'statusForm' . $row->id
                ]);
                $actions .= Form::hidden('id', encode($row->id));
                $actions .= Form::select('is_active', [
                    '0' => 'Inactive',
                    '1' => 'Active'
                ], $row->is_active, ['class' => 'form-control', 'onchange' => '$(form).submit();']);
                $actions .= Form::close();
                $actions .= '<a class="btn btn-primary btn-icon" href="' . url("admin/cms-pages/" . encode($row->id) . '/edit') . '" title="Edit"><i class="fa fa-pencil-alt"></i></a>';
                $actions .= Form::open([
                    'method' => 'DELETE',
                    'url' => ['admin/cms-pages', encode($row->id)],
                    'style' => 'display:inline'
                ]);
                $actions .= Form::button('<i class="fa fa-trash fa-fw" title="Delete Cms Page"></i>', ['class' => 'delete-form-btn btn btn-default btn-icon']);
                $actions .= Form::submit('Delete', ['class' => 'hidden deleteSubmit']);
                $actions .= Form::close();

                $actions .= '';
                return $actions;
            });

            $datatable = $datatable->rawColumns(['url', 'status', 'action', 'is_home']);
            $datatable = $datatable->make(true);
            return $datatable;
        }
        return view('admin.cmsPages.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        if (!auth()->user()->can('Add CMS Pages')) {
            $request->session()->flash('error', 'You Dont have permission to access this page.');
                return redirect()->back();
        }
        $data['action'] = "Add";
        return view('admin.cmsPages.edit')->with($data);
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
        if (!auth()->user()->can('Update CMS Pages')) {
            $request->session()->flash('error', 'You Dont have permission to access this page.');
                return redirect()->back();
        }
        $id = decode($id);
        $data['action'] = "Edit";
        $data['cmsPage'] = CmsPages::findOrFail($id);
        return view('admin.cmsPages.edit')->with($data);
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
        if (!isset($input['sort_by']) || $input['sort_by'] == '') {
            $input['sort_by'] = 0;
        }
        if ($input['action'] == 'Edit') {
            $CmsPages = CmsPages::findOrFail($input['id']);
            if ($CmsPages->is_static == 1) {
                file_put_contents($CmsPages->description, str_replace('textbox', 'textarea', $input['file_content']));
            }
            $sqlChk = CmsPages::whereRaw('seo_url = "' . $input['seo_url'] . '" AND id <>  ' . $input['id'])->first();
            if ($sqlChk) {
                $input['seo_url'] = $input['seo_url'] . '-' . rand(1, 99999);
            }
            $CmsPages->update($input);
            //EVENT LOG START
            \App\Events\UserEvents::dispatch('admin', 20, '', ['item_id' => $CmsPages->id]);
            Alert::success('Success', 'CMS Page updated successfully!')->persistent('Close')->autoclose(5000);
        } else {

            $sqlChk = CmsPages::where('seo_url', $input['seo_url'])->first();
            if ($sqlChk) {
                $input['seo_url'] = $input['seo_url'] . '-' . rand(1, 99999);
            }

            $CmsPages = CmsPages::create($input);
            //EVENT LOG START
            \App\Events\UserEvents::dispatch('admin', 19, '', ['item_id' => $CmsPages->id]);
            Alert::success('Success', 'CMS Page added successfully!')->persistent('Close')->autoclose(5000);
        }

        return redirect('admin/cms-pages');
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
        if (!auth()->user()->can('Delete CMS Pages')) {
            $request->session()->flash('error', 'You Dont have permission to access this page.');
                return redirect()->back();
        }
        CmsPages::destroy($id);
        //EVENT LOG START
        \App\Events\UserEvents::dispatch('admin', 21, '', ['item_id' => $id]);
        Alert::success('Success', 'CMS Page deleted successfully!')->persistent('Close')->autoclose(5000);
        return redirect()->back();
    }

    // Update Status
    public function update_status(Request $request)
    {
        $input = $request->all();
        if (!auth()->user()->can('Update CMS Pages')) {
            $request->session()->flash('error', 'You Dont have permission to access this page.');
                return redirect()->back();
        }
        $is_active = $input['is_active'];
        $id = decode($input['id']);

        if ($is_active <> '' && $id <> '') {
            $data = array(
                'is_active' => $is_active,
            );

            CmsPages::whereId($id)->update($data);
            //EVENT LOG START
            \App\Events\UserEvents::dispatch('admin', 22, '', ['item_id' => $id]);
            Alert::success('Success', 'CMS Page status updated successfully!')->persistent('Close')->autoclose(5000);
        } else {
            Alert::error('Error', 'Error occured. CMS Page status not updated!')->persistent('Close')->autoclose(5000);
        }
        return redirect()->back();
    }
}
