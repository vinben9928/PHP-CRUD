<?php
    class File
    {
        private $fileName;

        public function __construct($fileName) {
            $this->fileName = $fileName;

            $path = dirname(__DIR__) . "/.data/" . $this->fileName;
            if(file_exists($path)) {
                if(is_dir($path)) { throw new Exception("'" . $this->fileName . "' is a directory!"); }
            }
            else {
                file_put_contents($path, json_encode([]));
            }
        }

        public function read() {
            $contents = file_get_contents(".data/" . $this->fileName);
            $contents = json_decode($contents);
            return $contents;
        }
        
        public function write($obj) {
            $data = $this->read();
            $data[] = $obj;
            $json = json_encode($data, JSON_PRETTY_PRINT);
            file_put_contents(".data/" . $this->fileName, $json);
        }
    }
?>