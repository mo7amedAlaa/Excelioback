<?php

namespace App\Http\Controllers;

use App\Mail\SendExcelMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Jobs\SendExcelEmailJob;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function send(Request $request)
    {
        set_time_limit(300); // تمديد المهلة للملفات الكبيرة

        $request->validate([
            'email' => 'required|email',
            'file' => 'required|file|mimes:xlsx,xls|max:204800', // حد الحجم 200MB
            'group' => 'nullable|string',
        ]);

        $email = $request->input('email');
        $group = $request->input('group', 'غير معروف');
        $file = $request->file('file');

        $fileName = 'group_' . $group . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('temp', $fileName, 'local'); // تخزين مؤقت

        // إرسال المهمة إلى الكيو
        Mail::to($email)->send(new SendExcelMail($group, $path));


        return response()->json(['message' => 'تم إرسال البريد الإلكتروني في الخلفية 🚀']);
    }
}
