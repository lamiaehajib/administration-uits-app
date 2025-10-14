<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    /**
     * تشغيل النسخ الاحتياطي وتنزيل أحدث ملف ZIP محلياً.
     * @return \Illuminate\Http\Response
     */
    public function downloadBackup()
    {
        // 1. تشغيل أمر النسخ الاحتياطي لإنشاء ملف جديد
        try {
            // يتم استخدام Artisan::call لتشغيل الأمر في الخلفية
            Artisan::call('backup:run', ['--only-db' => true]);
        } catch (\Exception $e) {
            // في حالة فشل إنشاء ملف النسخ الاحتياطي (لأي سبب: مثل عدم وجود mysqldump)
            \Log::error("Backup failed during download attempt: " . $e->getMessage());
            return back()->with('error', 'Backup failed during download attempt:Logs.');
        }

        // 2. إيجاد أحدث ملف Backup من القرص المحلي
        $backupDisk = Storage::disk('local');
        
        // **التعديل الحاسم:** البحث في جذر مجلد 'storage/app/'
        // يتم استخدام '' لتمثيل الجذر (Root) بدلاً من اسم مجلد فرعي غير موجود
        $files = $backupDisk->files(''); 
        
        // تصفية الملفات وإزالة مجلدات النظام
        $backupFiles = array_filter($files, function ($file) {
            return str_ends_with($file, '.zip') && !str_contains($file, 'backup-temp');
        });

        // ترتيب الملفات حسب التوقيت الزمني (الأحدث أولاً)
        usort($backupFiles, function ($a, $b) use ($backupDisk) {
            return $backupDisk->lastModified($b) <=> $backupDisk->lastModified($a);
        });

        // الحصول على أحدث ملف
        $latestBackup = $backupFiles[0] ?? null;

        if ($latestBackup) {
            // 3. إرسال الملف للمتصفح لتنزيله
            $fileName = 'backup-' . now()->format('Y-m-d_H-i-s') . '.zip';
            return $backupDisk->download($latestBackup, $fileName);
        }

        // 4. إذا لم يتم العثور على أي ملف، أرسلي رسالة خطأ
        return back()->with('error', 'No backup file found to download.');
    }
}