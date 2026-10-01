<?php

namespace App\Policies;

use App\Models\BlogCategory;
use App\Models\User;

class BlogCategoryPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, BlogCategory $category): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return (bool) $user->is_admin;
    }

    public function update(User $user, BlogCategory $category): bool
    {
        return (bool) $user->is_admin;
    }

    public function delete(User $user, BlogCategory $category): bool
    {
        return (bool) $user->is_admin;
    }

    public function restore(User $user): bool
    {
        return (bool) $user->is_admin;
    }

    public function forceDelete(User $user, BlogCategory $category): bool
    {
        return (bool) $user->is_admin;
    }
}
