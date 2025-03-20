<?php

/**
 * Fonction pour envoyer un email
 *
 * @param string $to L'adresse email du destinataire
 * @param string $subject Le sujet de l'email
 * @param string $message Le contenu de l'email
 * @param string $from L'adresse email de l'expéditeur (optionnel)
 * @return bool Retourne true si l'email a été envoyé avec succès, sinon false
 */
function sendEmail($to, $subject, $message, $from = 'no-reply@example.com') {
    try {
        // En-têtes de l'email
        $headers = [
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=utf-8',
            'From: ' . $from,
            'Reply-To: ' . $from,
            'X-Mailer: PHP/' . phpversion()
        ];

        // Envoyer l'email
        if (mail($to, $subject, $message, implode("\r\n", $headers))) {
            return true; // Succès
        } else {
            return false; // Échec
        }
    } catch (Exception $e) {
        // Log l'erreur (facultatif)
        error_log("Erreur lors de l'envoi de l'email : " . $e->getMessage());
        return false; // Échec
    }
}

// Exemple d'utilisation de la fonction
// sendEmail('destinataire@example.com', 'Sujet de l\'email', '<h1>Bonjour</h1><p>Ceci est un test.</p>');