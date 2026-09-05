<?php
declare(strict_types=1);
final class ContactController {
    public static function index(): void {
        $services = Service::published();
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            verify_csrf();
            $old = [];
            foreach(['name','email','phone','service','budget','message'] as $key) {
                $old[$key] = input($key);
            }
            $errors = [];
            if (str_length($old['name']) < 1 || str_length($old['name']) > 100) $errors[] = 'Enter your name.';
            if (!valid_email($old['email'])) $errors[] = 'Enter a valid email address.';
            if (strlen($old['phone']) > 40) $errors[] = 'Enter a valid phone number or leave it blank.';
            $old['service'] = substr($old['service'], 0, 140);
            $old['budget'] = substr($old['budget'], 0, 100);
            if (str_length($old['message']) < 1 || str_length($old['message']) > 5000) $errors[] = 'Write a message.';

            if ($errors) {
                $_SESSION['contact_old'] = array_map(fn($v) => substr($v, 0, 20000), $old);
                $_SESSION['contact_errors'] = $errors;
                redirect(page_url('contact') . '#contact-form');
            }

            Contact::create([
                'name' => $old['name'],
                'email' => $old['email'],
                'phone' => $old['phone'],
                'service' => $old['service'],
                'budget' => $old['budget'],
                'message' => $old['message'],
                'status' => 'new',
            ]);

            unset($_SESSION['contact_old'], $_SESSION['contact_errors']);
            flash('success', 'Thanks for getting in touch! Your message is saved, and our team will review it.');
            redirect(page_url('contact') . '#contact-form');
        }
        $old = $_SESSION['contact_old'] ?? ['service' => query('service')];
        $errors = $_SESSION['contact_errors'] ?? [];
        unset($_SESSION['contact_old'], $_SESSION['contact_errors']);
        public_page('contact/index', compact('services', 'old', 'errors') + ['active' => 'contact', 'title' => 'Contact us', 'canonical' => page_url('contact')]);
    }
}
