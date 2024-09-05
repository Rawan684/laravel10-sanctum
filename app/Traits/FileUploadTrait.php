<?

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

trait FileUploadTrait
{
    public function uploadFile(UploadedFile $file, $directory, $filename = null)
    {
        $filename = $filename ?? $this->generateFileName($file);
        $file->move(public_path($directory), $filename);
        return $directory . '/' . $filename;
    }

    public function deleteFile($filePath)
    {
        if (file_exists(public_path($filePath))) {
            unlink(public_path($filePath));
        }
    }

    protected function generateFileName(UploadedFile $file)
    {
        $timestamp = time();
        $extension = $file->getClientOriginalExtension();
        $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        return "{$filename}_{$timestamp}.{$extension}";
    }
}
