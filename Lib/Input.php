<?php
namespace Lib;

class Input {
    
    public static function get($type, $name, $default = false, $cast = null)
    {
        $data = '';
        switch($type)
        {
            case 'post':
                $data = $_POST;
                break;
            case 'put':
                parse_str(file_get_contents("php://input"),$data);
                break;
            default :
                return $default;
        }
        
        if (!isset($data[$name])) {
            return $default;
        }

        $post = $data[$name];

        if (!empty($cast))
        {
            $_cast = explode('|', $cast);

            foreach($_cast as $c)
            {
                switch($c)
                {
                    case 'int':
                        $post = (int)$post;
                        break;
                    case 'trim':
                        $post = trim($post);
                        break;
                    //TODO: implement other casts
                    default :
                        break;
                }
            }
        }
        
        return $post;
    }
    
    public static function validateDatetime($date, $format='Y-m-d H:i:s')
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}