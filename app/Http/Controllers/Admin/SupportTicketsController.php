<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use DataTables;
use Alert;
use Carbon\Carbon;
use DB;
use File;
use Image;

class SupportTicketsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = array();
        if (!auth()->user()->can('View Tickets')) {
            $request->session()->flash('error', 'You Dont have permission to access this page.');
                return redirect()->back();
        }
        if ($request->ajax()) {
            $db_record = SupportTicket::with(['ticketUser', 'messages'])->orderByDesc('id')->get();

            $datatable = DataTables::of($db_record);

            $datatable->addColumn('user', function ($row) {
                return $row->ticketUser->full_name();
            });


            $datatable->addColumn('status', function ($row) {
                return $row->status();
            });

            $datatable->addColumn('last_reply', function ($row) {
                return $row->get_date();
            });

            $datatable->addColumn('action', function ($row) {
                $actions = '<a class="btn btn-primary btn-icon" href="' . url("admin/support-ticket/" . encode($row->id) . '/view') . '" title="View Ticket">View</a>';
                return $actions;
            });

            $datatable = $datatable->rawColumns(['user',  'message', 'status', 'last_reply', 'action']);
            $datatable = $datatable->make(true);
            return $datatable;
        }
        return view('admin.support.index')->with($data);
    }

    /**
     * view
     *
     * @param  mixed $id
     * @return void
     */
    public function view($id)
    {
        $data = array();

        $id = decode($id);

        $data['ticket'] = SupportTicket::with(['ticketUser'])->where('id', $id)->first();

        $data['messages'] = SupportTicketMessage::where('ticket_id', $id)->paginate(10);

        return view('admin.support.view')->with($data);
    }
    /**
     * close_ticket
     *
     * @param  mixed $id
     * @param  mixed $request
     * @return void
     */
    public function change_status_ticket($id, $type, Request $request)
    {
        $data = [];

        $id = decode($id);
        if ($id) {
            if ($type == 'close') {
                $data['ticket'] = SupportTicket::where('id', $id)->update(['status' => 2, 'updated_at' => date('Y-m-d H:i:s')]);
                Alert::success('Success', 'Support Ticket closed successfully.')->persistent('Close')->autoclose(5000);
                return redirect()->to('admin/support-ticket');
            }
            if ($type == 'open') {
                $data['ticket'] = SupportTicket::where('id', $id)->update(['status' => 1, 'updated_at' => date('Y-m-d H:i:s')]);
                Alert::success('Success', 'Support Ticket opened successfully.')->persistent('Close')->autoclose(5000);
                return redirect()->back();
            }
        }

        
    }

    public function reply(Request $request)
    {
        $User = auth()->user();

        $validation = $request->validate([
            'message' => ['required'],
        ]);

        DB::beginTransaction();
        try {
            $input = $request->all();

            SupportTicket::where('id', $input['ticket_id'])->update(['message_status' => 1, 'updated_at' => date('Y-m-d H:i:s')]);

            $ticketMsg = SupportTicketMessage::create(
                [
                    'ticket_id' => $input['ticket_id'],
                    'user_id' => auth()->user()->id,
                    'message' => $input['message'],
                    'is_admin' => 1
                ]
            );

            //MAKE DIRECTORY
            $upload_path = public_path() . '/storage/uploads/support';
            if (!File::exists(public_path() . '/storage/uploads/support')) {
                File::makeDirectory($upload_path, 0777, true);
            }

            $upload_path = public_path() . '/storage/uploads/support/' . $input['ticket_id'];
            if (!File::exists(public_path() . '/storage/uploads/support/' . $input['ticket_id'])) {
                File::makeDirectory($upload_path, 0777, true);
            }

            if (!empty($request->files) && $request->hasFile('files')) {
                $images = [];
                foreach ($request->file('files') as $file) {
                    $type      = strtolower($file->getClientOriginalExtension());
                    $size      = $file->getSize();
                    $file_name = $file->getClientOriginalName();
                    $size_mbs  = ($size / 1024) / 1024;
                    $file_temp_name = str_replace(' ', '-', $file_name);
                    $path = public_path('storage/uploads/support/') . $input['ticket_id'] . '/' . $file_temp_name;
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
                    $ticketMsg->files = implode(',', $images);
                    $ticketMsg->save();
                }
            }

            DB::commit();

            Alert::success('Success', 'Support Ticket Replied successfully.')->persistent('Close')->autoclose(5000);
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('Error', $e->getMessage())->persistent('Close')->autoclose(5000);

            return redirect()->back()->withErrors($validation)->withInput();
        }
    }
}
