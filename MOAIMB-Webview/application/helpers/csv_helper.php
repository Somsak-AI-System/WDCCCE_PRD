<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('array_to_csv'))
{
    function array_to_csv($array, $download = "")
    {
        if ($download != "")
        {
            header('Content-Type: application/csv');
            header('Content-Disposition: attachement; filename="' . $download . '"');
        }

        ob_start();
        $f = fopen($download, 'wb') or show_error("Can't open php://output");
        $n = 0;
        foreach ($array as $line)
        {
            $n++;
            if ( ! fputcsv($f, $line))
            {
                show_error("Can't write line $n: $line");
            }
        }
        fclose($f) or show_error("Can't close php://output");
        $str = ob_get_contents();
        ob_end_clean();

        if ($download == "")
        {
            return $str;
        }
        else
        {
            echo $str;
        }
    }
}

if(!function_exists("export_files")){
	function export_files( $filename = "",$array,$attachment= false)
	{
		ob_start();
		if($attachment) {
			// send response headers to the browser
			header( 'Content-Type: application/csv' );
			header( 'Content-Disposition: attachment;filename='.$filename);
			$fp = fopen('php://output', 'w');
		} else {
			$fp = fopen($filename, 'w');
		}
		while($row = $array) {
			fputcsv($fp, $row);
		}

		fclose($fp);
		ob_end_clean();
		exit;
	}
}

if ( ! function_exists('write_file_csv'))
{
	function write_file_csv($path, $data, $mode = FOPEN_WRITE_CREATE_DESTRUCTIVE)
	{
		header( 'Content-Type: application/csv' );
		header( 'Content-Disposition: attachment;filename='.$path);
		if ( ! $fp = @fopen($path, $mode))
		{
			return FALSE;
		}

		flock($fp, LOCK_EX);
		fwrite($fp, $data);
		flock($fp, LOCK_UN);
		fclose($fp);

		return TRUE;
	}
}
if ( ! function_exists('query_to_csv'))
{
    function query_to_csv($query, $headers = TRUE, $delim = ",", $newline = "\n", $enclosure = '"')
    {
    	$CI = get_instance();
        if ( ! is_object($query) OR ! method_exists($query, 'list_fields'))
        {
            show_error('invalid query');
        }
        $out = '';
        $a_data = $query->result_array();
        $CI = get_instance();
        $config_filed = $CI->config->item('export');
        $a_field_string = $config_filed['string'];

        if ($headers)
        {

            $fieldname = array_keys($a_data[0]);
            foreach ($fieldname as $name)
            {
            	$out .= $enclosure.str_replace($enclosure, $enclosure.$enclosure, $name).$enclosure.$delim;
            }
            $out = rtrim($out);
            $out .= $newline;
        }

        foreach ($a_data as $row)
		{
			foreach ($row as $key=>$item)
			{
				if( in_array($key,$a_field_string)){
					$out .= $enclosure.str_replace($enclosure, $enclosure.$enclosure, "'".$item).$enclosure.$delim;
				}else{
					$out .= $enclosure.str_replace($enclosure, $enclosure.$enclosure, $item).$enclosure.$delim;
				}

			}
			$out = rtrim($out);
			$out .= $newline;
		}
		return $out;
    }
}

/* End of file csv_helper.php */
/* Location: ./system/helpers/csv_helper.php */