<?php


namespace App\Services\Pdf;


class PdfDocument
{
    public string $name;
    public string $inn;
    public string $date;
    public string $okved;

    public function __construct($name, $inn, $date, $okved)
    {
        $this->name = $name;
        $this->inn = $inn;
        $this->date = $date;
        $this->okved = $okved;
    }
}
