<?php

	loadlib("cooperhewitt_api");

	#################################################################

	function sms_objects_object(){
	
		$out = array();

		$body = request_str("Body");
		$body = explode(' ', $body);

		if ( $body[1] == "help"){
			sms_output_help("Type the word object followed by a valid object ID.");
		}

		$object_id = $body[1];
		$extra = $body[2];

		### call getInfo with object ID

		$args = array(
			"object_id" => $object_id,
			"access_token" => $GLOBALS['cfg']['cooperhewitt_api_access_token'],
		);

		$rsp = cooperhewitt_api_call("cooperhewitt.objects.getInfo", $args);

		$data = json_decode($rsp['body'], 'as hash');
		
		if ($data['stat'] != 'ok'){
			sms_output_error(404, "Object not found");
		}

		$out['description'] = $data['object']['title'] . "\n" . $data['object']['url'];

		if ($data['object']['description']){
			$out['object'] = $data['object']['description'];
		}

		$more = array();

		$more['media'] = $data['object']['images'][0]['n']['url'];

		if ( $extra ){
			$out['extra'] = $extra;
		}

		sms_output_ok($out, $more);
	}

	#################################################################

	function sms_objects_random(){

		$body = request_str("Body");
		$body = explode(' ', $body);

		if ( $body[1] == "help"){
			sms_output_help("This just returns a random object.");
		}
	
		$out = array();

		$args = array(
			"access_token" => $GLOBALS['cfg']['cooperhewitt_api_access_token'],
			"has_image" => "YES",
		);

		$rsp = cooperhewitt_api_call("cooperhewitt.objects.getRandom", $args);

		$data = json_decode($rsp['body'], 'as hash');
		
		if ($data['stat'] != 'ok'){
			sms_output_error(404, "Cant get no...");
		}

		$out['title'] = $data['object']['title'];

		if ($data['object']['description']){
			$out['object'] = $data['object']['description'];
		}

		sms_output_ok($out);

	}
