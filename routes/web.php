<?php

declare(strict_types=1);

use HopsWeb\Http\Controllers\Admin\HopController as AdminHopController;
use HopsWeb\Http\Controllers\Admin\HopQueryController;
use HopsWeb\Http\Controllers\Admin\UserController;
use HopsWeb\Http\Controllers\AgendaController;
use HopsWeb\Http\Controllers\AgendaRunController;
use HopsWeb\Http\Controllers\ComparisonController;
use HopsWeb\Http\Controllers\HopController as PublicHopController;
use HopsWeb\Http\Controllers\LaboratoryController;
use HopsWeb\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get("/", [PublicHopController::class, "index"])->name("hops.index");
Route::get("/hops/{hop:slug}", [PublicHopController::class, "show"])->name("hops.show");

Route::middleware(["auth"])->group(function (): void {
    Route::get("/comparison", [ComparisonController::class, "index"])->name("comparison.index");
    Route::post("/comparison", [ComparisonController::class, "store"])->name("comparison.store");

    Route::middleware("is_team_member")->prefix("laboratory")->name("laboratory.")->group(function (): void {
        Route::get("/", LaboratoryController::class)->name("index");
        Route::get("/agendas/create", [AgendaController::class, "create"])->name("agendas.create");
        Route::post("/agendas", [AgendaController::class, "store"])->name("agendas.store");
        Route::get("/agendas/{agenda}/runs/create", [AgendaRunController::class, "create"])->name("agendas.runs.create");
        Route::post("/agendas/{agenda}/runs", [AgendaRunController::class, "store"])->name("agendas.runs.store");
    });

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
        Route::get("/hops", [AdminHopController::class, "index"])->name("hops.index");
        Route::get("/hops/{hop}/edit", [AdminHopController::class, "edit"])->name("hops.edit");
        Route::put("/hops/{hop}", [AdminHopController::class, "update"])->name("hops.update");
        Route::get("/hops/create", [AdminHopController::class, "create"])->name("hops.create");
        Route::post("/hops", [AdminHopController::class, "store"])->name("hops.store");
        Route::delete("/hops/{hop}", [AdminHopController::class, "destroy"])->name("hops.destroy");
        Route::get("/hop-queries", [HopQueryController::class, "index"])->name("hop-queries.index");
        Route::get("/hop-queries/{hopQuery}", [HopQueryController::class, "show"])->name("hop-queries.show");
    });
});
