<?php

namespace App\Http\Middleware\Api\V1;

use Closure;
use Illuminate\Http\Request;

class FormatPageNumberField
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('page')) {
            $pageNumber = $request->get('page', null);

            if (empty($pageNumber) || !is_numeric($pageNumber) || (int) $pageNumber != $pageNumber) {
                $pageNumber = 1;
            }

            $request->page = abs((int) $pageNumber);
        }

        return $next($request);
    }
}
