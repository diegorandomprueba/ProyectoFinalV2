<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactForm;

class ContactController extends Controller
{
    public function show()
    {
        return view('pages.contact');
    }
    
    public function send(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'product_id' => 'nullable|exists:productes,id'
        ]);
        
        // Guardar el mensaje de contacto
        $contact = new Contact();
        $contact->name = $validated['name'];
        $contact->email = $validated['email'];
        $contact->subject = $validated['subject'];
        $contact->message = $validated['message'];
        $contact->product_id = $validated['product_id'] ?? null;
        $contact->save();
        
        // Enviar email de notificación
        Mail::to(config('mail.from.address'))->send(new ContactForm($contact));
        
        return redirect()->back()->with('success', 'Tu mensaje ha sido enviado correctamente. Nos pondremos en contacto contigo pronto.');
    }
}