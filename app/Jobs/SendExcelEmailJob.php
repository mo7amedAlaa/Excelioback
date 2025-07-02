<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\SendExcelMail;

class SendExcelEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    protected $group;
    protected $filePath;

    public function __construct($email, $group, $filePath)
    {
        $this->email = $email;
        $this->group = $group;
        $this->filePath = $filePath;
    }

    public function handle()
    {
        // إرسال البريد مع المرفق
        Mail::to($this->email)->send(new SendExcelMail($this->group, $this->filePath));

        // حذف الملف بعد الإرسال
        Storage::disk('local')->delete($this->filePath);
    }
}
