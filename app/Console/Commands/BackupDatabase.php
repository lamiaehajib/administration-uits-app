<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use File;

// يمكنكِ إزالة تعليق الأسطر المتعلقة بـ PHPMailer إذا كنتِ تريدين إرسال إيميل 
//use PHPMailer\PHPMailer\PHPMailer; 
//use PHPMailer\PHPMailer\Exception;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup'; 

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup for database'; 

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //------------------------------------ INFORMATIONS DATABASES ------------------------------------
        $database = config('database.connections.mysql.database'); 
        $host = config('database.connections.mysql.host');
        $username = config('database.connections.mysql.username'); 
        $password = config('database.connections.mysql.password'); 
        $dump = config('database.connections.mysql.dump');

        //------------------------------------ NAME FOR BACKUP ------------------------------------
       $date=now()->format('Y-m-d_H-i-s'); 
        $fileName = "backup_{$database}_{$date}.sql"; 
        $storagePath=storage_path("app/public/backups/{$fileName}");

        //------------------------------------ EXECUTE COMMAND FOR BACKUP ------------------------------------
        // هذا هو الأمر الذي يشغل 'mysqldump' لعمل الباك اب 
        // ** تم تعديل هذا السطر لوضع علامات اقتباس حول متغير $dump **
       $command = sprintf(
    '%s -h%s -u%s -p%s %s > "%s"',
    escapeshellarg($dump),
    escapeshellarg($host),
    escapeshellarg($username),
    escapeshellarg($password),
    escapeshellarg($database),
    $storagePath
);


        $result = null;
        $output = null;
        exec($command, $output, $result);

        if($result !== 0){
            $this->error('Database backup failed.'); 
            return Command::FAILURE; 
        }else {
            $this->info("Database backup ({$fileName}) created with success."); 
            $this->info("Upload database backup in google drive..."); 

            // هذه الأسطر خاصة بالإيميل ومُعلّقة (لا تعمل إلا إذا أزلتِ التعليق وجهزتِ PHPMailer) 
            $this->info("Email with attachement sending..."); 
            try {
                // $mail = new PHPMailer(true); 
                // $mail->isSMTP(); 
                // $mail->CharSet = 'utf-8'; 
                // $mail->Host = ''; 
                // $mail->SMTPAuth = true; 
                // $mail->Username = ''; 
                // $mail->Password = ''; 
                // $mail->SMTPSecure = 'ssl'; 
                // $mail->Port = '465'; 
                // $mail->setFrom('', ''); 
                // $mail->Subject = 'Backup reçu avec succès'; 
                // $mail->addAddress('mohamed.nabil@etud.iga.ac.ma'); 
                // $mail->isHTML(true); 
                // $htmlContent = view('Mail/mailBackupDatabase')->render(); 
                // $mail->Body = $htmlContent; 
                // $mail->addAttachment($storagePath, $fileName); 
                // if (!$mail->send()) { 
                //     return back()->with("error", "Email non envoyé : " . $mail->ErrorInfo); 
                // }else{ 
                //     $this->info("Email send with success."); 
                // } 

                // هذا الجزء هو المسؤول عن رفع الملف إلى Google Drive 
                if(Storage::disk('google')->put($fileName, File::get($storagePath))){ 
                    $this->info("Upload database to google drive with success."); 
                }
            }catch(\Exception $e){
                // إذا حدث خطأ في محاولة الإرسال أو الرفع 
                redirect('/error'); 
            }

            return Command::SUCCESS; 
        }
    }
}