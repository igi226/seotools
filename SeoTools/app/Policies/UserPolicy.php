<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can use Data Export.
     *
     * @param User $user
     * @return bool
     */
    public function dataExport(User $user)
    {
        return $user->plan->features->data_export;
    }

    /**
     * Determine whether the user can use the API.
     *
     * @param User $user
     * @return bool
     */
    public function api(User $user)
    {
        return $user->plan->features->api;
    }

    /**
     * Determine whether the user can use the Research Tools.
     *
     * @param ?User $user
     * @return bool
     */
    public function researchTools(?User $user)
    {
        return !$user || $user->plan->features->research_tools;
    }

    /**
     * Determine whether the user can use the Developer Tools.
     *
     * @param ?User $user
     * @return bool
     */
    public function developerTools(?User $user)
    {
        return !$user || $user->plan->features->developer_tools;
    }

    /**
     * Determine whether the user can use the Content Tools.
     *
     * @param ?User $user
     * @return bool
     */
    public function contentTools(?User $user)
    {
        return !$user || $user->plan->features->content_tools;
    }

    /**
     * Determine whether the user can brand Reports.
     *
     * @param User $user
     * @return bool
     */
    public function brandReports(User $user)
    {
        return $user->plan->features->branded_reports == 1;
    }

    /**
     * Determine whether the user can white-label Reports.
     *
     * @param User $user
     * @return bool
     */
    public function whiteLabelReports(User $user)
    {
        return $user->plan->features->white_label_reports == 1;
    }
}
