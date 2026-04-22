<?php

namespace App\Http\Controllers;

use App\Services\TrelloService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class TrelloController extends Controller
{
    public function cards(TrelloService $trelloService): JsonResponse
    {
        $cards = $trelloService->getBoardCards();

        return response()->json([
            'status' => 'ok',
            'data' => $cards,
        ]);
    }

    /**
     * Example Blade usage endpoint (optional page for local dev).
     */
    public function cardsView(TrelloService $trelloService): View
    {
        return view('trello.cards', [
            'cards' => $trelloService->getBoardCards(),
        ]);
    }
}
