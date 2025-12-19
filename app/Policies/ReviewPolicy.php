<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\User;

class ReviewPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Review $review): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // only customers can create reviews
        return $user->customer !== null;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Review $review): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Review $review): bool
    {
        // review author or admin can delete
        return ($user->customer && $user->customer->id === $review->customer_id) 
            || $user->is_admin;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Review $review): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Review $review): bool
    {
        return $user->is_admin;
    }

    public function createForBooking(User $user, $bookingId): bool
    {
        // must be a customer
        if (! $user->customer) {
            return false;
        }

        $booking = \App\Models\Booking::find($bookingId);
        if (! $booking) {
            return false;
        }

        // must own the booking
        if ($booking->customer_id !== $user->customer->id) {
            return false;
        }

        // booking must not be cancelled
        if ($booking->is_cancelled) {
            return false;
        }

        // must not already have a review
        if ($booking->review) {
            return false;
        }

        return true;
    }
}
