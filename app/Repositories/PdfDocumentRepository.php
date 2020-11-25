<?php


namespace App\Repositories;


class PdfDocumentRepository
{
    protected $parsed_data;
    protected $plain_text;

    public function __construct($parsed_data, $plain_text)
    {
        $this->parsed_data = $parsed_data;
        $this->plain_text = $plain_text;
    }

    public function getData()
    {
        return $this->parsed_data;
    }

    public function getPlainText()
    {
        return $this->plain_text;
    }

    public function getName()
    {
        $result = $this->getData();

        if(isset($result['common']['full_name']))
        {
            // организации
            $name = $result['common']['full_name'];
        }
        else {

            // ИП
            $last_name = $result['common']['last_name'];
            $first_name = $result['common']['first_name'];
            $middle_name = $result['common']['middle_name'];

            $name = "ИП ".implode(' ', [$last_name, $first_name, $middle_name]);
        }

        return $name;
    }

    public function getINN()
    {
        $result = $this->getData();
        return preg_replace('/\D/','',$result['taxes']['inn']);
    }

    public function getDate()
    {
        $result = $this->getPlainText();
        preg_match("/по состоянию на (.+)\s№\s/", $result, $output_array);
        $date = isset($output_array[1]) ?: null;

        return $date ? trim($date) : Date('Y-m-d');

    }

    public function getOKVED()
    {
        $result = $this->getData();

        // список ОКВЭДов
        $main_activity = $result['main_activity'];
        $extra_activity =  $result['extra_activity'];


        foreach($main_activity as $code_name => $okveds) {

            if ($code_name == 'code_name') {
                $okved_storage[] = $okveds;
            }
        }


        foreach($extra_activity as $index => $okveds) {

            foreach ($okveds as $code_name => $o) {

                if (strpos($code_name, 'code_name') !== false) {
                    $okved_storage[] = $o;
                }
            }

        }


        $okved = join('|',$okved_storage);

        return $okved;
    }
}
