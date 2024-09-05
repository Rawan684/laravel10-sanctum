<?

namespace App\Support\Upload;

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class CustomFileNamingStrategy
{
    public function handle(UploadedFile $file, string $directory)
    {
        $fileName = $this->generateFileName($file);
        return $file->storeAs($directory, $fileName);
    }

    protected function generateFileName(UploadedFile $file)
    {
        $originalFileName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();

        // Set your own file name logic here
        // For example, you can use a combination of the user's name and a timestamp
        $newFileName = Str::slug(auth()->user()->name) . '_' . now()->timestamp . '.' . $extension;

        return $newFileName;
    }
}
