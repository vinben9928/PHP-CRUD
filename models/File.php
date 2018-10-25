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

        public function readRaw() {
            $contents = file_get_contents($this->path);
            return $contents;
        }
        
        public function write($obj) {
            $data = $this->read();
            $data[] = $obj;
            $json = json_encode($data, JSON_PRETTY_PRINT);
            file_put_contents($this->path, $json);
        }

        public function deleteObjectByProperty($property, $value) {
            $index = $this->getIndexByProperty($property, $value);
            if($index < 0) { return false; }

            $contents = $this->read();
            array_splice($contents, $index, 1);

            $json = json_encode($contents, JSON_PRETTY_PRINT);
            file_put_contents($this->path, $json);
                    
            return true;
        }

        public function getIndexByProperty($property, $value) {
            $contents = $this->read();
            $count = count($contents);

            for($i = 0; $i < $count; $i++) {
                $obj = $contents[$i];

                if(isset($obj->$property) && $obj->$property == $value) {
                    return $i;
                }
            }

            return -1;
        }

        public function getObjectByProperty($property, $value) {
            $index = $this->getIndexByProperty($property, $value);
            if($index < 0) { return null; }

            $contents = $this->read();
            return $contents[$index];
        }

        public function setObjectByProperty($property, $value, $obj) {
            $index = $this->getIndexByProperty($property, $value);
            if($index < 0) { return false; }

            $contents = $this->read();
            $contents[$index] = $obj;

            $json = json_encode($contents, JSON_PRETTY_PRINT);
            file_put_contents($this->path, $json);

            return true;
        }
    }
?>