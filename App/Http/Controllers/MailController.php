<?php

namespace App\Http\Controllers;

use App\Mail\SendNotifMail;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class MailController extends Controller
{
    public function sendEmail() {
    try {
        $toEmailAddress = "jenper0807@gmail.com";
        $mailMessage = "Hey sample message.";
        Mail::to($toEmailAddress)->send(new SendNotifMail($mailMessage));
    }
    catch (Exception $e) {
        // Log::error("Unable to send email" . $e->getMessage());
    }
    }
}
