<?php

namespace App\Http\Controllers;
use ZipArchive;
use DateTime;
use App\Corpus;
use App\User;
use Dingo\Api\Auth\Auth;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Mockery\Exception;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Webpatser\Uuid\Uuid;

class CorpusController extends Controller
{
    use Helpers;
    /**
     * Stores new file on 'storage/app/corpus/' directory uncompress the file in /public/unzipedCorpus and
     * delete the old file from 'storage/app/corpus/'
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request;
        $user = $this->getCurrentUser();
        $apiStorageBasePath = "../storage/app/"; // directory where the API stores the compresses corpus
        $fecha = new DateTime();
        $timestamp = $fecha->getTimestamp();
        $unzippedCorpusDir = "../public/unzippedCorpus/".$timestamp; //directory where the controller unzip the compressed corpus
        // Insert current user UUID as Foreign Key
        try {
            $data['uuid'] = Uuid::generate(4)->string;// UUID type 4, random generation
        } catch (\Exception $e) {
            return response()->json(['error' => 'could_not_generate_corpus_uuid'], 500);
        }
        $data['user_id'] = $user->id;
        $data['path'] = $request->file('file')->store('corpus');
        $apiCorpusName = $data['path'];

        $fileToUnzip = $apiStorageBasePath.$apiCorpusName;

        // raising permissions to the current compressed corpus
        chmod($fileToUnzip,0644);
        // Unzipping the compressed corpus
        $zip = new ZipArchive;
        if ($zip->open($fileToUnzip) === TRUE) {
            $zip->extractTo($unzippedCorpusDir."/".substr($apiCorpusName,8,-4));
            $zip->close();
        }

        //delete the upload file from API
        unlink($fileToUnzip);
        // detecting the name of the corpus in the directory
        $files = array_diff(scandir($unzippedCorpusDir), array('.', '..'));
        $extractedCorpus =  "";
        foreach($files as $file){
            $finalCorpusName = $file;// root directory name of the corpus
            $extractedCorpus = $unzippedCorpusDir."/".$file; //path to the new corpus root directory
            chmod($unzippedCorpusDir,0777);
        }
        $corpusData = array();

        $configurationFile = simplexml_load_file($extractedCorpus."/corpus_summary.xml");
        $spamDir = $configurationFile->spamDir;
        $hamDir = $configurationFile->hamDir;
        exec("java -jar ../storage/java/corpusDataExtractor.jar ".$unzippedCorpusDir."/".$finalCorpusName." ".$spamDir." ".$hamDir, $corpusData);
        $data['name'] = $finalCorpusName;
        $data['size'] = $this->folderSize($extractedCorpus)/1000000;
        $data['description'] = "This corpus has not a description yet";
        $data['all_content_languages'] = $corpusData[0];
        $data['all_content_types'] = $corpusData[1];
        $data['all_content_dates'] = $corpusData[2];
        $data['total_sites'] = $configurationFile->numSpamPages + $configurationFile->numHamPages;
        if($data['total_sites'] == 0){
            $data['spam_amount'] = 0;
        }else{
            $data['spam_amount'] = $configurationFile->numSpamPages/$data['total_sites'] * 100;
        }
        $data['downloads'] = 0;
        $data['status'] = "public";
        $data['path'] = $extractedCorpus;
        $data['spamDir'] = $spamDir;
        $data['hamDir'] = $hamDir;
        $corpus = Corpus::create($data->all());
        $corpus->save();
        //return $extractedCorpus;//return .response()->json($corpus, 200);
        return $corpus;
    }

    /**
     * Get current user
     *
     * @return User
     */
    private function getCurrentUser()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            // If the token is invalid
            if (! $user) {
                return response()->json(['invalid user'], 401);
            }
        } catch (TokenInvalidException $e) {
            return $this->response->error("Token is invalid");
        } catch (TokenExpiredException $e) {
            return $this->response->error("Token has expired");
        } catch (TokenBlackListedException $e) {
            return $this->response->error("Token is blacklisted");
        }
        return $user;
    }

    /**
     * Update the attributes of a corpus and redirect to user/home view
     * @param $corpus_uuid the uuid of the corpus you want modify
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($corpus_uuid, Request $request){
        if(auth()->user()->role != 'admin'){

            $corpus = Corpus::where('uuid','=',$corpus_uuid)->get()[0];
            if($request->corpus_name != "" && $request->corpus_name != null){
                $corpus->name = $request->corpus_name;
            }
            if($request->description != null){
                $corpus->description = $request->description;
            }
            if($request->make_private != null){
                $corpus->status = "private";
            }
            if($request->make_public != null){
                $corpus->status = "public";
            }
            if($request->make_private != null){
                $corpus->status = "private";
            }
            $corpus->save();
            return redirect('user/home');
        }else{
            return redirect('user/home');
        }
    }

    /**
     * @param $dir you want to know the size
     * @return int the size of $dir
     */
    private function folderSize ($dir)
    {
        $size = 0;
        foreach (glob(rtrim($dir, '/').'/*', GLOB_NOSORT) as $each) {
            $size += is_file($each) ? filesize($each) : $this->folderSize($each);
        }
        return $size;
    }

    /**
     * Search corpus using the request params and return to the view 'corpus.search-corpus'
     * @param Request $request
     * @param Corpus $corpus Instace of model Corpus
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search(Request $request, Corpus $corpus){
        if(auth()->user()->role != 'admin'){

            $corpuses = $corpus->newQuery();

            if ($request->has('name')) {
                $user = User::where('name', 'LIKE',"%{$request->input('name')}%")->first();
                if($user != null){
                    $corpuses->where('user_id', $user->id);
                }
            }

            if ($request->has('size')) {
                $corpuses->where('size', '<=', $request->input('size'));
            }

            if ($request->has('spam_amount')) {
                $corpuses->where('spam_amount','<=', $request->input('spam_amount'));
            }
            if($request->has('status')){
                if($request->get('status') == 'myCorpus') {
                    $corpuses->where('user_id', auth()->user()->id);
                    $corpuses->where('status', '!=' ,'trash');
                    $corpuses->where('status', '!=' ,'removed');
                }
                else{
                    $corpuses->where('status', '=','public');
                }
            }else{
                $corpuses->where('status', '=','public');
            }
            return view('corpus.search-corpus', ['corpuses' => $corpuses->get()]);
        }else{
            return redirect('user/home');
        }
    }

    /**
     * send a corpus to the trash and redirect to user/home view
     * @param $corpus_uuid the uuid of the corpus you want sent to the trash
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete($corpus_uuid){
        $corpus = Corpus::where('uuid','=',$corpus_uuid)->get()[0];
        if(auth()->user()->id == $corpus->user_id){
            $corpus->status = "trash";
            $corpus->save();
        }
        return redirect('user/home');
    }

    /**
     * marka a corpus as removed (soft delete)
     * @param $corpus_uuid the uuid of the corpus you want to remove and redirect to user/home view
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function remove($corpus_uuid){
        $corpus = Corpus::where('uuid','=',$corpus_uuid)->get()[0];
        if(auth()->user()->id == $corpus->user_id){
            $corpus->status = "removed";
            $corpus->save();
        }
        return redirect('user/home');
    }

    /**
     * restore a corpus from the trash and redirect to user/home view
     * @param $corpus_uuid the uuid of the corpus you want to restore from the trash
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function restore($corpus_uuid){
        $corpus = Corpus::where('uuid','=',$corpus_uuid)->get()[0];
        if(auth()->user()->id == $corpus->user_id){
            $corpus->status = "public";
            $corpus->save();
        }
        return redirect('user/home');
    }

    /**
     * mark all corpus in the trash like removed and redirect to user/home view
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function empty_trash(){
        $all_corpus = Corpus::where('user_id','=',auth()->user()->id)->where('status','=','trash')->get();
        foreach ($all_corpus as $corpus){
            $corpus->status = 'removed';
            $corpus->save();
        }
        return redirect('user/home');
    }

    /**
     * Create a new corpus using the parameters of the request and return to view user/home
     */
    public function joinCorpus(Request $request){
        $fecha = new DateTime();
        $timestamp = $fecha->getTimestamp();
        $unzippedCorpusDir = "../public/unzippedCorpus/".$timestamp."/";
        $newCorpusData = array();
        $newCorpusData[0] = 0;
        $newCorpusData[1] = 0;
        $finalLangsAcumulative = "";
        $finalContentsAcumulative = "";
        $finalDatesAcumulative = "";
        foreach ($request->uuids as $uuid ){
            $corpuses = array();
            $corpuses[$uuid[0]] = array();
            $currentCorpus = Corpus::where('uuid','=',$uuid[0])->get()[0];
            $dates =  $request->dates[$uuid[0]];
            $contents = $request->contents[$uuid[0]];
            $langs = $request->langs[$uuid[0]];
            $spam = false;
            $ham = false;
            $spam_ham = "";
            if(isset($request->spam[$uuid[0]])){
                $spam = true;
            }
            if(isset($request->ham[$uuid[0]])){
                $ham = true;
            }
            if($spam && $ham){
                $spam_ham = "both";
            }else{
                if($spam == true && $ham == false){
                    $spam_ham = "spam";
                }else{
                    $spam_ham = "ham";
                }
            }
            $corpuses[$uuid[0]]['dates'] = $dates;
            $finalDates = implode(",",$corpuses[$uuid[0]]['dates']);

            $corpuses[$uuid[0]]['langs'] = $langs;
            $finalLangs = implode(",",$corpuses[$uuid[0]]['langs']);

            $corpuses[$uuid[0]]['contents'] = $contents;
            $currentCorpus_content_types = explode(",",$currentCorpus->all_content_types);
            foreach ($currentCorpus_content_types as $key => $currentContentType){
                $eliminate = true;
                foreach ($corpuses[$uuid[0]]['contents'] as $selectedContenType){
                    $strposvar = stristr($currentContentType, $selectedContenType);
                    if( $strposvar != false ){
                        $eliminate = false;
                    }
                }
                if($eliminate){
                    unset($currentCorpus_content_types[$key]);
                }
            }
            $finalContents = implode(",",$currentCorpus_content_types);

            $finalContents = "\"".$finalContents."\"";
            $initialCorpusPath = Corpus::where('uuid','=',$uuid[0])->get()[0]->path;
            $finalCorpusPath = $unzippedCorpusDir."newCorpus_".$timestamp;

            $returnVar = array();//pathcorpusInicial pathCorpusFinal en,gl,es,fr "text/html; charset=UTF-8",text/html 2018 both spam ham
            //contains numSpam on index [0], numHam on index[1], the dstinct content types on index[2] the distinct
            //content languages on index[3] and the distinct content dates on index[4]

            exec("java -jar ../storage/java/corpusJoiner.jar ".$initialCorpusPath." ".
                $finalCorpusPath." " .
                $finalLangs." ".
                $finalContents." ".
                $finalDates." ".
                $spam_ham." ".
                $currentCorpus->spamDir." ".
                $currentCorpus->hamDir, $returnVar);
            $newCorpusData[0] +=  $returnVar[0];
            $newCorpusData[1] +=  $returnVar[1];
            $finalContentsAcumulative = $finalContentsAcumulative.$returnVar[2];
            $finalLangsAcumulative = $finalLangsAcumulative.$returnVar[3];
            $finalDatesAcumulative = $returnVar[4];
        }
        $data = $request;
        try {
            $data['uuid'] = Uuid::generate(4)->string;// UUID type 4, random generation
        } catch (\Exception $e) {
            return response()->json(['error' => 'could_not_generate_corpus_uuid'], 500);
        }
        $data['user_id'] = auth()->user()->id;
        $data['name'] = "newCorpus_".$timestamp;
        $data['size'] = $this->folderSize($finalCorpusPath)/1000000;
        $data['description'] = "This corpus has not a description yet";
        if(substr($finalLangsAcumulative,-1) == ','){
            $finalLangsAcumulative = substr_replace($finalLangsAcumulative, "", -1);
        }
        $data['all_content_languages'] = implode(",",array_unique(explode(",",$finalLangsAcumulative)));
        if(substr($finalContentsAcumulative,-1) == ','){
            $finalContentsAcumulative = substr_replace($finalContentsAcumulative, "", -1);
        }
        $data['all_content_types'] = implode(",",array_unique(explode(",",$finalContentsAcumulative)));
        if(substr($finalDatesAcumulative,-1) == ','){
            $finalDatesAcumulative = substr_replace($finalDatesAcumulative, "", -1);
        }
        $data['all_content_dates'] = implode(",",array_unique(explode(",",$finalDatesAcumulative)));
        $data['total_sites'] = $newCorpusData[0] + $newCorpusData[1];
        $data['spam_amount'] = $newCorpusData[0]/$data['total_sites'] * 100;
        $data['downloads'] = 0;
        $data['status'] = "public";
        $data['path'] = $finalCorpusPath;
        $data['spamDir'] = "spam";
        $data['hamDir'] = "ham";
        $corpus = Corpus::create($data->all());
        $corpus->save();
        return redirect('user/home');
    }

    /**
     * redirect to the corpus.filterAndSetup view passing the selected corpus in the request
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function filter(Request $request)
    {
        $corpuses = Corpus::find($request->get('corpus'));
        $uuids = array();
        foreach ($corpuses as $corpus){
            array_push($uuids, $corpus->uuid);
        }
        return view('corpus.filterAndSetup', ['corpuses' => $corpuses, 'uuids' => $uuids]);
    }
}

