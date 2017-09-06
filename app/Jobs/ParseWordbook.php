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
        $searches = ['n.', 'v.', 'pron.', 'adj.', 'a.', 'adv.', 'ad.', 'num.', 'art.', 'prep.', 'conj.',
                     'interj.', 'int.', 'vi.', 'vt.', 'u.', 'c.', 'cn.', 'pl.', 'abbr.', 'aux.', 'pers.', ];
        $replaces = ['<br>n.', '<br>v.', '<br>pron.', '<br>adj.', '<br>a.', '<br>adv.', '<br>ad.', '<br>num.', '<br>art.', '<br>prep.', '<br>conj.',
                     '<br>interj.', '<br>int.', '<br>vi.', '<br>vt.', '<br>u.', '<br>c.', '<br>cn.', '<br>pl.', '<br>abbr.', '<br>aux.', '<br>pers.', ];
        $result   = str_replace($searches, $replaces, $string);

        return $result;
    }
}
