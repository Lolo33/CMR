<?php

include "vendor/autoload.php";

$stripe = array(
    "secret_key"      => "sk_test_OjNWaci3qA3iIGEc5fqW7bfJ",
    "publishable_key" => "pk_test_RYUONkH3SSrBGd9vw1VJZHfb"
);

\Stripe\Stripe::setApiKey($stripe['secret_key']);

		function create_stripe_profil ($business_id, $mail){
			$db = Config::getBddInstance();

			$customer = \Stripe\Customer::create(array(
				'email' => $mail
			));

		    $req = $db->prepare("UPDATE business SET stripe_id = :user_stripe_id, mail = :email WHERE id = :id_bus;");
		    $req->bindValue(":user_stripe_id", $customer->id, PDO::PARAM_STR);
		    $req->bindValue(":email", $mail, PDO::PARAM_STR);
		    $req->bindValue(":id_bus", $business_id, PDO::PARAM_INT);
		    $req->execute();

		    return $customer->id;
		}

		function add_source_for_stripe ($user_stripe_id, $source){
			$db = Config::getBddInstance();

            $customer = \Stripe\Customer::retrieve($user_stripe_id);
		    
		    $new_source = $customer->sources->create(array("source" => $source));
		    
		    $req = $db->prepare("INSERT INTO source_stripe (user_stripe_id, user_id, source_id) VALUES (:user_stripe_id, :id_membre, :source_id)");
		    $req->bindValue(":user_stripe_id", $user_stripe_id, PDO::PARAM_STR);
		    $req->bindValue(":id_membre", 2, PDO::PARAM_INT);
		    $req->bindValue(":source_id", $new_source->id, PDO::PARAM_STR);
		    $req->execute();

		    return $new_source;
		    
		}
		function charge_stripe_by_source ($user_stripe_id, $source, $montant, $capture){
			$db = Config::getBddInstance();

			$charge = \Stripe\Charge::create(array(
			    'customer' => $user_stripe_id,
			    'source' => $source,
			    'amount'   => $montant,
			    'currency' => 'eur',
                'capture' => $capture
			));
			return $charge;
		}

		function charge_stripe_by_user_stripe ($user_stripe_id, $montant){
			$charge = \Stripe\Charge::create(array(
			    'customer' => $user_stripe_id,
			    'amount'   => $montant,
			    'currency' => 'eur'
			));
			return $charge->id;
		}

		function list_sources_stripe($user_stripe_id){
			$db = Config::getBddInstance();
			$customer = \Stripe\Customer::retrieve($user_stripe_id);
			return $customer->sources;
		}

?>