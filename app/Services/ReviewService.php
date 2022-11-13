<?php

namespace App\Services;

use App\Jobs\RecalculateUserReviewCounts;
use App\Models\Review;
use Illuminate\Support\Arr;

class ReviewService
{
    public function __construct(
        private RouteService $routeService
    )
    {
    }

    /**
     * Добавляет новый отзыв к поездке
     *
     * @param array $data
     * @return Review
     */
    public function add(array $data): Review
    {
        $isTravelEnd = $this->routeService->isTravelEnd($data['route_id'], $data['user_id']);

        if (!$isTravelEnd) {
            abort(403, 'Поездка еще не закончилась.');
        }

        $review = Review::create(['sender_id' => auth()->id()] + $data);

        RecalculateUserReviewCounts::dispatch($data['user_id']);

        return $review;
    }

    /**
     * Отвечает на отзыв
     *
     * @param string $reviewId
     * @param array $data
     * @return Review
     */
    public function reply(string $reviewId, array $data): Review
    {
        $review = Review::whereUserId(Arr::get($data, 'user_id', auth()->id()))
            ->findOrFail($reviewId);

        $review->update(['reply' => $data['reply']]);

        return $review;
    }
}
