<?php

declare(strict_types=1);

use HopsWeb\Http\Controllers\Admin\HopController;
use HopsWeb\Http\Controllers\Admin\UserController;
use HopsWeb\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get("/", fn() => view("welcome"));

Route::get("/dashboard", fn() => view("dashboard"))->middleware(["auth", "verified"])->name("dashboard");

Route::middleware(["auth"])->group(function (): void {
    Route::get("/profile", [ProfileController::class, "edit"])->name("profile.edit");
    Route::patch("/profile", [ProfileController::class, "update"])->name("profile.update");
    Route::delete("/profile", [ProfileController::class, "destroy"])->name("profile.destroy");

    Route::prefix("admin")->name("admin.")->group(function (): void {
        Route::get("/users", [UserController::class, "index"])->name("users.index");
        Route::get("/users/{user}/edit", [UserController::class, "edit"])->name("users.edit");
        Route::put("/users/{user}", [UserController::class, "update"])->name("users.update");
        Route::get("/users/create", [UserController::class, "create"])->name("users.create");
        Route::post("/users", [UserController::class, "store"])->name("users.store");
        Route::delete("/users/{user}", [UserController::class, "destroy"])->name("users.destroy");

        Route::get("/hops", [HopController::class, "index"])->name("hops.index");
        Route::get("/hops/{hop}/edit", [HopController::class, "edit"])->name("hops.edit");
        Route::put("/hops/{hop}", [HopController::class, "update"])->name("hops.update");
        Route::get("/hops/create", [HopController::class, "create"])->name("hops.create");
        Route::post("/hops", [HopController::class, "store"])->name("hops.store");
        Route::delete("/hops/{hop}", [HopController::class, "destroy"])->name("hops.destroy");
    });
});
