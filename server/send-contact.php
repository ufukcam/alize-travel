<?php
require_once __DIR__ . '/../includes/config.php';
// Basic contact form email sender (SMTP destekli)

header('Content-Type: application/json');

// Allow only POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'message' => 'Method not allowed']);
    exit;
}

// Simple rate limit via existing session (config.php already started session)
if (!empty($_SESSION['last_contact_submit']) && (time() - $_SESSION['last_contact_submit'] < 10)) {
    http_response_code(429);
    echo json_encode(['ok' => false, 'message' => 'Lütfen biraz sonra tekrar deneyin.']);
    exit;
}

// Collect and sanitize inputs
$firstName = trim($_POST['firstName'] ?? '');
$lastName  = trim($_POST['lastName'] ?? '');
$email     = trim($_POST['email'] ?? '');
$phone     = trim($_POST['phone'] ?? '');
$travelDate= trim($_POST['travelDate'] ?? '');
$groupSize = trim($_POST['groupSize'] ?? '');
$message   = trim($_POST['message'] ?? '');

// Basic validation
if ($firstName === '' || $lastName === '' || $email === '' || $message === '') {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'Lütfen gerekli alanları doldurun.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'Geçerli bir e‑posta adresi girin.']);
    exit;
}

// Build email
$to = 'memoguide@yahoo.fr';
$subject = 'Yeni İletişim Mesajı - Alize Travel';

$lines = [];
$lines[] = 'İsim: ' . $firstName . ' ' . $lastName;
$lines[] = 'E‑posta: ' . $email;
if ($phone !== '') $lines[] = 'Telefon: ' . $phone;
if ($travelDate !== '') $lines[] = 'Seyahat Tarihi: ' . $travelDate;
if ($groupSize !== '') $lines[] = 'Grup Büyüklüğü: ' . $groupSize;
$lines[] = str_repeat('-', 40);
$lines[] = $message;

$body = implode("\n", $lines);

// SMTP ile gönderim dene; ayarlar yoksa mail() ile devam et
$fromEmail = (SMTP_USER ?: (SMTP_FROM_EMAIL ?: 'no-reply@alizetravel.com'));
$fromName  = SMTP_FROM_NAME ?: 'Alize Travel';

$headers = [];
$headers[] = 'From: ' . $fromName . ' <' . $fromEmail . '>';
$headers[] = 'Reply-To: ' . $email;
$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-Type: text/plain; charset=UTF-8';

