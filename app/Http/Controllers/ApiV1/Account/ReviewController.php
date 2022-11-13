<?php

namespace App\Http\Controllers\ApiV1\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiV1\Account\ReviewReplyStoreRequest;
use App\Http\Requests\ApiV1\Account\ReviewStoreRequest;
use App\Http\Resources\ApiV1\ReviewResource;
use App\Services\ReviewService;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct(
        private ReviewService $reviewService
    )
    {
    }

    /**
     * Добавляет новый отзыв
     *
     * @param ReviewStoreRequest $request
     * @return ReviewResource
     */
    public function store(ReviewStoreRequest $request): ReviewResource
    {
        return ReviewResource::make(
            $this->reviewService->add($request->validated())
        );
    }

    /**
     * Отвечает на отзыв
     *
     * @param string $reviewId
     * @param ReviewReplyStoreRequest $request
     * @return ReviewResource
     */
    public function reply(string $reviewId, ReviewReplyStoreRequest $request): ReviewResource
    {
        return ReviewResource::make(
            $this->reviewService->reply($reviewId, $request->validated())
        );
    }
}
