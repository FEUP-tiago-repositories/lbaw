<?php

namespace App\Policies;

use App\Models\Response;
use App\Models\Review;
use App\Models\User;

class ResponsePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Response $response): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // only business owners can create a response
        return $user->businessOwner !== null;
    }

    public function createForReview(User $user, Review $review)
    {
        if (!$user->businessOwner) {
            return false;
        }

        // review must not already have a response
        if ($review->response) {
            return false;
        }

        // must be the owner of the space
        if ($review->booking->space->owner_id !== $user->businessOwner->id) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Response $response): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Response $response): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Response $response): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Response $response): bool
    {
        return false;
    }
}
