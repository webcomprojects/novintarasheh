<?php

namespace App\Listeners\User;

use App\Models\Discount;
use App\Models\Referral;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateReferralDiscounts
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {

        $user  = $event->user;
        $owner = $user->referral;

        if ($owner) {

            $discountType=option('user_refrral_gift_discount_type','percent');

            $owner_discount_id=null;
            if (option('user_refrral_gift_type')=="discount_code"){

                $owner_code = random_code();
                $owner_discount = Discount::create([
                    'title'       => "تخفیف کد معرف",
                    'code'        => $owner_code,
                    'type'        => $discountType,
                    'amount'      =>  option('owner_refrral_amount', 0),
                    'description' => "کد تخفیف برای معرفی کاربر $user->fullname",
                    'quantity'    => 1,
                    'start_date'  => now(),
                    'end_date'    => now()->addDays(90)
                ]);
                $owner_discount->users()->attach([$owner->id]);
                $owner_discount_id=$owner_discount->id;

            }



            $user_code = random_code();

            $user_discount = Discount::create([
                'title'       => "تخفیف کد معرف",
                'code'        => $user_code,
                'type'        => $discountType,
                'amount'      => option('user_refrral_amount', 0),
                'description' => "کد تخفیف برای ثبت کد معرف",
                'quantity'    => 1,
                'start_date'  => now(),
                'end_date'    => now()->addDays(90)
            ]);

            $user_discount->users()->attach([$user->id]);

            Referral::create([
                'owner_discount_id' => $owner_discount_id,
                'user_discount_id'  => $user_discount->id,
                'owner_id'          => $owner->id,
                'user_id'           =>  $user->id
            ]);
        }
    }
}
