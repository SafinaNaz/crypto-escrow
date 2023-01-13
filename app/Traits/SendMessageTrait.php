<?php

namespace App\Traits;

use App\Models\Messages;
use App\Models\MessageThreads;

trait SendMessageTrait
{

    public function sendMessageFromTrait($input)
    {

        $thread = MessageThreads::findOrFail($input['thread_id']);
        if ($thread) {
            try {

                if ($input['is_private'] == 1) {
                    $message = encryptText($input['message']);
                } else {
                    $message = $input['message'];
                }
                
                $messages = Messages::create(
                    [
                        'thread_id' => $input['thread_id'],
                        'sender_id' => $input['sender_id'],
                        'receiver_id' => $input['receiver_id'],
                        'message' => $message,
                        'is_private' => $input['is_private'],
                        'is_admin' => $input['is_admin'],
                        'is_dispute' => $input['is_dispute']
                    ]
                );

                if ($input['is_admin'] == 0) {
                    $meta = ['item_id' => $input['thread_id'], 'thread_id' => $input['thread_id'], 'message_id' => $messages->id];
                    if ($input['is_dispute'] == 0) {
                        if ($input['is_private'] == 0) {
                            if (auth()->user()->user_type == 2) {
                                $type = 35;
                            } else {
                                $type = 36;
                            }
                        } else {
                            if (auth()->user()->user_type == 2) {
                                $type = 37;
                            } else {
                                $type = 38;
                            }
                        }
                    } else {
                        if (auth()->user()->user_type == 2) {
                            $type = 39;
                        } else {
                            $type = 40;
                        }
                    }
                    \App\Events\UserEvents::dispatch('web', $type, '', $meta, $input['receiver_id']);
                }

                if ($input['is_private'] == 1) {
                    return ['status' => 2, 'message' => 'Message sent successfully!', 'id' => $messages->id];
                }

                return ['status' => 1, 'message' => 'Message sent successfully!', 'id' => $messages->id];
            } catch (\Exception $e) {

                return ['status' => 0, 'message' => $e->getMessage(), 'id' => 0];
            }
        } else {
            return ['status' => 0, 'message' => 'You are not allowed to send Message.', 'id' => 0];
        }
    }
}
