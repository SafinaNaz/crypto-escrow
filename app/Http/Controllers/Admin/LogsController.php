<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserEvent;
use Illuminate\Http\Request;
use DataTables;

class LogsController extends Controller
{

    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $data = array();
        if (!auth()->user()->can('View Admin Logs')) {
            $request->session()->flash('error', 'You Dont have permission to access this page.');
                return redirect()->back();
        }
        // $db_record = UserEvent::with('eventType')->orderByDesc('id')->get();
        // dd($db_record);

        if ($request->ajax()) {
            $db_record = UserEvent::with('eventType')->where('is_admin', 1)->orderByDesc('id');

            $datatable = DataTables::of($db_record);

            $datatable = $datatable->editColumn('admin_name', function ($row) {
                return $row->meta['firstname'] . ' ' . $row->meta['lastname'];
            });
            $datatable = $datatable->editColumn('event_type', function ($row) {
                return $row->eventType->event_name;
            });

            $datatable = $datatable->editColumn('created_at', function ($row) {
                return date('M d,Y', strtotime($row->created_at));
            });

            $datatable = $datatable->rawColumns(['admin_name', 'event_type', 'created_at']);
            $datatable = $datatable->make(true);
            return $datatable;
        }
        return view('admin.logs.index')->with($data);
    }
}
