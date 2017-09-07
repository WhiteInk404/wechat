<?php

namespace App\Jobs;

use App\Entities\Wordbook;
use App\Entities\WordbookContent;
use DB;
use Excel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Collections\CellCollection;
use Maatwebsite\Excel\Readers\LaravelExcelReader;
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
        try {
            $file_path = storage_path('app/' . $this->path);
            Excel::load($file_path, function (LaravelExcelReader $reader) {
                DB::beginTransaction();
                $wordbook  = Wordbook::create(['name' => $this->wordbook_name]);
                $words_arr = [];
                $reader->noHeading()->each(function (CellCollection $row) use (&$words_arr) {
                    if ($row->get(0) && $row->get(1)) {
                        $words_arr[] = new WordbookContent([
                            'facade' => $row->get(0),
                            'back'   => $this->insertEnter($row->get(1)),
                        ]);
                    }
                });

                $wordbook->contents()->saveMany($words_arr);
                DB::commit();
                \Log::info('parse wordbook success', ['wordbook' => $wordbook]);
            });
            Storage::delete($this->path);
        } catch (\Exception $exception) {
            DB::rollBack();
            \Log::info('parse wordbook error.', ['file_path' => $this->path, 'exception' => $exception]);
        }
    }

    public function insertEnter($string)
    {
        $searches = ['/\bpron\b\./', '/\badj\b\./', '/\badv\b\./', '/\bad\b\./', '/\bnum\b\./', '/\bart\b\./', '/\bprep\b\./', '/\bconj\b\./',
                     '/\binterj\b\./', '/\babbr\b\./', '/\baux\b\./', '/\bpers\b\./', '/\bint\b\./', '/\bpl\b\./', '/\bvi\b\./', '/\bvt\b\./', '/\bu\b\./', '/\bc\b\./', '/\bcn\b\./', '/\bn\b\./', '/\bv\b\./', '/\ba\b\./', ];
        $replaces = ['<br>pron.', '<br>adj.', '<br>adv.', '<br>ad.', '<br>num.', '<br>art.', '<br>prep.', '<br>conj.',
                     '<br>interj.', '<br>abbr.', '<br>aux.', '<br>pers.', '<br>int.', '<br>pl.', '<br>vi.', '<br>vt.', '<br>u.', '<br>c.', '<br>cn.', '<br>n.', '<br>v.', '<br>a.', ];

        $result = preg_replace($searches, $replaces, $string);

        return $result;
    }
}
