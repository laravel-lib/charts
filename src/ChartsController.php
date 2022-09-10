<?php

namespace LaravelLib\Charts;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class ChartsController extends BaseController
{
    public function __invoke(Request $request, ...$args)
    {
        return new JsonResponse($args[0]->handler($request)->toObject());
    }
}
