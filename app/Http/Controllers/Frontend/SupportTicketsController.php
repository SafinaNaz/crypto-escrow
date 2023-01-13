<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
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
    public function index()
    {
        $data = [];


        $data['tickets'] = SupportTicket::with('ticketUser')->where('user_id', auth()->user()->id)->paginate(10);

        return view('frontend.support.tickets', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];
        return view('frontend.support.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $User = auth()->user();

        $validation = $request->validate([
            'subject' => ['required'],
            'message' => ['required'],
        ]);

        DB::beginTransaction();
        try {
            $input = $request->all();

            $ticket = SupportTicket::create([
                'user_id' => auth()->user()->id,
                'subject' => $input['subject'],
                'status' => 0
            ]);

            $ticketMsg = $ticket->messages()->create(
                [
                    'user_id' => auth()->user()->id,
                    'message' => $input['message'],
                    'is_admin' => 0
                ]

            );

            //MAKE DIRECTORY
            $upload_path = public_path() . '/storage/uploads/support';
            if (!File::exists(public_path() . '/storage/uploads/support')) {
                File::makeDirectory($upload_path, 0777, true);
            }

            $upload_path = public_path() . '/storage/uploads/support/' . $ticket->id;
            if (!File::exists(public_path() . '/storage/uploads/support/' . $ticket->id)) {
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
                    $path = public_path('storage/uploads/support/') . $ticket->id . '/' . $file_temp_name;
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

            $request->session()->flash('success', 'Support Ticket created successfully.');

            return redirect()->to('support-ticket');
        } catch (\Exception $e) {
            DB::rollback();
            $request->session()->flash('error', $e->getMessage());

            return redirect()->back()->withErrors($validation)->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        $data = [];

        $id = decode($id);

        $data['ticket'] = SupportTicket::with(['ticketUser'])->where('id', $id)->first();

        $data['messages'] = SupportTicketMessage::where('ticket_id', $id)->paginate(10);

        return view('frontend.support.view', $data);
    }


    /**
     * change_status_ticket
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
                $request->session()->flash('success', 'Support Ticket closed successfully.');
                return redirect()->to('support-ticket');
            }
            if ($type == 'open') {
                $data['ticket'] = SupportTicket::where('id', $id)->update(['status' => 1, 'updated_at' => date('Y-m-d H:i:s')]);
                $request->session()->flash('success', 'Support Ticket opened successfully.');
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

            SupportTicket::where('id', $input['ticket_id'])->update(['message_status' => 0, 'updated_at' => date('Y-m-d H:i:s')]);

            $ticketMsg = SupportTicketMessage::create(
                [
                    'ticket_id' => $input['ticket_id'],
                    'user_id' => auth()->user()->id,
                    'message' => $input['message'],
                    'is_admin' => 0
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

            $request->session()->flash('success', 'Support Ticket Replied successfully.');

            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            $request->session()->flash('error', $e->getMessage());

            return redirect()->back()->withErrors($validation)->withInput();
        }
    }
}
