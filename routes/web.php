<?php

declare(strict_types=1);

use HopsWeb\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get("/", fn() => view("welcome"));

Route::get("/dashboard", fn() => view("dashboard"))->middleware(["auth", "verified"])->name("dashboard");

Route::middleware("auth")->group(function (): void {
    Route::get("/profile", [ProfileController::class, "edit"])->name("profile.edit");
    Route::patch("/profile", [ProfileController::class, "update"])->name("profile.update");
    Route::delete("/profile", [ProfileController::class, "destroy"])->name("profile.destroy");
});

require __DIR__ . "/auth.php";
