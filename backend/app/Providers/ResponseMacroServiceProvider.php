<?php


namespace App\Providers;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class ResponseMacroServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Response::macro('success', function ($data, $status = 200, array $headers = []) {
            if ($data instanceof Builder || $data instanceof \Illuminate\Database\Query\Builder) {
                if (request()->query('paginated') === 'true') {
                    return Response::json($data->paginate(request()->query('per_page', 15)), $status)->withHeaders($headers);
                }
                return Response::json($data->get(), $status)->withHeaders($headers);
            }
            return Response::json($data, $status);
        });

        Response::macro('failure', function ($message, $status = 400, array $headers = []) {
            return Response::json([
                'error' => __($message),
            ], $status)->withHeaders($headers);
        });
    }
}
