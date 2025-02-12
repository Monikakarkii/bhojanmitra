<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Table; // Assuming your model is named Table
use Illuminate\Support\Facades\Session;

class User
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        $tableNumber = $request->route('tableNumber'); // Assuming route parameter is 'tableNumber'
        $token = $request->query('token');

        // Check if the session is already set
        if (session()->has('user_table') && session()->has('user_table_token') && session()->has('user_table_expires_at')) {
            $expiresAt = session('user_table_expires_at');

            // Check if the session has expired
            if (now()->greaterThan($expiresAt)) {
                session()->forget(['user_table', 'user_table_token', 'user_table_expires_at']); // Clear expired session
                return redirect()->route('home')->with('error', 'Session expired. Please scan the QR code again.');
            }
        } else {
            // Verify table and token from the database
            $table = Table::where('table_number', $tableNumber)
                ->where('token', $token)
                ->first();
           if($table){


           }

            if (!$table) {
                return redirect()->route('home')->with('error', 'Invalid table or token.');
            }

            // Set session for 2-3 hours (expiration time), including token
            session([
                'user_table' => $table->id,
                'user_table_token' => $table->token, // Add token to session
                'user_table_expires_at' => now()->addHours(2), // Set expiration time
            ]);
        }

        return $next($request);
    }
}
