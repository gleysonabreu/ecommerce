<?php 

namespace Hcode\Model;

use \Hcode\Model;
use \Hcode\DB\Sql;

class Address extends Model {
    protected $fields = [
        "idaddress", "idperson", "desaddress", 'descomplement', 'desdistrict', 'descity', 'desstate', 'descountry', 'desnumber', 'deszipcode'
    ];
    const SESSION_ERROR = 'AddressError';
    public static function getCep($nrcep){

        $nrcep = str_replace("-", "", $nrcep);

        $ch = CURL_INIT();

        CURL_SETOPT($ch, CURLOPT_URL, "https://viacep.com.br/ws/$nrcep/json/");

        CURL_SETOPT($ch, CURLOPT_RETURNTRANSFER, true);
        CURL_SETOPT($ch, CURLOPT_SSL_VERIFYPEER, false);

        $data = json_decode(CURL_EXEC($ch), true);

        CURL_CLOSE($ch);

        return $data;

    }

    public function loadFromCep($nrcep){

        $data = Address::getCep($nrcep);

        if(isset($data['logradouro']) && $data['logradouro']) {

            $this->setdesaddress($data['logradouro']);
            $this->setdescomplement($data['complemento']);
            $this->setdesdistrict($data['bairro']);
            $this->setdescity($data['localidade']);
            $this->setdesstate($data['uf']);
            $this->setdescountry("Brasil");
            $this->setdeszipcode($nrcep);

        }

    }

    public function save(){

        $sql = new Sql();

        $results = $sql->select("CALL sp_addresses_save(:idaddress, :idperson, :desaddress, :desnumber, :descomplement, :descity, :desstate, :descountry, :deszipcode, :desdistrict)", [
            ':idaddress'=> $this->getidaddress(),
            ':idperson'=> $this->getidperson(),
            ':desaddress'=> $this->getdesaddress(),
            ':desnumber'=> $this->getdesnumber(),
            ':descomplement'=> $this->getdescomplement(),
            ':descity'=> $this->getdescity(),
            ':desstate'=> $this->getdesstate(),
            ':descountry'=> $this->getdescountry(),
            ':deszipcode'=> $this->getdeszipcode(),
            ':desdistrict'=> $this->getdesdistrict()
        ]);

        if(count($results) > 0){
            $this->setData($results[0]);
        }
    }

    public static function setMsgError($msg){
        $_SESSION[Address::SESSION_ERROR]  = $msg;
    }

    public static function getMsgError(){

        $msg = (isset($_SESSION[Address::SESSION_ERROR])) ? $_SESSION[Address::SESSION_ERROR] : "";

        Cart::clearMsgError();

        return $msg;

    }

    public  static function clearMsgError(){
        $_SESSION[Address::SESSION_ERROR] = NULL;
    }

}

 ?>