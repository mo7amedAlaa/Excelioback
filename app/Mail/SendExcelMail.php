<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendExcelMail extends Mailable
{
    use Queueable, SerializesModels;

    public $group;
    public $filePath;

    public function __construct($group, $filePath)
    {
        $this->group = $group;
        $this->filePath = $filePath;
    }

    public function build()
    {
        return $this->subject('ملف Excel خاص بالمجموعة: ' . $this->group)
            ->attachFromStorageDisk('local', $this->filePath) // استخدام stream بدلاً من التحميل
            ->view('raw-text');
    }
}
