<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\User;

class BookingPolicy
{
    /**
     * Check if user is a customer
     */
    public function before(User $user): ?bool
    {
        // Em ambiente local, permitir tudo
        if (app()->environment('local')) {
            return true;
        }

        // Verificar se é customer válido
        if (!Customer::where('user_id', $user->id)->exists() || $user->is_banned || $user->is_deleted) {
            return false;
        }

        return null; // Continue para outras verificações
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Booking $booking): bool
    {
        $customer = Customer::where('user_id', $user->id)->first();
        return $customer && $booking->customer_id === $customer->id;
    }

    public function create(User $user): bool
    {
        return true; // Already checked in before()
    }

    public function update(User $user, Booking $booking): bool
    {
        $customer = Customer::where('user_id', $user->id)->first();
        return $customer
            && $booking->customer_id === $customer->id
            && !$booking->is_cancelled
            && $booking->isFuture();
    }

    public function cancel(User $user, Booking $booking): bool
    {
        return $this->update($user, $booking); // Same logic
    }
}
