<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MessageThreads;
use App\Models\Messages;
use Illuminate\Support\Facades\Auth;

class ChatsController extends Controller
{
    use \App\Traits\SendMessageTrait;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show chats
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$id = null )
    {
        $input = $request->all();
        $data = [];
        $whr = ' 1';
        if (isset($input['message']) && $input['message'] == 'unread') {
            $whr .= ' AND is_read = 0';
        }
        $data['search'] = $search = '';
        if (isset($input['search']) && $input['search'] <> '') {

            $data['search']  = $search = $input['search'];

            $ids = Messages::where('message', 'like', '%' . $search . '%')
                ->where('sender_id', Auth::id())
                ->orWhere('receiver_id', Auth::id())->distinct()->pluck('thread_id')->toArray();
            if ($ids) {
                $whr .= ' AND id IN (' . implode(',', $ids) . ')';
            }
        }

        $data['threads'] = MessageThreads::with(['messages' => function ($q) {
            return $q->where('is_dispute', 0);
        }])
            ->wherehas('product', function ($query) {
                $query->select('id')->wherehas('productTransaction', function ($query) {
                    return $query->whereNotIn('status_id', [7, 9]);
                });
            })

            ->whereRaw('(seller_id = ' . Auth::id() . ' || buyer_id = ' . Auth::id() . ')')
            ->whereRaw($whr)
            ->orderByDesc('id')
            ->paginate(10);


        if ($id) {
            $product_id = decode($id);

            if ($product_id) {

                $data['current_thread']  = MessageThreads::with(['messages' => function ($q) {
                    return $q->where('is_dispute', 0);
                }])
                    ->wherehas('product', function ($query) {
                        $query->select('id')->wherehas('productTransaction', function ($query) {
                            return $query->whereNotIn('status_id', [7, 9]);
                        });
                    })
                    ->where('product_id', $product_id)
                    ->orderByDesc('id')
                    ->first();
                $data['thread_id'] = $data['current_thread']->id;
                MessageThreads::where('product_id', $product_id)->update(['is_read' => 1]);
            }
        } else {
            if (count($data['threads']) > 0) {
                MessageThreads::where('id', $data['threads'][0]->id)->update(['is_read' => 1]);
                $data['current_thread']  = $data['threads'][0];
                $data['thread_id'] = $data['threads'][0]->id;
            }
        }


        $data['tab'] = $input['tab'] ?? 'public';

        $data['queryString'] = $request->getQueryString() ? '?' . $request->getQueryString() : '';
        return view('frontend.messaging.index')->with($data);
    }

    /**
     * Persist message to database
     *
     * @param  Request $request
     * @return Response
     */
    public function sendMessage(Request $request)
    {
        $user = auth()->user();
        $input = $request->all();
        $input['sender_id'] = $user->id;
        $input['is_admin'] = 0;
        $input['is_dispute'] = 0;
        $response =  $this->sendMessageFromTrait($input);
        if ($response['status'] == 2) {
            $request->session()->flash('success', $response['message']);
            return redirect('messages?tab=private');
        } else {
            $request->session()->flash('success', $response['message']);
            return redirect('messages');
        }
    }
}
