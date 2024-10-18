<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Mail;

use Illuminate\Support\ServiceProvider;

/**
 * Register Mail ServiceProvider.
 *
 * @since 1.1.0
 */
class MailServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Set Return-Path equal to MailFrom field.
        add_action('phpmailer_init', fn ($phpmailer) => $phpmailer->Sender = $phpmailer->From);
    }
}
