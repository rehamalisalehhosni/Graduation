<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class AdminRedirectifAuth {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if(Session::has('user')){
			return $next($request);
		}
		
		return redirect('backend/admin/login');


	}

}
