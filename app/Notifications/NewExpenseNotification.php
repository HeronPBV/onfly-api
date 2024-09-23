<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Expense;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewExpenseNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected User $user, protected Expense $expense)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Onfly API - Nova Despesa Cadastrada')
                    ->greeting('Olá, ' . $this->user->name . "!")
                    ->line('Uma nova despesa foi cadastrada com sucesso em seu nome: ')
                    ->line($this->user->name . " - " . $this->user->email)
                    ->line('Descrição: ' . $this->expense->description)
                    ->line('Valor: ' . $this->expense->value)
                    ->line('Data da despesa: ' . Carbon::parse($this->expense->date)->format('d/m/Y'))
                    ->salutation('Se você não reconhece essa operação, entre em contato com o nosso suporte.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
