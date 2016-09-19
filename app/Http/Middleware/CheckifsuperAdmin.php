<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;


class CheckifsuperAdmin {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{

			if (Session::get('user')[0]['admin_type']==1){
				return $next($request);
			}

		return redirect('/backend/realstate');


	}

}
