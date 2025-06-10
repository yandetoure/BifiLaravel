<?php declare(strict_types=1);

namespace App\Policies;

use App\Models\Balance;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BalancePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isSupervisor() || $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Balance $balance): bool
    {
        return $user->isSupervisor() || $user->isAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isSupervisor() || $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Balance $balance): bool
    {
        return $user->isSupervisor() || $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Balance $balance): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Balance $balance): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Balance $balance): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can manage balances.
     */
    public function manage(User $user): bool
    {
        return $user->isSupervisor() || $user->isAdmin();
    }

    /**
     * Determine whether the user can initialize balances.
     */
    public function initialize(User $user): bool
    {
        return $user->isSupervisor() || $user->isAdmin();
    }

    /**
     * Determine whether the user can make deposits.
     */
    public function deposit(User $user): bool
    {
        return $user->isSupervisor() || $user->isAdmin();
    }

    /**
     * Determine whether the user can make supervisor deposits.
     */
    public function supervisorDeposit(User $user): bool
    {
        return $user->isSupervisor() || $user->isAdmin();
    }
}
