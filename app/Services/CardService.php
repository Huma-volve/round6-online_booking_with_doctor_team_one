<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Customer;
use Stripe\PaymentMethod;
use App\Models\User;
use App\Repositories\Interfaces\UserPaymentMethodRepositoryInterface;

class CardService
{
    protected $repository;

    public function __construct(UserPaymentMethodRepositoryInterface $repository)
    {
        $this->repository = $repository;
        Stripe::setApiKey(config('services.stripe.STRIPE_SECRET_KEY'));
    }

    /**
     * Add a new card for the user.
     */
    public function addCard(User $user, string $paymentMethodId, ?bool $isDefault = false)
    {
        // Step 1: Create Stripe customer if not exists
        if (!$user->stripe_customer_id) {
            $customer = Customer::create([
                'name' => $user->name,
                'email' => $user->email,
            ]);
            $user->update(['stripe_customer_id' => $customer->id]);
        } else {
            $customer = Customer::retrieve($user->stripe_customer_id);
        }

        // Step 2: Attach payment method to customer
        $paymentMethod = PaymentMethod::retrieve($paymentMethodId);
        $paymentMethod->attach(['customer' => $customer->id]);

        // Step 3: Save to database
        return $this->repository->create([
            'user_id'          => $user->id,
            'payment_method_id' => $paymentMethod->id,
            'brand'            => $paymentMethod->card->brand,
            'last4'            => $paymentMethod->card->last4,
            'exp_month'        => $paymentMethod->card->exp_month,
            'exp_year'         => $paymentMethod->card->exp_year,
            'is_default'       => $isDefault ?? false, // default is false
        ]);
    }

    /**
     * List all user cards.
     */
    public function listCards(User $user)
    {
        return $this->repository->getUserCards($user);
    }

    /**
     * Set default card for user.
     */
    public function setDefault(User $user, int $cardId)
    {
        return $this->repository->setDefaultCard($user, $cardId);
    }

    /**
     * Delete a card.
     */
    public function deleteCard(User $user, int $cardId)
    {
        return $this->repository->delete($user, $cardId);
    }
}
