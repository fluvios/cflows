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

            // run cflow
            $comm = 'for i in $(find '.$path.' -type d); do (cd $i && cflow *.c);done';
            $output = shell_exec($comm);

            // write main.cgt
            // $filename = $path."/main.cgt";
            // $fh = fopen($filename, "a");
            // fwrite($fh, $output);
            // fclose($fh);

            // checking whether file exists or not 
            // if(file_exists($filename) == 1) {
            //     return redirect()->action(
            //         'ParseController@readDirectory', ['id' => $now]
            //     );    
            // } else {
            //     echo "Permission Error!";
            // }
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
        $index = 0;

        foreach($files as $key => $value){
            $path = realpath($dir.DIRECTORY_SEPARATOR.$value); 
    
            if(!is_dir($path)) {
                if(empty($filter) || preg_match($filter, $path)) 
                    $results[] = array(
                                        'name' => basename($path),
                                        'path' => str_replace("/","~",$path),
                                        'norpath' => $path,
                                        'content' => File::get($path),
                                        'index' => $index
                                    );
                    $index++;
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
        $code = $this->scan_dir($path);
        return view('file', compact('id','files','csv','code')); 
    }

    // Function for load result page
    public function analyze($id) {
        $path = public_path().'/file/' . $id;
        $codes = array();

        // find all .COG files
        $files = $this->getDirContents($path,'/\.cgt$/');
        foreach($files as $file){
            $codeline = [];
            if ($fh = fopen($file['norpath'], 'r')) {
                $counter = 0;
                while (!feof($fh)) {
                    $line = fgets($fh);
                    if (strpos($line,"EOF") == true) {
                        $loc = $counter;
                    } else {
                        $counter++;
                    }
                    
                    array_push($codeline, $line);
                }
                fclose($fh);
            }

            //Remove EOF Token
            for ($i=0; $i <= $loc; $i++) { 
                # code...
                array_shift($codeline);
            }

            // Remove empty token
                array_pop($codeline);

            //Removes all 3 types of line breaks
            $codeline = str_replace("\r", "", $codeline);
            $codeline = str_replace("\n", "", $codeline);            

            array_push($codes, array(
                'name' => $file['name'],
                'index' => $file['index'],
                'path' => $file['path'],
                'flist' => $codeline));

        }
        
        // get result.csv
        $resloc =  public_path().'/result.csv';
        $csv = $this->csvHandler($resloc);
        $stat = $this->scan_dir($path);

        return view('script', compact('id','codes', 'csv', 'stat'));
    }

    // function read specific file
    public function readFile($id,$name,$index) {
        $prefix = str_replace("~","/",$index);
        $path = public_path().'/file/' . $id . $prefix;

        // find all .COG files
        $files = $this->getDirContents($path, '/\.cgt$/');
        foreach($files as $file){
            if(($file['name'] == $name)) {
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
        $nbfolder=0;
        foreach (new \RecursiveIteratorIterator($ite) as $filename=>$cur) {
            $filesize=$cur->getSize();
            $bytestotal+=$filesize;
            if (preg_match('/\/\./i', $filename)) {
                $nbfolder++;
            } else {
                $nbfiles++;
            }
            $files[] = $filename;
        }
    
        $bytestotal=number_format($bytestotal);
    
        return array('total_files'=>$nbfiles, 'total_folder'=>($nbfolder/2)-1,
        'total_size'=>$bytestotal,'files'=>$files);
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
    public function findFile($id, $name, $index) {
        $prefix = str_replace("~","/",$index);
        $path = public_path().'/file/' . $id . $prefix;
        // $files = $this->readFolder($id);

        // find all .COG files
        $files = $this->getDirContents($path);
        foreach($files as $file){
            if($file['name'] == $name) {
                $result = preg_split("/\r\n|\n|\r/", $file['content']);
            }
        }

        return $result;
    }
}