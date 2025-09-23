<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function downloadBackup()
    {
        // 1. تشغيل أمر النسخ الاحتياطي
        try {
            Artisan::call('backup:run', ['--only-db' => true]);
        } catch (\Exception $e) {
            \Log::error("Backup failed during download attempt: " . $e->getMessage());
        }

        // 2. إيجاد أحدث ملف Backup
        $backupDisk = Storage::disk('local');
        
        // ** التعديل الحاسم: ابحثي في الجذر مباشرة أو في المجلد الصحيح **
        // بما أن الملفات موجودة في الجذر، سنقوم بالبحث في الجذر.
        // إذا كنتِ تريدين البحث في مجلد معين، غيّري 'Backups' إلى اسم المجلد الصحيح
        $files = $backupDisk->files('Backups'); 
        
        // إيجاد أحدث ملف من المصفوفة
        $latestBackup = end($files);

        if ($latestBackup) {
            // 3. إرسال الملف للمتصفح لتنزيله
            $fileName = 'backup-' . now()->format('Y-m-d') . '.zip';
            return $backupDisk->download($latestBackup, $fileName);
        }

        // 4. إذا لم يتم العثور على أي ملف، أرسلي رسالة خطأ
        return back()->with('error', 'No backup file found to download.');
    }
}