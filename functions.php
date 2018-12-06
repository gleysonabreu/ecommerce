<?php

    use \Hcode\Model\User;
    use \Hcode\Model\Cart;
    use \Hcode\Model\Product;
	function formatPrice($vlprice){
		return number_format($vlprice, 2, ",", ".");
	}

	function checkLogin($inadmin = true){
	    return User::checkLogin($inadmin);
    }

    function getUserName(){
	    $user = User::getFromSession();
	    return $user->getdesperson();
    }

    function getCartNrQtd(){
	    $cart = Cart::getFromSession();
	    $totals = $cart->getProductsTotals();

	    return $totals['nrqtd'];
    }
    function getCartVlSubTotal(){
        $cart = Cart::getFromSession();
        $totals = $cart->getProductsTotals();



        return formatPrice($totals['vltotal']);

    }

    function formatDate($date){
	    return date("d/m/Y", strtotime($date));
    }
?>