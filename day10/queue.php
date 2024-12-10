<?php

class Node
{
    public $value;
    public $next;

    public function __construct($value)
    {
        $this->value = $value;
        $this->next = null;
    }
}

class Queue
{
    public $first;
    public $last;
    public $length;

    public function __construct()
    {
        $this->first = null;
        $this->last = null;
        $this->length = 0;
    }

    public function printQueue()
    {
        echo "Queue:\n";
        $temp = $this->first;
        while ($temp != null) {
            print_r($temp->value);
            $temp = $temp->next;
        }
    }

    public function enqueue($value)
    {
        $newNode = new Node($value);
        if ($this->length == 0) {
            $this->first = $newNode;
            $this->last = $newNode;
        } else {
            $this->last->next = $newNode;
            $this->last = $newNode;
        }
        $this->length++;
    }

    public function dequeue()
    {
        if ($this->length == 0) {
            return null;
        }
        $temp = $this->first;
        if ($this->length == 1) {
            $this->first = null;
            $this->last = null;
        } else {
            $this->first = $this->first->next;
            $temp->next = null;
        }
        $this->length--;
        return $temp->value;
    }

    public function isEmpty() {
        return $this->length === 0;
    }

}
