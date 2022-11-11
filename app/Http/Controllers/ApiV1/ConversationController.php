<?php

namespace App\Http\Controllers\ApiV1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiV1\ConversationStoreRequest;
use App\Http\Resources\ApiV1\ConversationResource;
use App\Models\Conversation;
use App\Services\ConversationService;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function __construct(
        private ConversationService $conversationService
    )
    {
    }

    public function index()
    {
        $lastIds = $this->conversationService->getLastRecorOfEveryUser(auth()->id());

        return ConversationResource::collection(
            Conversation::whereIn('id', $lastIds)
                ->with(['sender', 'receiver'])
                ->get()
        );
    }

    public function store(ConversationStoreRequest $request)
    {
        return ConversationResource::make(
            $this->conversationService->store(
                $request->message,
                $request->receiver_id
            )
        );
    }
}
