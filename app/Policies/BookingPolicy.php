<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;
use App\Models\Customer;

class BookingPolicy
{
    public function viewAny(User $user): bool
    {
        return Customer::where('user_id', $user->id)->exists();
    }

    public function view(User $user, Booking $booking): bool
    {
        $customer = Customer::where('user_id', $user->id)->first();
        return $customer && $booking->customer_id === $customer->id;
    }

    public function create(User $user): bool
    {
        return Customer::where('user_id', $user->id)->exists()
            && !$user->is_banned
            && !$user->is_deleted;
    }

    public function update(User $user, Booking $booking): bool
    {
        $customer = Customer::where('user_id', $user->id)->first();

        return $customer
            && $booking->customer_id === $customer->id
            && !$booking->is_cancelled
            && $booking->isFuture()
            && !$user->is_banned
            && !$user->is_deleted;
    }

    public function cancel(User $user, Booking $booking): bool
    {
        $customer = Customer::where('user_id', $user->id)->first();

        return $customer
            && $booking->customer_id === $customer->id
            && !$booking->is_cancelled
            && $booking->isFuture()
            && !$user->is_banned
            && !$user->is_deleted;
    }
}
