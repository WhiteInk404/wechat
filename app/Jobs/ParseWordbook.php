<?php

namespace App\Jobs;

use App\Entities\Wordbook;
use App\Entities\WordbookContent;
use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Storage;

class ParseWordbook implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    private $wordbook_name;
    private $path;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($wordbook_name, $path)
    {
        $this->wordbook_name = $wordbook_name;
        $this->path          = $path;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $storage           = Storage::getDriver();
        $wordbook_resource = $storage->readStream($this->path);

        try {
            DB::beginTransaction();
            $wordbook = Wordbook::create(['name' => $this->wordbook_name, 'sort' => 99]);

            $words_arr = [];
            while ($row = fgets($wordbook_resource)) {
                preg_match('/(\w+)(.*)/', $row, $matches);
                if (count($matches) != 3) {
                    continue;
                }

                $words_arr[] = new WordbookContent([
                    'facade' => $matches[1],
                    'back'   => trim($matches[2]),
                ]);

                if (count($words_arr) === 1000) {
                    $wordbook->contents()->saveMany($words_arr);
                    unset($words_arr);
                }
            }

            if (count($words_arr) > 0) {
                $wordbook->contents()->saveMany($words_arr);
                unset($words_arr);
            }

            DB::commit();
            \Log::info('parse wordbook success', ['wordbook' => $wordbook]);
            $storage->delete($this->path);
        } catch (\Exception $exception) {
            DB::rollBack();
            \Log::info('parse wordbook error.', ['file_path' => $this->path, 'exception' => $exception]);
        }
    }
}
