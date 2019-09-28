<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;
use Zipper;

class ParseController extends Controller
{
    // Function for load index page
    public function index() {
        return view('index'); 
    }

    // Function for processing form in index file
    public function load(Request $request) {
        // check zip file        
        if ($request->hasFile('archive')) {
            // create folder name
            $now = strtotime(date("Y-m-d H:i:s")); 
            $path = public_path().'/file/' . $now;
            File::makeDirectory($path, $mode = 0777, true, true);

            // extract zip file
            Zipper::make($request->file('archive'))->extractTo($path);

            return redirect()->action(
                'ParseController@readDirectory', ['id' => $now]
            );    
        } else {
            echo "File not uploaded!";
        }
    }

    // Function find COG files in directory
    public function getDirContents($dir, $filter = '', &$results = array()) {
        $files = scandir($dir);
    
        foreach($files as $key => $value){
            $path = realpath($dir.DIRECTORY_SEPARATOR.$value); 
    
            if(!is_dir($path)) {
                if(empty($filter) || preg_match($filter, $path)) 
                    $results[] = array(
                                        'name' => basename($path),
                                        'path' => $path,
                                        'content' => File::get($path)
                                    );
            } elseif($value != "." && $value != "..") {
                $this->getDirContents($path, $filter, $results);
            }
        }
    
        return $results;
    }

    // Function for load file page
    public function readDirectory($id) {
        $path = public_path().'/file/' . $id . '/';

        // get result.csv
        $resloc =  public_path().'/result.csv';
        $csv = $this->csvHandler($resloc);

        // find all .COG files
        $files = $this->getDirContents($path,'/\.cgt$/');
        echo var_dump($this->scan_dir($path));
        // return view('file', compact('id','files','csv')); 
    }

    // Function for load result page
    public function analyze($id) {
        $path = public_path().'/file/' . $id;

        // find all .COG files
        $files = $this->getDirContents($path,'/\.cgt$/');
        foreach($files as $file){
            $codeline = [];
            if ($fh = fopen($file['path'], 'r')) {
                while (!feof($fh)) {
                    $line = fgets($fh);
                    array_push($codeline, $line);
                }
                fclose($fh);
            }

            //Removes all 3 types of line breaks
            $codeline = str_replace("\r", "", $codeline);
            $codeline = str_replace("\n", "", $codeline);            

            $codes = array(
                'name' => $file['name'],
                'flist' => $codeline);
        }
        
        // get result.csv
        $resloc =  public_path().'/result.csv';
        $csv = $this->csvHandler($resloc);

        return view('script', compact('id','codes', 'csv'));
    }

    // function read specific file
    public function readFile($id,$name) {
        $path = public_path().'/file/' . $id;

        // find all .COG files
        $files = $this->getDirContents($path, '/\.cgt$/');
        foreach($files as $file){
            if($file['name'] == $name) {
                $result = $file['content'];
            }
        }

        return $result;
    }

    // function for calculate number of file and folders
    function scan_dir($path){
        $ite=new \RecursiveDirectoryIterator($path);
    
        $bytestotal=0;
        $nbfiles=0;
        foreach (new \RecursiveIteratorIterator($ite) as $filename=>$cur) {
            $filesize=$cur->getSize();
            $bytestotal+=$filesize;
            $nbfiles++;
            $files[] = $filename;
        }
    
        $bytestotal=number_format($bytestotal);
    
        return array('total_files'=>$nbfiles,'total_size'=>$bytestotal,'files'=>$files);
    }


    // function for csv processing
    public function csvHandler($path)
    {
        $csv = array_map('str_getcsv', file($path));
        array_walk($csv, function(&$a) use ($csv) {
            $a = array_combine($csv[0], $a);
        });
        array_shift($csv); # remove column header        

        return $csv;
    }

    // function read specific file
    public function findFile($id,$name) {
        $path = public_path().'/file/' . $id;
        // $files = $this->readFolder($id);

        // find all .COG files
        $files = $this->getDirContents($path);
        foreach($files as $file){
            if($file['name'] == $name) {
                $result = $file['content'];
            }
        }

        return $result;
    }
}
