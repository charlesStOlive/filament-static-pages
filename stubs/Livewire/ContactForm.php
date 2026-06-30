<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

class ContactForm extends Component
{
    public string $prenom = '';

    public string $nom = '';

    public string $email = '';

    public string $telephone = '';

    public string $objet = '';

    public string $content = '';

    public bool $success = false;

    public string $successMessage = '';

    protected function rules(): array
    {
        return [
            'prenom' => ['nullable', 'string', 'min:2', 'max:50'],
            'nom' => ['nullable', 'string', 'min:2', 'max:50'],
            'email' => ['required', 'email', 'max:100'],
            'telephone' => ['nullable', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10'],
            'objet' => ['required', 'string', 'min:5', 'max:100'],
            'content' => ['required', 'string', 'min:10', 'max:1000'],
        ];
    }

    protected function messages(): array
    {
        return [
            'prenom.min' => 'Le prénom doit contenir au moins 2 caractères.',
            'prenom.max' => 'Le prénom ne peut pas dépasser 50 caractères.',

            'nom.min' => 'Le nom doit contenir au moins 2 caractères.',
            'nom.max' => 'Le nom ne peut pas dépasser 50 caractères.',

            'email.required' => 'L’email est obligatoire.',
            'email.email' => 'L’email doit être valide.',
            'email.max' => 'L’email ne peut pas dépasser 100 caractères.',

            'telephone.regex' => 'Le numéro de téléphone n’est pas valide.',
            'telephone.min' => 'Le numéro de téléphone doit contenir au moins 10 caractères.',

            'objet.required' => 'L’objet est obligatoire.',
            'objet.min' => 'L’objet doit contenir au moins 5 caractères.',
            'objet.max' => 'L’objet ne peut pas dépasser 100 caractères.',

            'content.required' => 'Le message est obligatoire.',
            'content.min' => 'Le message doit contenir au moins 10 caractères.',
            'content.max' => 'Le message ne peut pas dépasser 1000 caractères.',
        ];
    }

    public function submit(): void
    {
        $this->success = false;
        $this->successMessage = '';

        $key = 'contact-form:' . request()->ip();

        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);

            $this->addError(
                'throttle',
                "Trop de tentatives. Réessayez dans {$seconds} secondes."
            );

            return;
        }

        RateLimiter::hit($key, 60);

        $this->validate();

        try {
            $this->sendContactEmail();

            $this->reset([
                'prenom',
                'nom',
                'email',
                'telephone',
                'objet',
                'content',
            ]);

            $this->success = true;
            $this->successMessage = 'Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.';

            RateLimiter::clear($key);
        } catch (\Throwable $e) {
            report($e);

            $this->addError(
                'send',
                'Une erreur est survenue lors de l’envoi. Veuillez réessayer.'
            );
        }
    }

    protected function sendContactEmail(): void
    {
        $data = [
            'prenom' => $this->prenom,
            'nom' => $this->nom,
            'email' => $this->email,
            'telephone' => $this->telephone,
            'objet' => $this->objet,
            'content' => $this->content,
            'date' => now()->format('d/m/Y H:i'),
        ];

        $recipient = $this->resolveRecipientEmail();

        Mail::send('emails.contact', $data, function ($message) use ($recipient) {
            $senderName = trim($this->prenom . ' ' . $this->nom);

            $message
                ->to($recipient)
                ->subject('Nouveau message de contact : ' . $this->objet)
                ->replyTo($this->email, $senderName ?: null);
        });
    }

    protected function resolveRecipientEmail(): string
    {
        if (function_exists('filament_static_pages_setting')) {
            return filament_static_pages_setting(
                'email',
                config('mail.contact_email', config('mail.from.address'))
            );
        }

        return config('mail.contact_email', config('mail.from.address'));
    }

    public function hideSuccess(): void
    {
        $this->success = false;
        $this->successMessage = '';
    }

    public function render()
    {
        return view('livewire.front.form');
    }
}
