<?php

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use App\User;
use App\Corpus;
use Webpatser\Uuid\Uuid;

class CorpusSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     *
     */
    public function run()
    {
        DB::table('corpus')->delete();
        $john = User::where('email', 'john@warcrepository.com')->get();
        $jane = User::where('email', 'jane@warcrepository.com')->get();
        Corpus::create([
            'uuid' => Uuid::generate(4)->string,
            'user_id' => $jane[0]->id,
            'name' => 'corpus_file_ds_02' ,
            'size' => 1.38,
            'description' => "This corpus has not a description yet" ,
            'all_content_languages' => 'en,fr,en-GB,es,en-US',
            'all_content_types' => 'text/html; charset=UTF-8,text/html,text/html; charset=utf-8',
            'all_content_dates' => '2018',
            'total_sites' => 48,
            'spam_amount' => 50.00,
            'downloads' => 0,
            'status' => 'public',
            'path' => '../public/unzippedCorpus/1527702604/corpus_file_ds_02',
            'spamDir' => '_spam_',
            'hamDir' => '_ham_',
        ]);
        Corpus::create([
            'uuid' => Uuid::generate(4)->string,
            'user_id' => $john[0]->id,
            'name' => 'corpus_warc_ds_02' ,
            'size' => 0.10,
            'description' => "This corpus has not a description yet" ,
            'all_content_languages' => 'ar,hr',
            'all_content_types' => 'text/html,font/woff2',
            'all_content_dates' => '2018',
            'total_sites' => 2,
            'spam_amount' => 50.00,
            'downloads' => 0,
            'status' => 'public',
            'path' => '../public/unzippedCorpus/1527703220/corpus_warc_ds_02',
            'spamDir' => '_spam_',
            'hamDir' => '_ham_',
        ]);
        Corpus::create([
            'uuid' => Uuid::generate(4)->string,
            'user_id' => $jane[0]->id,
            'name' => 'corpus_file_ds_03' ,
            'size' => 0.70,
            'description' => "This corpus has not a description yet" ,
            'all_content_languages' => 'en,fr,en-GB,es,en-US',
            'all_content_types' => 'text/html; charset=UTF-8,text/html,text/html; charset=utf-8',
            'all_content_dates' => '2018',
            'total_sites' => 32,
            'spam_amount' => 75.00,
            'downloads' => 0,
            'status' => 'public',
            'path' => '../public/unzippedCorpus/1527702791/corpus_file_ds_03',
            'spamDir' => '_spam_',
            'hamDir' => '_ham_',
        ]);
        Corpus::create([
            'uuid' => Uuid::generate(4)->string,
            'user_id' => $jane[0]->id,
            'name' => 'corpus_file_ds_04' ,
            'size' => 0.50,
            'description' => "This corpus has not a description yet" ,
            'all_content_languages' => 'en,fr,en-GB,es,en-US',
            'all_content_types' => 'text/html; charset=UTF-8,text/html,text/html; charset=utf-8',
            'all_content_dates' => '2018',
            'total_sites' => 24,
            'spam_amount' => 100.00,
            'downloads' => 0,
            'status' => 'public',
            'path' => '../public/unzippedCorpus/1527702851/corpus_file_ds_04',
            'spamDir' => '_spam_',
            'hamDir' => '_ham_',
        ]);
        Corpus::create([
            'uuid' => Uuid::generate(4)->string,
            'user_id' => $john[0]->id,
            'name' => 'corpus_warc_ds_01' ,
            'size' => 6.79,
            'description' => "This corpus has not a description yet" ,
            'all_content_languages' => 'ar,no,en,hr,fr',
            'all_content_types' => 'text/html,text/html; charset=UTF-8,text/html; charset=utf-8,text/html; charset=iso-8859-1',
            'all_content_dates' => '2018',
            'total_sites' => 235,
            'spam_amount' => 0.00,
            'downloads' => 0,
            'status' => 'public',
            'path' => '../public/unzippedCorpus/1527702966/corpus_warc_ds_01',
            'spamDir' => '_spam_',
            'hamDir' => '_ham_',
        ]);
        Corpus::create([
            'uuid' => Uuid::generate(4)->string,
            'user_id' => $john[0]->id,
            'name' => 'corpus_csv_02' ,
            'size' => 10.64,
            'description' => "This corpus has not a description yet" ,
            'all_content_languages' => 'en,en-US,fr,de-DE,en-gb,nl-nl,en-GB,es,ru,de-de,ar,tr-TR,de,lt,mul,hr,hu,fr-FR,pl,ja,en-EN,en-rd,cs,it,es-ES,da,cs-CZ,pt,EN,nl',
            'all_content_types' => 'text/html; charset=UTF-8,text/html,text/html; charset=utf-8,text/html;charset=utf-8,text/html;charset=ISO-8859-1,text/html;charset=UTF-8,text/html; charset=iso-8859-1,text/html;charset=iso-8859-1,text/html; charset=ISO-8859-1,text/html; Charset=utf-8,text/html; charset=windows-1250',
            'all_content_dates' => '2018',
            'total_sites' => 429,
            'spam_amount' => 100.00,
            'downloads' => 0,
            'status' => 'public',
            'path' => '../public/unzippedCorpus/1527705234/corpus_csv_02',
            'spamDir' => '_spam_',
            'hamDir' => '_ham_',
        ]);

        Corpus::create([
            'uuid' => Uuid::generate(4)->string,
            'user_id' => $john[0]->id,
            'name' => 'corpus_csv_03' ,
            'size' => 0.40,
            'description' => "This corpus has not a description yet" ,
            'all_content_languages' => 'en-gb,fr-FR,en,it,de,nl-nl,fr,de-DE',
            'all_content_types' => 'text/html,text/html; charset=UTF-8,text/html; charset=utf-8',
            'all_content_dates' => '2018',
            'total_sites' => 20,
            'spam_amount' => 50.00,
            'downloads' => 0,
            'status' => 'public',
            'path' => '../public/unzippedCorpus/1527713322/corpus_csv_03',
            'spamDir' => '_spam_',
            'hamDir' => '_ham_',
        ]);
    }
}
