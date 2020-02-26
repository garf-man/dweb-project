<?php


namespace App\Components;


interface CartInterface
{
        public function add($id,$qty,$price);

        public function edit($id,$qty,$action);

        public function delete($id);

        public function get();

        public function count();

        public function save();

        public function destroy();

        public function decrement ($id,$qty);

}