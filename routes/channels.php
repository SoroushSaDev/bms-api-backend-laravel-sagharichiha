<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('register.{registerId}', function () {
    return true;
});