// Helper: basit SMTP gönderici
function smtp_send_basic($host, $port, $secure, $user, $pass, $from, $to, $subject, $body, $replyTo = null) {
    $errno = 0; $errstr = '';
    $transport = ($secure === 'ssl') ? 'ssl://' : '';
    $fp = @stream_socket_client($transport . $host . ':' . $port, $errno, $errstr, 15, STREAM_CLIENT_CONNECT);
    if (!$fp) return ['ok' => false, 'error' => 'Bağlantı hatası: ' . $errstr];
    stream_set_timeout($fp, 15);
    $read = function() use ($fp) { return fgets($fp, 515); };
    $write = function($cmd) use ($fp) { fwrite($fp, $cmd . "\r\n"); };

    $banner = $read();
    if (strpos($banner, '220') !== 0) { fclose($fp); return ['ok' => false, 'error' => 'Sunucu yanıtı geçersiz (banner)']; }

    $domain = 'alizetravel.com';
    $write('EHLO ' . $domain);
    $ehlo = '';
    for ($i=0; $i<10; $i++) { $line = $read(); if ($line === false) break; $ehlo .= $line; if (strpos($line, '250 ') === 0) break; }

    if ($secure === 'tls') {
        $write('STARTTLS');
        $resp = $read();
        if (strpos($resp, '220') !== 0) { fclose($fp); return ['ok' => false, 'error' => 'STARTTLS başarısız']; }
        if (!stream_socket_enable_crypto($fp, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) { fclose($fp); return ['ok' => false, 'error' => 'TLS kurulamadı']; }
        // TLS sonrası yeniden EHLO
        $write('EHLO ' . $domain);
        for ($i=0; $i<10; $i++) { $line = $read(); if ($line === false) break; if (strpos($line, '250 ') === 0) break; }
    }

    if ($user && $pass) {
        $write('AUTH LOGIN');
        if (strpos($read(), '334') !== 0) { fclose($fp); return ['ok' => false, 'error' => 'AUTH başlatılamadı']; }
        $write(base64_encode($user));
        if (strpos($read(), '334') !== 0) { fclose($fp); return ['ok' => false, 'error' => 'Kullanıcı reddedildi']; }
        $write(base64_encode($pass));
        if (strpos($read(), '235') !== 0) { fclose($fp); return ['ok' => false, 'error' => 'Şifre reddedildi']; }
    }

    $write('MAIL FROM: <' . $from . '>');
    if (strpos($read(), '250') !== 0) { fclose($fp); return ['ok' => false, 'error' => 'MAIL FROM başarısız']; }
    $write('RCPT TO: <' . $to . '>');
    $rcptResp = $read();
    if (strpos($rcptResp, '250') !== 0 && strpos($rcptResp, '251') !== 0) { fclose($fp); return ['ok' => false, 'error' => 'RCPT TO başarısız']; }
    $write('DATA');
    if (strpos($read(), '354') !== 0) { fclose($fp); return ['ok' => false, 'error' => 'DATA reddedildi']; }

    $headers = [];
    $headers[] = 'From: ' . $from;
    if ($replyTo) $headers[] = 'Reply-To: ' . $replyTo;
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-Type: text/plain; charset=UTF-8';
    $headers[] = 'Date: ' . date('r');
    $headers[] = 'Subject: ' . '=?UTF-8?B?'.base64_encode($subject).'?=';
    $headers[] = 'To: ' . $to;

    $data = implode("\r\n", $headers) . "\r\n\r\n" . $body . "\r\n.\r\n";
    $write($data);
    if (strpos($read(), '250') !== 0) { fclose($fp); return ['ok' => false, 'error' => 'Mesaj kabul edilmedi']; }
    $write('QUIT');
    fclose($fp);
    return ['ok' => true];
}

$useSmtp = SMTP_HOST && SMTP_USER && SMTP_PASS;
$smtpError = '';
if ($useSmtp) {
    $res = smtp_send_basic(SMTP_HOST, (int)SMTP_PORT, SMTP_SECURE, SMTP_USER, SMTP_PASS, $fromEmail, $to, $subject, $body, $email);
    if ($res['ok']) {
        $_SESSION['last_contact_submit'] = time();
        echo json_encode(['ok' => true, 'message' => 'Mesajınız gönderildi. Teşekkürler!']);
        exit;
    }
    $smtpError = $res['error'] ?? 'SMTP bilinmeyen hata';
    // Otomatik alternatif deneme: TLS 587 başarısızsa SSL 465 dene
    if ((int)SMTP_PORT === 587 && strtolower(SMTP_SECURE) === 'tls') {
        $res2 = smtp_send_basic(SMTP_HOST, 465, 'ssl', SMTP_USER, SMTP_PASS, $fromEmail, $to, $subject, $body, $email);
        if ($res2['ok']) {
            $_SESSION['last_contact_submit'] = time();
            echo json_encode(['ok' => true, 'message' => 'Mesajınız gönderildi. Teşekkürler!']);
            exit;
        }
        $smtpError .= ' | Retry(ssl:465): ' . ($res2['error'] ?? 'bilinmiyor');
    }
    // SMTP başarısızsa mail() fallback
}

$sent = @mail($to, '=?UTF-8?B?'.base64_encode($subject).'?=', $body, implode("\r\n", $headers));
if ($sent) {
    $_SESSION['last_contact_submit'] = time();
    echo json_encode(['ok' => true, 'message' => 'Mesajınız gönderildi. Teşekkürler!']);
} else {
    http_response_code(500);
    $detail = $smtpError ? (' | SMTP: ' . $smtpError) : '';
    error_log('[contact] send failed' . $detail);
    echo json_encode(['ok' => false, 'message' => 'Mesaj gönderilemedi (SMTP/mail).' . $detail]);
}


