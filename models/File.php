<?php
    class File
    {
        private $fileName;
        private $path;

        public function __construct($fileName) {
            $this->fileName = $fileName;

            $this->path = dirname(__DIR__) . "/.data/" . $this->fileName;
            if(file_exists($this->path)) {
                if(is_dir($this->path)) { throw new Exception("'" . $this->fileName . "' is a directory!"); }
            }
            else {
                file_put_contents($this->path, json_encode([]));
            }
        }

        public function read() {
            $contents = file_get_contents($this->path);
            $contents = json_decode($contents);
            return $contents;
        }
        
        public function write($obj) {
            $data = $this->read();
            $data[] = $obj;
            $json = json_encode($data, JSON_PRETTY_PRINT);
            file_put_contents($this->path, $json);
        }
    }
?>