<?php

class Session{
    public static function start(){
        if(strlen(session_id()) == 0 ){
        	
            Config::includeSessionClass();  
            
            session_start();
        }
    }
}