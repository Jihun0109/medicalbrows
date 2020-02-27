<?php

namespace Illuminate\Auth\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use Log;
class ResetPassword extends Notification
{
    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * The callback that should be used to build the mail message.
     *
     * @var \Closure|null
     */
    public static $toMailCallback;

    /**
     * Create a notification instance.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        Log::info($notifiable);
        return (new MailMessage)
            //->subject(Lang::get('Reset Password Notification'))
            ->subject("【Medicalbrows予約システム】パスワードリセット通知")
            ->greeting($notifiable->name . '様')
            //->line(Lang::get('You are receiving this email because we received a password reset request for your account.'))
            ->line('ご利用の メールまたはID に対して、パスワードリセットのリクエストが先ほど行われました。続けるには以下の[パスワード再設定]ボタンをクリックしてください。')
            //->action(Lang::get('Reset Password'), url(config('app.url').route('password.reset', ['token' => $this->token, 'email' => $notifiable->getEmailForPasswordReset()], false)))
            ->action("パスワードのリセット", url(config('app.url').route('password.reset', ['token' => $this->token, 'email' => $notifiable->getEmailForPasswordReset()], false)))
            //->line(Lang::get('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
            ->line("尚、本パスワード再設定の有効時間は" . config('auth.passwords.'.config('auth.defaults.passwords').'.expire') . "分となります。")
            //->line(Lang::get('If you did not request a password reset, no further action is required.'));
            ->line("また、パスワードの再設定をしない場合は、特に何もする必要はございません。宜しくお願い致します。");
    }

    /**
     * Set a callback that should be used when building the notification mail message.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public static function toMailUsing($callback)
    {
        static::$toMailCallback = $callback;
    }
}
