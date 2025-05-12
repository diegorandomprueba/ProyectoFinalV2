<?php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:newsletters,email'
        ]);
        
        $newsletter = new Newsletter();
        $newsletter->email = $request->email;
        $newsletter->status = 'active';
        $newsletter->save();
        
        return redirect()->back()->with('success', '¡Gracias por suscribirte a nuestra newsletter!');
    }
    
    public function unsubscribe($token)
    {
        $newsletter = Newsletter::where('token', $token)->first();
        
        if (!$newsletter) {
            return redirect()->route('home')->with('error', 'El enlace para darse de baja no es válido');
        }
        
        $newsletter->status = 'inactive';
        $newsletter->save();
        
        return redirect()->route('home')->with('success', 'Te has dado de baja correctamente');
    }
}