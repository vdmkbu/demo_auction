<?php


namespace App\Services\Pdf;

use antonshell\EgrulNalogParser\Parser;

class PdfParserService
{
    private Parser $parser;

    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    public function parseDocument($path): PdfDocument
    {
        $result = $this->parser->parseDocument($path);
		$plaint_text = $this->parser->getPlainText($path);

		$name = $this->getName($result);
		$inn = $this->getINN($result);
		$date = $this->getDate($plaint_text);
		$okved = $this->getOKVED($result);

		return new PdfDocument($name, $inn, $date, $okved);
    }

    private function getName($result)
    {

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

    private function getINN($result)
    {
        return preg_replace('/\D/','',$result['taxes']['inn']);
    }

    private function getDate($result)
    {

        preg_match("/по состоянию на (.+)\s№\s/", $result, $output_array);
        $date = isset($output_array[1]) ?: null;

        return $date ? trim($date) : Date('Y-m-d');

    }

    private function getOKVED($result)
    {

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
