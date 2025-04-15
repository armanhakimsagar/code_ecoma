<?php

namespace App\Traits;

use App\Constants\Status;

trait SellerNotify
{
    public static function notifyToSeller()
    {
        return [
            'allSellers'              => 'All Sellers',
            'selectedSellers'         => 'Selected Sellers',
            'emptyBalanceSellers'     => 'Empty Balance Sellers',
            'twoFaDisableSellers'     => '2FA Disable Seller',
            'twoFaEnableSellers'      => '2FA Enable Seller',
            'kycUnverified'            => 'KYC Unverified Sellers',
            'hasDepositedSellers'       => 'Deposited Sellers',
            'notDepositedSellers'       => 'Not Deposited Sellers',
            'pendingDepositedSellers'   => 'Pending Deposited Sellers',
            'rejectedDepositedSellers'  => 'Rejected Deposited Sellers',
            'topDepositedSellers'     => 'Top Deposited Sellers',
            'hasWithdrawSellers'      => 'Withdraw Sellers',
            'pendingWithdrawSellers'  => 'Pending Withdraw Sellers',
            'rejectedWithdrawSellers' => 'Rejected Withdraw Sellers',
            'pendingTicketSeller'     => 'Pending Ticket Sellers',
            'answerTicketSeller'      => 'Answer Ticket Sellers',
            'closedTicketSeller'      => 'Closed Ticket Sellers',
            'notLoginSellers'         => 'Last Few Days Not Login Sellers',
        ];
    }

    public function scopeSelectedSellers($query)
    {
        return $query->whereIn('id', request()->seller ?? []);
    }

    public function scopeAllSellers($query)
    {
        return $query;
    }

    public function scopeEmptyBalanceSellers($query)
    {
        return $query->where('balance', '<=', 0);
    }

    public function scopeTwoFaDisableSellers($query)
    {
        return $query->where('ts', Status::DISABLE);
    }

    public function scopeTwoFaEnableSellers($query)
    {
        return $query->where('ts', Status::ENABLE);
    }

    public function scopeHasDepositedSellers($query)
    {
        return $query->whereHas('deposits', function ($deposit) {
            $deposit->successful();
        });
    }

    public function scopeNotDepositedSellers($query)
    {
        return $query->whereDoesntHave('deposits', function ($q) {
            $q->successful();
        });
    }

    public function scopePendingDepositedSellers($query)
    {
        return $query->whereHas('deposits', function ($deposit) {
            $deposit->pending();
        });
    }

    public function scopeRejectedDepositedSellers($query)
    {
        return $query->whereHas('deposits', function ($deposit) {
            $deposit->rejected();
        });
    }

    public function scopeTopDepositedSellers($query)
    {
        return $query->whereHas('deposits', function ($deposit) {
            $deposit->successful();
        })->withSum(['deposits' => function ($q) {
            $q->successful();
        }], 'amount')->orderBy('deposits_sum_amount', 'desc')->take(request()->number_of_top_deposited_user ?? 10);
    }

    public function scopeHasWithdrawSellers($query)
    {
        return $query->whereHas('withdrawals', function ($q) {
            $q->approved();
        });
    }

    public function scopePendingWithdrawSellers($query)
    {
        return $query->whereHas('withdrawals', function ($q) {
            $q->pending();
        });
    }

    public function scopeRejectedWithdrawSellers($query)
    {
        return $query->whereHas('withdrawals', function ($q) {
            $q->rejected();
        });
    }

    public function scopePendingTicketSeller($query)
    {
        return $query->whereHas('tickets', function ($q) {
            $q->whereIn('status', [Status::TICKET_OPEN, Status::TICKET_REPLY]);
        });
    }

    public function scopeClosedTicketSeller($query)
    {
        return $query->whereHas('tickets', function ($q) {
            $q->where('status', Status::TICKET_CLOSE);
        });
    }

    public function scopeAnswerTicketSeller($query)
    {
        return $query->whereHas('tickets', function ($q) {

            $q->where('status', Status::TICKET_ANSWER);
        });
    }

    public function scopeNotLoginSellers($query)
    {
        return $query->whereDoesntHave('loginLogs', function ($q) {
            $q->whereDate('created_at', '>=', now()->subDays(request()->number_of_days ?? 10));
        });
    }
}
