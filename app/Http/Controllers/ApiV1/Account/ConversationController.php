<?php

namespace App\Http\Controllers\ApiV1\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiV1\Account\ConversationStoreRequest;
use App\Http\Resources\ApiV1\ConversationResource;
use App\Models\Conversation;
use App\Services\ConversationService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use function auth;

class ConversationController extends Controller
{
    public function __construct(
        private ConversationService $conversationService
    )
    {
    }

    /**
     * Возвращает список собеседников с которыми общался пользователь
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $lastIds = $this->conversationService->getLastRecordOfEveryUser(auth()->id());

        return ConversationResource::collection(
            Conversation::whereIn('id', $lastIds)->get()
        );
    }

    /**
     * Отправляет сообщение пользователю
     *
     * @param ConversationStoreRequest $request
     * @return ConversationResource
     */
    public function store(ConversationStoreRequest $request): ConversationResource
    {
        return ConversationResource::make(
            $this->conversationService->store(
                $request->message,
                $request->receiver_id
            )
        );
    }

    /**
     * Возвращает весь список сообщений по конкретному собеседнику
     *
     * @param string $userId
     * @return AnonymousResourceCollection
     */
    public function show(string $userId): AnonymousResourceCollection
    {
        $this->conversationService->readMessages(auth()->id());

        return ConversationResource::collection(
            Conversation::forUser($userId)->orderBy('created_at', 'DESC')->paginate(30)
        );
    }
}
