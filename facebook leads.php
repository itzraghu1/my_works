<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
Use App\Models\Account;
class LeadsExternal extends Controller
{
	function sendEmail($name='',$email='')
	{
		if(!empty($name) && !empty($email)){
			$url='https://www.nhsnegligenceclaim.com';
			$to_email = $email;
			$subject = 'Medical Negligence';
			$html='<html><head><title>medicalnegligencedirect</title></head><body>';
			$html.= '<p>Dear '.$name.'</p>'; 
			$html.='<p>Thank you for your enquiry regarding Medical Negligence. One of our advisors will be in touch with you within 1 hour to provide a Free, No Obligation Assessment of your case.</p>';

			$html.='<p>Thank You</p>';
			$html.='<a href="https://www.nhsnegligenceclaim.com">'.$url.'</a><br>'; 
			$html.='<img src="https://www.nhsnegligenceclaim.com/wp-content/uploads/2016/11/logo-new.png" alt="nhsnegligenceclaim">'; 
			$html.='</body></html>';
			$from = 'claims@medicalnegligencedirect.com';

			$headers = "From: " .$from. "\r\n";
			$headers .= "Reply-To: ".$from. "\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
			$message = $html;
			mail($to_email,$subject,$message,$headers);
			echo 'sent mail';
		}else{
			echo 'fail mail';
		}
	}

	public function getLead(){
		$st = date('Y-m-d',strtotime("-1 days"));
		$t = date('00:00:00+0000');
		$create_time = strtotime($st.'T'.$t);
		$filter = [array(
			"field" => "time_created",
			"operator" => "GREATER_THAN",
			"value" => $create_time
		)];
		$filter = json_encode($filter);
		$page_access_token='EAADB8YA7LhoBAMBsbZA4DgPJA3SwAfHLEr1dnSWu5qLtxAG0HX3toKSPZAaOAZBbtW1rHIN1YtjwPgJ7OZBzd5K9DUjkhwuURgTeh3ZBIqRPmbKtH2mGYBc5h6okx3A6NrZBBJhCUvWVLnffRfIau889kts4UtrZAxCvHepOnjirAZDZD';
		$URL='https://graph.facebook.com/v4.0/912590792245688/leadgen_forms?access_token='.$page_access_token;
		$curl_handle=curl_init();
		curl_setopt($curl_handle,CURLOPT_URL,$URL);
		curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
		curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
		$buffer = curl_exec($curl_handle);
		curl_close($curl_handle);
		if (empty($buffer)){
			print "Nothing returned from url.<p>";
		}
		else{
			$someObject = json_decode($buffer,true);

			// echo "<pre>";
			// print_r($someObject);
			// exit;
			$data=[];
			if(!empty($someObject['data'])){
				foreach ($someObject['data'] as $key => $value) {

					$LEAD_URL='https://graph.facebook.com/v4.0/'.$value['id'].'/leads?filtering='.$filter.'&access_token='.$page_access_token.'&limit=100000';
					$curl_handle_leada=curl_init();
					curl_setopt($curl_handle_leada,CURLOPT_URL,$LEAD_URL);
					curl_setopt($curl_handle_leada,CURLOPT_CONNECTTIMEOUT,2);
					curl_setopt($curl_handle_leada,CURLOPT_RETURNTRANSFER,1);
					$form_leads = curl_exec($curl_handle_leada);
					curl_close($curl_handle_leada);
					$response = json_decode($form_leads,true);
					$i=0;
					if(!empty($response['data'])){
						foreach ($response['data'] as $key => $value1) {
							$data['cf_853']=!empty($value1['created_time'])?$value1['created_time']:'';
							/*$data['form_id']=$value['id'];*/
							if(!empty($value1['field_data'][0]['name'])){
								$zero = $value1['field_data'][0]['name'];
								$data["$zero"]=!empty($value1['field_data'][0]['values'][0])?$value1['field_data'][0]['values'][0]:'';
							}
							if(!empty($value1['field_data'][1]['name'])){
								$one = $value1['field_data'][1]['name'];
								$data["$one"]=!empty($value1['field_data'][1]['values'][0])?$value1['field_data'][1]['values'][0]:'';
							}
							if(!empty($value1['field_data'][2]['name'])){
								$two = $value1['field_data'][2]['name'];
								$data["$two"]=!empty($value1['field_data'][2]['values'][0])?$value1['field_data'][2]['values'][0]:'';
							}
							if(!empty($value1['field_data'][3]['name'])){
								$three = $value1['field_data'][3]['name'];
								$data["$three"]=!empty($value1['field_data'][3]['values'][0])?$value1['field_data'][3]['values'][0]:'';
							}
							if(!empty($value1['field_data'][4]['name'])){
								$four = $value1['field_data'][4]['name'];
								$data["$four"]=!empty($value1['field_data'][4]['values'][0])?$value1['field_data'][4]['values'][0]:'';
							}

							echo "<pre>";
							print_r($data);
							// exit;

							// $this->createLeadInVtiger($data);
						}
						$i++;
					}

				}

			}else{
				echo 'token failed';
			}
		}
	}

	public function createLeadInVtiger($elements='',$phone=''){
		date_default_timezone_set('Europe/London');
		$data['name']=!empty($elements['full_name'])?$elements['full_name']:'';
		$data['email']=!empty($elements['email'])?$elements['email']:'';
		if(!empty($elements['date_of_negligence'])){
			$data['date_of_injury']=!empty($elements['date_of_negligence'])?$elements['date_of_negligence']:'';
		}else{
			$data['date_of_injury']=!empty($elements['date_of_injury_or_negligence'])?$elements['date_of_injury_or_negligence']:'';
		}
		$data['mobile']=!empty($elements['phone_number'])?$elements['phone_number']:'';
		$data['sales_agent']='1';
		$data['lead_source']='Facebook';
		$data['account_status']='New';
		$data['date_lead_recieved']=!empty(date('d-m-Y',strtotime($elements['cf_853'])))?date('d-m-Y',strtotime($elements['cf_853'])):'';
		$data['created_at']=date('Y-m-d H:i:s');
		$data['updated_at']=date('Y-m-d H:i:s');
		//$jsonValue = json_encode($data,true);
		
		$value= _arefy(Account::where(['email'=>$data['email'],'date_lead_recieved'=>$data['date_lead_recieved']])->get());
		if(empty($value)){
			$get=Account::orderBy('id','desc')->first();
			if(!empty($get)){
				$id=$get->id;
			}else{
				$id=0;
			}
			$data['customer_number']='ACCOUNT'.$id;
		   // $sms = $this->sendEmail($data['name'],$data['email']);
			$response =Account::insert($data);
			if($response){
				echo 'success';
			}else{
				echo 'failed';
			}
		}

	}
}
