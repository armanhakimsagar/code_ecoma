<?php

namespace App\Http\Middleware;

use App\Models\Language;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ActiveTemplateMiddleware {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next) {
        $viewShare['activeTemplate']     = activeTemplate();
        $viewShare['activeTemplateTrue'] = activeTemplate(true);
        view()->share($viewShare);

        view()->composer(['Template::partials.header', 'Template::partials.footer'], function ($view) {
            $view->with([
                'languages' => Language::all(),
            ]);
        });

        View::addNamespace('Template',resource_path('views/templates/'.activeTemplateName()));
        return $next($request);
    }
}
