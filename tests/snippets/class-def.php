<?php

namespace App\Commands;

use Vendor\Package\Thing;
use Vendor\Package\Contracts\BigContract;
use Vendor\Package\Support\Contracts\SmallContract;
use App\Models\User;

class MyCommand extends Thing implements BigContract, SmallContract {
    public function render(array $params, User $user) {
