<?php

namespace App\Http\Controllers\Admin\ImportExport;

use App\Http\Controllers\Controller;
use App\Imports\UsersImport;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    /**
     * صفحة الاستيراد
     */
    public function index()
    {
        return view('admin.import.index');
    }

    /**
     * استيراد المستخدمين
     */
    public function users(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // 10MB
        ], [
            'file.required' => 'يجب اختيار ملف',
            'file.mimes' => 'صيغة الملف غير مدعومة (xlsx, xls, csv)',
            'file.max' => 'حجم الملف يجب أن يكون أقل من 10MB',
        ]);

        try {
            // تنفيذ الاستيراد
            $import = new UsersImport();
            Excel::import($import, $request->file('file'));

            $results = $import->results;

            // تسجيل في ملف Log
            $this->writeLog($results, 'users');

            // إرسال إشعار للمشرف
            $this->notifyAdmin($results, 'المستخدمين');

            // حفظ النتائج في الجلسة لعرضها
            session()->flash('import_results', $results);
            session()->flash('import_type', 'المستخدمين');

            return redirect()
                ->route('admin.import.result')
                ->with('success', 'تمت عملية الاستيراد');

        } catch (\Throwable $e) {
            Log::error('Import failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'فشل الاستيراد: ' . $e->getMessage());
        }
    }

    /**
     * صفحة عرض النتائج
     */
    public function result()
    {
        $results = session('import_results');
        $type = session('import_type', 'المستخدمين');

        if (!$results) {
            return redirect()
                ->route('admin.import.index')
                ->with('error', 'لا توجد نتائج للعرض');
        }

        return view('admin.import.result', compact('results', 'type'));
    }

    /**
     * تحميل قالب Excel للاستيراد
     */
    public function template($type = 'users')
    {
        $filename = match ($type) {
            'users' => 'users_template.xlsx',
            'products' => 'products_template.xlsx',
            default => 'template.xlsx',
        };

        // TODO: لاحقاً — تصدير قالب فعلي
        // الآن نستخدم Export فارغ مع الهيدر فقط

        return back()->with('info', 'سيتم إضافة هذه الميزة قريباً');
    }

    /**
     * كتابة ملف Log
     */
    protected function writeLog(array $results, string $type): void
    {
        $logData = [
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'type' => $type,
            'total' => $results['total'],
            'success' => $results['success'],
            'failed' => $results['failed'],
            'errors' => $results['errors'] ?? [],
            'admin_id' => auth()->id(),
            'admin_email' => auth()->user()->email,
        ];

        // سجل في Laravel Log
        Log::info("Excel Import — {$type}", $logData);

        // احفظ نسخة في ملف منفصل
        $filename = 'imports_' . $type . '_' . now()->format('Y_m_d_His') . '.log';
        $path = storage_path('logs/' . $filename);

        file_put_contents(
            $path,
            json_encode($logData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        );
    }

    /**
     * إشعار المشرف
     */
    protected function notifyAdmin(array $results, string $type): void
    {
        try {
            $title = "📥 استيراد {$type}";
            $body = "تم استيراد {$results['success']} من {$results['total']} صف بنجاح";

            if ($results['failed'] > 0) {
                $body .= " • {$results['failed']} فشل";
            }

            NotificationService::send(
                user: auth()->user(),
                type: 'import',
                title: $title,
                body: $body,
                actionUrl: route('admin.import.result')
            );
        } catch (\Throwable $e) {
            Log::error('Import notification failed: ' . $e->getMessage());
        }
    }
}