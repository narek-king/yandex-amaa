<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \GuzzleHttp\Client as http;

class MainController extends Controller
{

    private $client_id;
    private $client_secret;
    private $base_url;
    private $amaa_key;
    private $school_key;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
        $this->middleware('auth', ['except' => [
            'index',
            'yandexLogin',
            'yandexCallback',
            'yandexRefreshToken'

        ]]);

        $this->base_url = env('OURL');
        $this->client_secret = env('OCLIENT_SECRET');
        $this->client_id = env('OCIENT_ID');
        $this->amaa_key = env('AMAA_KEY');
        $this->school_key = env('SCHOOL_KEY');
    }

    public function index($world){

//        return view('home', ['world' => $world]);
    }

    public function yandexLogin(){
         // Id приложения
         // Пароль приложения
        // Callback URI

        $url= $this->base_url . 'authorize';

        $params = array(
	    'response_type' => 'code',
	    'client_id'     => $this->client_id
	    );
        $link = $url . '?' . urldecode(http_build_query($params));
        return view('login', ['link' => $link]);


    }

    public function yandexCallback(Request $request){

        if ($request->input('code') != null) {

            $params = ['form_params' => [
                'grant_type'    => 'authorization_code',
                'code'          => $request->input('code'),
                'client_id'     => $this->client_id,
                'client_secret' => $this->client_secret
            ]];

            $token = new http (['base_uri' => $this->base_url]);
            $response = $token->post('token', $params);

            echo "<script> localStorage.setItem('user', '". $response->getBody() ."');
                window.location.href = '". url() ."' </script>";
        }
    }

    public function yandexRefreshToken(Request $request){
        $params = ['form_params' => ['grant_type' => $request->input('grant_type'),
            'refresh_token' => $request->input('refresh_token'),
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret]];
        $token = new http (['base_uri' => $this->base_url]);
        $response = $token->request('post','token', $params);
        return $response->getBody();
    }

    public function prepareEmail(Request $request){
        $name = '';
        $domain = '';
        if ($request->input('name') != null)
            $name = $request->input('name');
        if ($request->input('domain') != null)
            $domain = $request->input('domain');

        $login = $this->normalize($this->wordParser($this->brake_word($name)));
        return response()->json(['name'=>$name,
                                'login'=>$login,
                                'password'=>$this->passwordMaker(),
                                'email'=>$login."@".$domain
        ]);
    }

    public function createAccount(Request $request){
        if (!$request->input('login') || !$request->input('password') || !$request->input('domain')){
            return response()->json(['data' => 'error'], 400);
        }
        $params = ['form_params' => ['domain'=> $request->input('domain'),
                                    'login' => $request->input('login'),
                                    'password' => $request->input('password')],
                  'headers' => ['PddToken' => $this->getDomainKey($request->input('domain'))]];
        $account = new http (['base_uri' => 'https://pddimp.yandex.ru/api2/admin/email/']);
        $response = $account->post( 'add', $params);
        $json = $response->getBody();
        $response = json_decode($json, true);
        if ($response['success'] == 'ok'){
            $this->addToMailList($request->input('login'), $request->input('domain'));
        }
        return $json;
    }

    function addToMailList($id, $domain, $list='no-reply'){
        if (!isset($id) || !isset($domain))
            return null;
        $params = ['form_params' => ['domain'=> $domain,
            'maillist' => $list,
            'subscriber' => $id,
            'can_send_on_behalf' => 'no'],
            'headers' => ['PddToken' => $this->getDomainKey($domain)]];
        $account = new http (['base_uri' => 'https://pddimp.yandex.ru/api2/admin/email/ml/']);
        $response = $account->post( 'subscribe', $params);
        $response = json_decode($response->getBody(), true);
        return $response['success'];
    }

    function getDomainKey($domain){
        if (in_array($domain, ['amaa.am', 'lyd.am', 'eca.am']))
            return $this->amaa_key;
        elseif ($domain === 'avedisianschool.am')
            return $this->school_key;
    }

    function brake_word($string){
        $words = explode(" ", $string);
        if (isset($words[1])){
            $newWord[0]= $words[1];
            $newWord[1]= ".";
            $newWord[2]= $words[0];
            return implode($newWord);}
        else return "Undefined";
    }


    function parse($char){
        $char = mb_strtolower($char, 'UTF-8');
        if (preg_match("/[a-z]/", $char) === 1)
            return $char;
        switch ($char){
            case "ա":
                return "a";
                break;
            case  "բ":
                return "b";
                break;
            case   "գ":
                return "g";
                break;
            case   "դ":
                return "d";
                break;
            case  "ե":
                return "e";
                break;
            case  "զ":
                return "z";
                break;
            case  "է":
                return "e";
                break;
            case  "ը":
                return "y";
                break;
            case  "թ":
                return "t";
                break;
            case  "ժ":
                return "jh";
                break;
            case  "ի":
                return "i";
                break;
            case  "լ":
                return "l";
                break;
            case  "խ":
                return "kh";
                break;
            case  "ծ":
                return "ts";
                break;
            case  "կ":
                return "k";
                break;
            case  "հ":
                return "h";
                break;
            case  "ձ":
                return "dz";
                break;
            case  "ղ":
                return "gh";
                break;
            case  "ճ":
                return "ch";
                break;
            case  "մ":
                return "m";
                break;
            case  "յ":
                return "y";
                break;
            case  "ն":
                return "n";
                break;
            case  "շ":
                return "sh";
                break;
            case  "ո":
                return "o";
                break;
            case  "չ":
                return "ch";
                break;
            case  "պ":
                return "p";
                break;
            case  "ջ":
                return "j";
                break;
            case  "ռ":
                return "r";
                break;
            case  "ս":
                return "s";
                break;
            case  "վ":
                return "v";
                break;
            case  "տ":
                return "t";
                break;
            case  "ր":
                return "r";
                break;
            case  "ց":
                return "tc";
                break;
            case  "ւ":
                return "u";
                break;
            case  "փ":
                return "p";
                break;
            case  "ք":
                return "q";
                break;
            case  "և":
                return "ev";
                break;
            case  "օ":
                return "o";
                break;
            case  "ֆ":
                return "f";
                break;
            default: return ".";
        }

    }

    function mbStringToArray ($string, $encoding = 'UTF-8') {
        $array = array();
        $strlen = mb_strlen($string);
        while ($strlen) {
            $array[] = mb_substr($string,0,1,$encoding);
            $string = mb_substr($string,1,$strlen,$encoding);
            $strlen = mb_strlen($string);
        }
        return $array;
    }

    function wordParser ($string){
        $newchar = array();
        $chars = $this->mbStringToArray($string);

        for ($i=0; $i < sizeof($chars); $i++){
            $newchar[$i] = $this->parse($chars[$i]);
        }
        return implode($newchar);
    }

    function normalize($string){
        $words = str_split($string);
        for ($i=0; $i<sizeof($words); $i++){
            if ($words[$i] == "o" && $words[$i+1] == "u"){
                unset($words[$i]);
            }
        }
        return implode($words);

    }
    function passwordMaker(){
        /*  $words = explode (".", $string);
          $newPassword = ucfirst($words[0]);
          $newPassword.="!!";
          return $newPassword; */
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    //
}
