<?php

namespace App\Helpers;

/**
 * Protection of the public forms (contact, registration) against the robots:
 * - a hidden field ("website") that only robots fill in,
 * - a limit of submissions by IP address.
 */
class FormGuard
{
    public const HONEYPOT = 'website';

    /**
     * @return string|null null if the submission can be handled, "spam" for a robot (answer as if it worked),
     *                     otherwise the error message to show
     */
    public static function check(string $form, int $maxPerHour = 5): ?string
    {
        $request = service('request');
        if (trim((string) $request->getPost(self::HONEYPOT)) !== '') {
            log_message('notice', 'Formulaire « {form} » : envoi d\'un robot ignoré (champ caché rempli)', ['form' => $form]);
            return 'spam';
        }

        $key = 'form-' . $form . '-' . md5((string) $request->getIPAddress());
        if (!service('throttler')->check($key, $maxPerHour, HOUR)) {
            log_message('notice', 'Formulaire « {form} » : trop d\'envois depuis la même adresse', ['form' => $form]);
            return 'Trop d\'envois depuis votre connexion : réessayez dans une heure ou contactez-nous par e-mail.';
        }

        return null;
    }

    /**
     * Hidden field to put in the form (invisible for the visitors and the screen readers).
     */
    public static function field(): string
    {
        // Inline style: this file is not read by Tailwind (app/Views only)
        return '<div style="position:absolute;left:-10000px;width:1px;height:1px;overflow:hidden" aria-hidden="true"><label>Ne pas remplir <input type="text" name="' . self::HONEYPOT . '" tabindex="-1" autocomplete="off"></label></div>';
    }
}
